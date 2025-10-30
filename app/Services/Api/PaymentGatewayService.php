<?php

declare(strict_types=1);

namespace App\Services\Api;

use App\Models\Payment;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Psr\Log\LoggerInterface;

class PaymentGatewayService
{
    public function __construct(private ?LoggerInterface $logger = null) {}

    /**
     * Create a payment session for the mobile SDK to collect card details securely.
     */
    public function createSession(Payment $payment): array
    {
        $baseUrl = rtrim(config('payments.base_url'), '/');
        $merchantId = config('payments.merchant_id');
        $username = config('payments.api_username');
        $password = config('payments.api_password');
        $apiVersion = config('payments.api_version');
        
        $endpointTpl = config('payments.endpoints.create_session');
        $endpoint = strtr($endpointTpl, [
            '{version}' => $apiVersion,
            '{merchantId}' => urlencode((string)$merchantId),
        ]);
        
        $url = $baseUrl . '/' . ltrim($endpoint, '/');

        // Create proper payload for session creation
        $payload = [
            'session' => [
                'id' => null // Let gateway generate session ID
            ]
        ];

        try {
            $res = Http::withBasicAuth($username, $password)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->timeout(30)
                ->post($url, $payload)
                ->throw();

            $data = $res->json();
            $sessionId = $data['session']['id'] ?? null;
            
            if (!$sessionId) {
                throw new \RuntimeException('Gateway did not return a session id.');
            }

            $payment->update(['session_id' => (string)$sessionId]);

            return [
                'session_id' => $sessionId,
                'provider' => config('payments.provider'),
                'raw' => $data,
            ];
        } catch (RequestException $e) {
            $this->logger?->error('Failed to create payment session', [
                'error' => $e->getMessage(),
                'response' => $e->response?->body(),
            ]);
            throw new \RuntimeException('Failed to create payment session: ' . $e->getMessage(), previous: $e);
        }
    }

    /**
     * Process payment with session ID after mobile SDK has updated the session with card details
     */
    public function payWithSession(Payment $payment, string $sessionId): array
    {
        $baseUrl = rtrim(config('payments.base_url'), '/');
        $merchantId = config('payments.merchant_id');
        $username = config('payments.api_username');
        $password = config('payments.api_password');
        $apiVersion = config('payments.api_version');
        
        $tpl = config('payments.endpoints.pay');

        // Create unique order and transaction IDs to avoid duplicates
        $orderId = 'order-' . $payment->order_id . '-' . $payment->id;
        $transactionId = 'txn-' . $payment->id . '-' . uniqid();

        $endpoint = strtr($tpl, [
            '{version}' => $apiVersion,
            '{merchantId}' => urlencode((string)$merchantId),
            '{orderId}' => urlencode($orderId),
            '{transactionId}' => urlencode($transactionId),
        ]);
        
        $url = $baseUrl . '/' . ltrim($endpoint, '/');

        $payload = [
            'apiOperation' => 'PAY',
            'order' => [
                'amount' => number_format((float)$payment->amount, 2, '.', ''),
                'currency' => $payment->currency ?? config('payments.currency'),
            ],
            'session' => ['id' => $sessionId],
            'sourceOfFunds' => ['type' => 'CARD'],
            'transaction' => [
                'source' => 'INTERNET'
            ]
        ];

        try {
            $res = Http::withBasicAuth($username, $password)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->timeout(30)
                ->put($url, $payload)
                ->throw();

            $data = $res->json();
            
            $gatewayCode = strtolower((string)($data['response']['gatewayCode'] ?? ''));
            $ok = in_array($gatewayCode, [
                'approved', 'approved_auto', 'approved_pending_settlement', 
                'pending', 'success', 'submitted'
            ], true);

            if ($ok && !empty($data['transaction']['id'])) {
                $payment->update([
                    'provider_txn_id' => $data['transaction']['id']
                ]);
            }

            return [
                'ok' => $ok,
                'status' => $ok ? 'succeeded' : 'failed',
                'provider_txn_id' => $data['transaction']['id'] ?? null,
                'raw' => $data,
            ];
        } catch (RequestException $e) {
            $this->logger?->error('Payment failed', [
                'error' => $e->getMessage(),
                'response' => $e->response?->body(),
                'payload' => $payload,
            ]);
            
            // Handle specific duplicate transaction error
            if (str_contains($e->getMessage(), 'Order already has an existing initial')) {
                // Try to retrieve existing transaction
                $retrieveResult = $this->retrieveTransaction($orderId);
                if ($retrieveResult['ok']) {
                    return $retrieveResult;
                }
            }
            
            return [
                'ok' => false, 
                'status' => 'failed', 
                'message' => 'PAY failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Retrieve transaction details from the gateway
     */
    public function retrieveTransaction(string $orderId, ?string $providerTxnId = null): array
    {
        $baseUrl = rtrim(config('payments.base_url'), '/');
        $merchantId = config('payments.merchant_id');
        $username = config('payments.api_username');
        $password = config('payments.api_password');
        $apiVersion = config('payments.api_version');
        
        $tpl = config('payments.endpoints.retrieve_order') ?? config('payments.endpoints.retrieve_txn');

        // If we have transaction ID, use it; otherwise retrieve order
        if ($providerTxnId) {
            $endpoint = strtr($tpl, [
                '{version}' => $apiVersion,
                '{merchantId}' => urlencode((string)$merchantId),
                '{orderId}' => urlencode($orderId),
                '{transactionId}' => urlencode($providerTxnId),
            ]);
        } else {
            // Retrieve order endpoint (adjust based on your gateway API)
            $endpoint = strtr(config('payments.endpoints.retrieve_order'), [
                '{version}' => $apiVersion,
                '{merchantId}' => urlencode((string)$merchantId),
                '{orderId}' => urlencode($orderId),
            ]);
        }

        $url = $baseUrl . '/' . ltrim($endpoint, '/');

        try {
            $res = Http::withBasicAuth($username, $password)
                ->withHeaders(['Accept' => 'application/json'])
                ->timeout(30)
                ->get($url)
                ->throw();

            $data = $res->json();

            $gatewayCode = $data['response']['gatewayCode'] ?? null;
            $amount = $data['order']['amount'] ?? ($data['transaction']['amount'] ?? null);
            $currency = $data['order']['currency'] ?? ($data['transaction']['currency'] ?? null);
            $providerId = $data['transaction']['id'] ?? null;

            $lower = is_string($gatewayCode) ? strtolower($gatewayCode) : '';
            $successCodes = [
                'approved', 'approved_auto', 'approved_pending_settlement', 
                'success', 'succeeded', 'paid', 'captured', 'submitted'
            ];
            $failedCodes = ['declined', 'error', 'failed', 'timed_out'];

            if (in_array($lower, array_map('strtolower', $successCodes), true)) {
                $ok = true;
                $status = 'succeeded';
            } elseif (in_array($lower, array_map('strtolower', $failedCodes), true)) {
                $ok = false;
                $status = 'failed';
            } else {
                $ok = false;
                $status = 'unknown';
            }

            return [
                'ok' => $ok,
                'status' => $status,
                'amount' => $amount !== null ? (float)$amount : null,
                'currency' => $currency,
                'provider_txn_id' => $providerId,
                'raw' => $data,
            ];
        } catch (RequestException $e) {
            $this->logger?->error('Retrieve transaction failed', ['exception' => $e]);
            return [
                'ok' => false,
                'status' => 'failed',
                'message' => 'Failed to retrieve transaction: ' . $e->getMessage(),
            ];
        }
    }
}