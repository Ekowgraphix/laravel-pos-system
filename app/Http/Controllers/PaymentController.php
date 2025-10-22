<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentGateway\PaystackPaymentService;
use App\Services\InvoiceGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PaystackPaymentService $paystack;
    protected InvoiceGenerator $invoiceGenerator;

    public function __construct(
        PaystackPaymentService $paystack,
        InvoiceGenerator $invoiceGenerator
    ) {
        $this->paystack = $paystack;
        $this->invoiceGenerator = $invoiceGenerator;
    }

    /**
     * Show checkout page and initialize payment
     */
    public function checkout(Order $order)
    {
        // Ensure order belongs to authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to order');
        }

        // Check if order is already paid
        if ($order->payment_status === 'completed') {
            return redirect()->route('customerOrders', $order->order_code)
                ->with('info', 'This order has already been paid.');
        }

        // Initialize payment with Paystack
        $result = $this->paystack->createPaymentIntent(
            $order->totalPrice,
            config('services.paystack.currency', 'GHS'),
            [
                'email' => auth()->user()->email,
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'customer_name' => auth()->user()->name,
                'callback_url' => route('paystack.callback'),
            ]
        );

        if (!$result['success']) {
            return back()->with('error', 'Payment initialization failed: ' . $result['error']);
        }

        // Store payment reference
        $order->update([
            'payment_reference' => $result['payment_id'],
        ]);

        // Redirect to Paystack payment page
        return redirect($result['authorization_url']);
    }

    /**
     * Handle payment callback from Paystack
     */
    public function callback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('orderList')
                ->with('error', 'No payment reference provided');
        }

        // Verify payment
        $result = $this->paystack->confirmPayment($reference);

        if (!$result['success']) {
            return redirect()->route('orderList')
                ->with('error', 'Payment verification failed: ' . $result['error']);
        }

        // Find order by reference
        $order = Order::where('payment_reference', $reference)->first();

        if (!$order) {
            return redirect()->route('orderList')
                ->with('error', 'Order not found');
        }

        // Update order status
        DB::transaction(function () use ($order, $result) {
            $order->update([
                'status' => 1, // 0=Pending, 1=Success, 2=Reject
                'payment_status' => 'completed',
                'paid_at' => now(),
            ]);

            // Generate invoice
            $this->invoiceGenerator->generate($order);
        });

        return redirect()->route('customerOrders', $order->order_code)
            ->with('success', '🎉 Payment Successful! Your order has been confirmed. You can now print your receipt below.');
    }

    /**
     * Handle Paystack webhooks
     */
    public function webhook(Request $request)
    {
        // Verify webhook signature
        $signature = $request->header('x-paystack-signature');
        $payload = $request->getContent();

        if (!$this->paystack->verifyWebhook($payload, $signature)) {
            Log::warning('Invalid Paystack webhook signature');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $event = json_decode($payload, true);

        // Handle different event types
        match($event['event']) {
            'charge.success' => $this->handleSuccessfulCharge($event['data']),
            'charge.failed' => $this->handleFailedCharge($event['data']),
            'transfer.success' => $this->handleTransferSuccess($event['data']),
            'transfer.failed' => $this->handleTransferFailed($event['data']),
            default => Log::info('Unhandled webhook event: ' . $event['event']),
        };

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle successful charge webhook
     */
    protected function handleSuccessfulCharge(array $data): void
    {
        $reference = $data['reference'];
        $order = Order::where('payment_reference', $reference)->first();

        if ($order && $order->payment_status !== 'completed') {
            DB::transaction(function () use ($order) {
                $order->update([
                    'status' => 1, // 0=Pending, 1=Success, 2=Reject
                    'payment_status' => 'completed',
                    'paid_at' => now(),
                ]);

                // Generate and email invoice
                $invoicePath = $this->invoiceGenerator->generate($order);
                
                // TODO: Send email notification
                // Mail::to($order->user)->send(new OrderConfirmation($order, $invoicePath));
            });

            Log::info("Order {$order->order_code} marked as paid via webhook");
        }
    }

    /**
     * Handle failed charge webhook
     */
    protected function handleFailedCharge(array $data): void
    {
        $reference = $data['reference'];
        $order = Order::where('payment_reference', $reference)->first();

        if ($order) {
            $order->update([
                'payment_status' => 'failed',
                'payment_error' => $data['gateway_response'] ?? 'Payment failed',
            ]);

            Log::warning("Payment failed for order {$order->order_code}");
        }
    }

    protected function handleTransferSuccess(array $data): void
    {
        // Handle successful transfers (for refunds, etc.)
        Log::info('Transfer successful', $data);
    }

    protected function handleTransferFailed(array $data): void
    {
        // Handle failed transfers
        Log::warning('Transfer failed', $data);
    }

    /**
     * Handle cash payment
     */
    public function cashPayment(Order $order)
    {
        // Ensure order belongs to authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to order');
        }

        // Check if order is already paid
        if ($order->payment_status === 'completed') {
            return redirect()->route('customerOrders', $order->order_code)
                ->with('info', 'This order has already been paid.');
        }

        // Update order status for cash payment
        DB::transaction(function () use ($order) {
            $order->update([
                'status' => 1, // 0=Pending, 1=Success, 2=Reject
                'payment_status' => 'completed',
                'paid_at' => now(),
                'payment_method' => 'Cash',
            ]);

            // Generate invoice
            $this->invoiceGenerator->generate($order);
        });

        // Redirect to order details with success message and auto-print flag
        return redirect()->route('customerOrders', $order->order_code)
            ->with('success', '💵 Cash Payment Recorded! Your receipt is ready to print.')
            ->with('auto_print', true); // Flag to trigger auto-print
    }
}