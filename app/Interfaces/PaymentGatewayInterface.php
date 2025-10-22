<?php

namespace App\Interfaces;

interface PaymentGatewayInterface
{
    /**
     * Create a payment intent
     *
     * @param float $amount
     * @param string $currency
     * @param array $metadata
     * @return array
     */
    public function createPaymentIntent(float $amount, string $currency = 'usd', array $metadata = []): array;

    /**
     * Confirm a payment
     *
     * @param string $paymentId
     * @return array
     */
    public function confirmPayment(string $paymentId): array;

    /**
     * Refund a payment
     *
     * @param string $paymentId
     * @param float|null $amount
     * @return array
     */
    public function refundPayment(string $paymentId, ?float $amount = null): array;

    /**
     * Get payment details
     *
     * @param string $paymentId
     * @return array
     */
    public function getPaymentDetails(string $paymentId): array;

    /**
     * Verify webhook signature
     *
     * @param string $payload
     * @param string $signature
     * @return bool
     */
    public function verifyWebhook(string $payload, string $signature): bool;
}
