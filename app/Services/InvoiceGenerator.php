<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceGenerator
{
    /**
     * Generate invoice PDF for an order
     *
     * @param Order $order
     * @param bool $download
     * @return \Illuminate\Http\Response|string
     */
    public function generate(Order $order, bool $download = false)
    {
        $invoiceData = $this->prepareInvoiceData($order);

        $pdf = Pdf::loadView('invoices.template', $invoiceData);

        // Save invoice to storage
        $filename = $this->getInvoiceFilename($order);
        $path = "invoices/{$filename}";
        Storage::put($path, $pdf->output());

        if ($download) {
            return $pdf->download($filename);
        }

        return $path;
    }

    /**
     * Prepare invoice data
     *
     * @param Order $order
     * @return array
     */
    protected function prepareInvoiceData(Order $order): array
    {
        return [
            'invoice_number' => $this->generateInvoiceNumber($order),
            'order' => $order->load(['product', 'user']),
            'order_code' => $order->order_code,
            'date' => now()->format('F d, Y'),
            'customer' => [
                'name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone,
                'address' => $order->user->address,
            ],
            'items' => [
                [
                    'name' => $order->product->name,
                    'quantity' => $order->count,
                    'price' => $order->product->price,
                    'total' => $order->totalPrice,
                ]
            ],
            'subtotal' => $order->totalPrice,
            'tax' => 0, // Add tax calculation if needed
            'total' => $order->totalPrice,
            'company' => [
                'name' => config('app.name', 'Winniema\'s Enterprise'),
                'address' => 'Your Company Address',
                'phone' => 'Your Phone Number',
                'email' => 'info@yourcompany.com',
            ],
        ];
    }

    /**
     * Generate invoice number
     *
     * @param Order $order
     * @return string
     */
    protected function generateInvoiceNumber(Order $order): string
    {
        return 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get invoice filename
     *
     * @param Order $order
     * @return string
     */
    protected function getInvoiceFilename(Order $order): string
    {
        $invoiceNumber = $this->generateInvoiceNumber($order);
        return "{$invoiceNumber}.pdf";
    }

    /**
     * Get invoice path
     *
     * @param Order $order
     * @return string|null
     */
    public function getInvoicePath(Order $order): ?string
    {
        $filename = $this->getInvoiceFilename($order);
        $path = "invoices/{$filename}";

        if (Storage::exists($path)) {
            return $path;
        }

        return null;
    }

    /**
     * Check if invoice exists
     *
     * @param Order $order
     * @return bool
     */
    public function exists(Order $order): bool
    {
        return $this->getInvoicePath($order) !== null;
    }

    /**
     * Delete invoice
     *
     * @param Order $order
     * @return bool
     */
    public function delete(Order $order): bool
    {
        $path = $this->getInvoicePath($order);

        if ($path) {
            return Storage::delete($path);
        }

        return false;
    }
}
