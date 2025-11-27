<?php

namespace App\Services;

use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DuitkuService
{
    protected $gateway;
    protected $merchantCode;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->gateway = PaymentGateway::where('code', 'like', 'duitku%')
            ->where('is_active', true)
            ->first();

        if (!$this->gateway) {
            throw new \Exception('Payment gateway Duitku not found or inactive');
        }

        $this->merchantCode = $this->gateway->merchant_code;
        $this->apiKey = $this->gateway->api_key;
        
        // Determine base URL based on environment
        $this->baseUrl = $this->gateway->environment === 'production'
            ? 'https://passport.duitku.com/webapi/api/merchant'
            : 'https://sandbox.duitku.com/webapi/api/merchant';
    }

    /**
     * Create payment transaction
     */
    public function createTransaction($params)
    {
        try {
            $merchantOrderId = $params['merchantOrderId'];
            $paymentAmount = $params['paymentAmount'];
            $paymentMethod = $params['paymentMethod'];
            $productDetails = $params['productDetails'];
            $email = $params['email'];
            $customerVaName = $params['customerVaName'];
            $callbackUrl = $params['callbackUrl'];
            $returnUrl = $params['returnUrl'];
            $expiryPeriod = $params['expiryPeriod'] ?? 60; // Default 60 minutes

            // Generate signature: MD5(merchantCode + merchantOrderId + paymentAmount + apiKey)
            $signature = md5($this->merchantCode . $merchantOrderId . $paymentAmount . $this->apiKey);

            $payload = [
                'merchantCode' => $this->merchantCode,
                'paymentAmount' => $paymentAmount,
                'paymentMethod' => $paymentMethod,
                'merchantOrderId' => $merchantOrderId,
                'productDetails' => $productDetails,
                'customerVaName' => $customerVaName,
                'email' => $email,
                'callbackUrl' => $callbackUrl,
                'returnUrl' => $returnUrl,
                'signature' => $signature,
                'expiryPeriod' => $expiryPeriod,
            ];

            // Add optional parameters if provided
            if (isset($params['phoneNumber'])) {
                $payload['phoneNumber'] = $params['phoneNumber'];
            }

            if (isset($params['itemDetails'])) {
                $payload['itemDetails'] = $params['itemDetails'];
            }

            if (isset($params['customerDetail'])) {
                $payload['customerDetail'] = $params['customerDetail'];
            }

            if (isset($params['additionalParam'])) {
                $payload['additionalParam'] = $params['additionalParam'];
            }

            Log::info('Duitku Create Transaction Request', [
                'payload' => array_merge($payload, ['signature' => 'HIDDEN']),
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/v2/inquiry', $payload);

            $result = $response->json();

            Log::info('Duitku Create Transaction Response', [
                'status' => $response->status(),
                'result' => $result,
            ]);

            if ($response->successful() && isset($result['statusCode']) && $result['statusCode'] === '00') {
                return [
                    'success' => true,
                    'data' => [
                        'reference' => $result['reference'],
                        'payment_url' => $result['paymentUrl'] ?? null,
                        'va_number' => $result['vaNumber'] ?? null,
                        'qr_string' => $result['qrString'] ?? null,
                        'amount' => $result['amount'],
                        'status_message' => $result['statusMessage'],
                    ],
                ];
            }

            return [
                'success' => false,
                'message' => $result['statusMessage'] ?? 'Failed to create transaction',
                'error' => $result,
            ];
        } catch (\Exception $e) {
            Log::error('Duitku Create Transaction Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify callback signature
     * Formula: MD5(merchantCode + amount + merchantOrderId + apiKey)
     */
    public function verifyCallbackSignature($merchantCode, $amount, $merchantOrderId, $signature)
    {
        $calculatedSignature = md5($merchantCode . $amount . $merchantOrderId . $this->apiKey);
        return hash_equals($calculatedSignature, $signature);
    }

    /**
     * Process callback data and return transaction info
     */
    public function processCallback(array $callbackData)
    {
        return [
            'merchant_code' => $callbackData['merchantCode'] ?? null,
            'amount' => $callbackData['amount'] ?? null,
            'merchant_order_id' => $callbackData['merchantOrderId'] ?? null,
            'product_detail' => $callbackData['productDetail'] ?? null,
            'additional_param' => $callbackData['additionalParam'] ?? null,
            'payment_code' => $callbackData['paymentCode'] ?? null,
            'result_code' => $callbackData['resultCode'] ?? null,
            'merchant_user_id' => $callbackData['merchantUserId'] ?? null,
            'reference' => $callbackData['reference'] ?? null,
            'signature' => $callbackData['signature'] ?? null,
            'publisher_order_id' => $callbackData['publisherOrderId'] ?? null,
            'sp_user_hash' => $callbackData['spUserHash'] ?? null,
            'settlement_date' => $callbackData['settlementDate'] ?? null,
            'issuer_code' => $callbackData['issuerCode'] ?? null,
        ];
    }

    /**
     * Check transaction status
     */
    public function checkTransactionStatus($merchantOrderId)
    {
        try {
            $signature = md5($this->merchantCode . $merchantOrderId . $this->apiKey);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/transactionStatus', [
                'merchantCode' => $this->merchantCode,
                'merchantOrderId' => $merchantOrderId,
                'signature' => $signature,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['statusCode'])) {
                return [
                    'success' => true,
                    'data' => $result,
                ];
            }

            return [
                'success' => false,
                'message' => $result['statusMessage'] ?? 'Failed to check transaction status',
            ];
        } catch (\Exception $e) {
            Log::error('Duitku Check Status Error', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
