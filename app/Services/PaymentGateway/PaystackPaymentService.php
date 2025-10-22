<?php

namespace App\Services\PaymentGateway;

use App\Interfaces\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class PaystackPaymentService implements PaymentGatewayInterface
{
    protected string $baseUrl;
    protected string $secretKey;

    public function __construct()
    {
        $this->baseUrl = config('services.paystack.base_url', 'https://api.paystack.co');
        $this->secretKey = config('services.paystack.secret_key');
    }

    /**
     * Create a payment intent (Initialize transaction in Paystack)
     *
     * @param float $amount
     * @param string $currency
     * @param array $metadata
     * @return array
     */
    public function createPaymentIntent(float $amount, string $currency = 'NGN', array $metadata = []): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/transaction/initialize', [
                'amount' => $amount * 100, // Convert to kobo (smallest currency unit)
                'currency' => strtoupper($currency),
                'email' => $metadata['email'] ?? config('services.paystack.merchant_email'),
                'metadata' => $metadata,
                'callback_url' => $metadata['callback_url'] ?? route('paystack.callback'),
            ]);

            $data = $response->json();

            if ($response->successful() && $data['status']) {
                return [
                    'success' => true,
                    'payment_id' => $data['data']['reference'],
                    'authorization_url' => $data['data']['authorization_url'],
                    'access_code' => $data['data']['access_code'],
                    'amount' => $amount,
                    'currency' => $currency,
                    'status' => 'pending',
                ];
            }

            return [
                'success' => false,
                'error' => $data['message'] ?? 'Payment initialization failed',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Confirm a payment (Verify transaction in Paystack)
     *
     * @param string $paymentId (reference)
     * @return array
     */
    public function confirmPayment(string $paymentId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get($this->baseUrl . '/transaction/verify/' . $paymentId);

            $data = $response->json();

            if ($response->successful() && $data['status']) {
                $transaction = $data['data'];
                
                return [
                    'success' => true,
                    'payment_id' => $transaction['reference'],
                    'status' => $transaction['status'],
                    'amount' => $transaction['amount'] / 100,
                    'currency' => $transaction['currency'],
                    'paid_at' => $transaction['paid_at'],
                    'channel' => $transaction['channel'],
                    'customer' => [
                        'email' => $transaction['customer']['email'],
                        'customer_code' => $transaction['customer']['customer_code'],
                    ],
                ];
            }

            return [
                'success' => false,
                'error' => $data['message'] ?? 'Payment verification failed',
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
     * @param string $paymentId (reference)
     * @param float|null $amount
     * @return array
     */
    public function refundPayment(string $paymentId, ?float $amount = null): array
    {
        try {
            $payload = ['transaction' => $paymentId];
            
            if ($amount !== null) {
                $payload['amount'] = $amount * 100; // Convert to kobo
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/refund', $payload);

            $data = $response->json();

            if ($response->successful() && $data['status']) {
                return [
                    'success' => true,
                    'refund_id' => $data['data']['id'],
                    'transaction_reference' => $data['data']['transaction_reference'],
                    'amount' => $data['data']['amount'] / 100,
                    'currency' => $data['data']['currency'],
                    'status' => $data['data']['status'],
                ];
            }

            return [
                'success' => false,
                'error' => $data['message'] ?? 'Refund failed',
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
     * @param string $paymentId (reference)
     * @return array
     */
    public function getPaymentDetails(string $paymentId): array
    {
        return $this->confirmPayment($paymentId);
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
        $computedSignature = hash_hmac('sha512', $payload, $this->secretKey);
        return hash_equals($computedSignature, $signature);
    }

    /**
     * Get all banks (useful for bank transfers)
     *
     * @param string $country
     * @return array
     */
    public function getBanks(string $country = 'nigeria'): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get($this->baseUrl . '/bank', [
                'country' => $country,
            ]);

            $data = $response->json();

            if ($response->successful() && $data['status']) {
                return [
                    'success' => true,
                    'banks' => $data['data'],
                ];
            }

            return [
                'success' => false,
                'error' => $data['message'] ?? 'Failed to fetch banks',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create a transfer recipient
     *
     * @param array $recipientData
     * @return array
     */
    public function createTransferRecipient(array $recipientData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/transferrecipient', [
                'type' => 'nuban',
                'name' => $recipientData['name'],
                'account_number' => $recipientData['account_number'],
                'bank_code' => $recipientData['bank_code'],
                'currency' => $recipientData['currency'] ?? 'NGN',
            ]);

            $data = $response->json();

            if ($response->successful() && $data['status']) {
                return [
                    'success' => true,
                    'recipient_code' => $data['data']['recipient_code'],
                    'details' => $data['data'],
                ];
            }

            return [
                'success' => false,
                'error' => $data['message'] ?? 'Failed to create recipient',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Initiate a transfer
     *
     * @param string $recipientCode
     * @param float $amount
     * @param string $reason
     * @return array
     */
    public function initiateTransfer(string $recipientCode, float $amount, string $reason = ''): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/transfer', [
                'source' => 'balance',
                'reason' => $reason,
                'amount' => $amount * 100, // Convert to kobo
                'recipient' => $recipientCode,
            ]);

            $data = $response->json();

            if ($response->successful() && $data['status']) {
                return [
                    'success' => true,
                    'transfer_code' => $data['data']['transfer_code'],
                    'amount' => $data['data']['amount'] / 100,
                    'status' => $data['data']['status'],
                ];
            }

            return [
                'success' => false,
                'error' => $data['message'] ?? 'Transfer failed',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
