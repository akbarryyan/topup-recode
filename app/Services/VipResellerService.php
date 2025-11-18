<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VipResellerService
{
    protected $apiUrl;
    protected $apiId;
    protected $apiKey;
    protected $sign;

    public function __construct()
    {
        $this->apiUrl = config('services.vip_reseller.api_url');
        $this->apiId = config('services.vip_reseller.api_id');
        $this->apiKey = config('services.vip_reseller.api_key');
        $this->sign = config('services.vip_reseller.sign');
    }

    /**
     * Get game services from VIP Reseller API
     */
    public function getGameServices($filterType = null, $filterValue = null, $filterStatus = null)
    {
        try {
            $payload = [
                'key' => $this->apiKey,
                'sign' => $this->sign,
                'type' => 'services',
            ];

            if ($filterType) {
                $payload['filter_type'] = $filterType;
            }

            if ($filterValue) {
                $payload['filter_value'] = $filterValue;
            }

            if ($filterStatus) {
                $payload['filter_status'] = $filterStatus;
            }

            Log::info('VIP Reseller API Request', [
                'url' => $this->apiUrl . '/game-feature',
                'payload' => $payload,
            ]);

            $response = Http::timeout(30)
                ->asForm()
                ->post($this->apiUrl . '/game-feature', $payload);

            Log::info('VIP Reseller API Response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['result']) && $data['result'] === true) {
                    return [
                        'success' => true,
                        'data' => $data['data'] ?? [],
                        'message' => $data['message'] ?? 'Success',
                    ];
                }

                return [
                    'success' => false,
                    'data' => [],
                    'message' => $data['message'] ?? 'Failed to get services',
                ];
            }

            return [
                'success' => false,
                'data' => [],
                'message' => 'API request failed: ' . $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('VIP Reseller API Error: ' . $e->getMessage());

            return [
                'success' => false,
                'data' => [],
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get services by specific game
     */
    public function getServicesByGame($gameName)
    {
        return $this->getGameServices('game', $gameName);
    }

    /**
     * Get available services only
     */
    public function getAvailableServices()
    {
        return $this->getGameServices(null, null, 'available');
    }

    /**
     * Get service stock by code
     */
    public function getServiceStock($serviceCode)
    {
        try {
            $payload = [
                'key' => $this->apiKey,
                'sign' => $this->sign,
                'type' => 'service-stock',
                'service' => $serviceCode,
            ];

            Log::info('VIP Reseller Stock API Request', [
                'url' => $this->apiUrl . '/game-feature',
                'payload' => $payload,
            ]);

            $response = Http::timeout(10) // Reduced to 10 seconds per request
                ->connectTimeout(5)
                ->asForm()
                ->post($this->apiUrl . '/game-feature', $payload);

            Log::info('VIP Reseller Stock API Response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['result']) && $data['result'] === true) {
                    return [
                        'success' => true,
                        'data' => $data['data'] ?? [],
                        'message' => $data['message'] ?? 'Success',
                    ];
                }

                return [
                    'success' => false,
                    'data' => [],
                    'message' => $data['message'] ?? 'Failed to get stock',
                ];
            }

            return [
                'success' => false,
                'data' => [],
                'message' => 'API request failed: ' . $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('VIP Reseller Stock API Error: ' . $e->getMessage());

            return [
                'success' => false,
                'data' => [],
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get prepaid services from VIP Reseller API
     */
    public function getPrepaidServices($filterType = null, $filterValue = null)
    {
        try {
            $payload = [
                'key' => $this->apiKey,
                'sign' => $this->sign,
                'type' => 'services',
            ];

            if ($filterType) {
                $payload['filter_type'] = $filterType;
            }

            if ($filterValue) {
                $payload['filter_value'] = $filterValue;
            }

            Log::info('VIP Reseller Prepaid API Request', [
                'url' => $this->apiUrl . '/prepaid',
                'payload' => $payload,
            ]);

            $response = Http::timeout(30)
                ->asForm()
                ->post($this->apiUrl . '/prepaid', $payload);

            Log::info('VIP Reseller Prepaid API Response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['result']) && $data['result'] === true) {
                    return [
                        'success' => true,
                        'data' => $data['data'] ?? [],
                        'message' => $data['message'] ?? 'Success',
                    ];
                }

                return [
                    'success' => false,
                    'data' => [],
                    'message' => $data['message'] ?? 'Failed to get prepaid services',
                ];
            }

            return [
                'success' => false,
                'data' => [],
                'message' => 'API request failed: ' . $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('VIP Reseller Prepaid API Error: ' . $e->getMessage());

            return [
                'success' => false,
                'data' => [],
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get prepaid services by brand
     */
    public function getPrepaidServicesByBrand($brandName)
    {
        return $this->getPrepaidServices('brand', $brandName);
    }

    /**
     * Get prepaid services by type
     */
    public function getPrepaidServicesByType($typeName)
    {
        return $this->getPrepaidServices('type', $typeName);
    }
}
