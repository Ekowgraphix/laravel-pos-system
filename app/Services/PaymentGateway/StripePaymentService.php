<?php

namespace App\Services\PaymentGateway;

use App\Interfaces\PaymentGatewayInterface;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Refund;
use Stripe\Webhook;
use Exception;

class StripePaymentService implements PaymentGatewayInterface
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a payment intent
     *
     * @param float $amount
     * @param string $currency
     * @param array $metadata
     * @return array
     */
    public function createPaymentIntent(float $amount, string $currency = 'usd', array $metadata = []): array
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // Convert to cents
                'currency' => $currency,
                'metadata' => $metadata,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return [
                'success' => true,
                'payment_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret,
                'amount' => $amount,
                'currency' => $currency,
                'status' => $paymentIntent->status,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Confirm a payment
     *
     * @param string $paymentId
     * @return array
     */
    public function confirmPayment(string $paymentId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentId);

            return [
                'success' => true,
                'payment_id' => $paymentIntent->id,
                'status' => $paymentIntent->status,
                'amount' => $paymentIntent->amount / 100,
                'currency' => $paymentIntent->currency,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Refund a payment
     *
     * @param string $paymentId
     * @param float|null $amount
     * @return array
     */
    public function refundPayment(string $paymentId, ?float $amount = null): array
    {
        try {
            $refundData = ['payment_intent' => $paymentId];
            
            if ($amount !== null) {
                $refundData['amount'] = $amount * 100; // Convert to cents
            }

            $refund = Refund::create($refundData);

            return [
                'success' => true,
                'refund_id' => $refund->id,
                'amount' => $refund->amount / 100,
                'status' => $refund->status,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get payment details
     *
     * @param string $paymentId
     * @return array
     */
    public function getPaymentDetails(string $paymentId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentId);

            return [
                'success' => true,
                'payment_id' => $paymentIntent->id,
                'amount' => $paymentIntent->amount / 100,
                'currency' => $paymentIntent->currency,
                'status' => $paymentIntent->status,
                'created_at' => date('Y-m-d H:i:s', $paymentIntent->created),
                'metadata' => $paymentIntent->metadata->toArray(),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify webhook signature
     *
     * @param string $payload
     * @param string $signature
     * @return bool
     */
    public function verifyWebhook(string $payload, string $signature): bool
    {
        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
