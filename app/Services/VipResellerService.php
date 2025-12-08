<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\VipResellerSetting;

class VipResellerService
{
    protected $apiUrl;
    protected $apiId;
    protected $apiKey;
    protected $sign;
    protected bool $configured = false;

    public function __construct()
    {
        $this->loadConfiguration();
    }

    protected function loadConfiguration(): void
    {
        $setting = VipResellerSetting::current();

        $this->apiUrl = optional($setting)->api_url ?? config('services.vip_reseller.api_url');
        $this->apiId = optional($setting)->api_id ?? config('services.vip_reseller.api_id');
        $this->apiKey = optional($setting)->api_key ?? config('services.vip_reseller.api_key');
        $this->sign = optional($setting)->sign ?? config('services.vip_reseller.sign');

        $this->configured = !empty($this->apiUrl) && !empty($this->apiKey) && !empty($this->sign);
    }

    protected function ensureConfigured(): bool
    {
        if ($this->configured) {
            return true;
        }

        Log::warning('VIP Reseller configuration is incomplete.');
        return false;
    }

    /**
     * Get game services from VIP Reseller API
     */
    public function getGameServices($filterType = null, $filterValue = null, $filterStatus = null)
    {
        if (!$this->ensureConfigured()) {
            return [
                'success' => false,
                'data' => [],
                'message' => 'Konfigurasi VIP Reseller belum lengkap. Mohon lengkapi credensial API.',
            ];
        }

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
        if (!$this->ensureConfigured()) {
            return [
                'success' => false,
                'data' => [],
                'message' => 'Konfigurasi VIP Reseller belum lengkap. Mohon lengkapi credensial API.',
            ];
        }

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
        if (!$this->ensureConfigured()) {
            return [
                'success' => false,
                'data' => [],
                'message' => 'Konfigurasi VIP Reseller belum lengkap. Mohon lengkapi credensial API.',
            ];
        }

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

    /**
     * Fetch authenticated reseller profile
     */
    public function getProfile()
    {
        if (!$this->ensureConfigured()) {
            return [
                'success' => false,
                'data' => [],
                'message' => 'Konfigurasi VIP Reseller belum lengkap. Mohon lengkapi credensial API.',
            ];
        }

        try {
            $payload = [
                'key' => $this->apiKey,
                'sign' => $this->sign,
            ];

            Log::info('VIP Reseller Profile API Request', [
                'url' => $this->apiUrl . '/profile',
                'payload' => $payload,
            ]);

            $response = Http::timeout(15)
                ->asForm()
                ->post($this->apiUrl . '/profile', $payload);

            Log::info('VIP Reseller Profile API Response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['result']) && $data['result'] === true) {
                    return [
                        'success' => true,
                        'data' => $data['data'] ?? [],
                        'message' => $data['message'] ?? 'Profil berhasil diambil.',
                    ];
                }

                return [
                    'success' => false,
                    'data' => [],
                    'message' => $data['message'] ?? 'Gagal mengambil profil.',
                ];
            }

            return [
                'success' => false,
                'data' => [],
                'message' => 'API request failed: ' . $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('VIP Reseller Profile API Error: ' . $e->getMessage());

            return [
                'success' => false,
                'data' => [],
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Order Game Service (Top Up Game)
     * 
     * @param string $serviceCode Kode layanan dari VIP Reseller
     * @param string $dataNo User ID game (data_no)
     * @param string|null $dataZone Zone ID (jika diperlukan)
     * @return array
     */
    public function orderGame(string $serviceCode, string $dataNo, ?string $dataZone = null): array
    {
        if (!$this->ensureConfigured()) {
            return [
                'success' => false,
                'data' => [],
                'message' => 'Konfigurasi VIP Reseller belum lengkap. Mohon lengkapi kredensial API.',
            ];
        }

        try {
            $payload = [
                'key' => $this->apiKey,
                'sign' => $this->sign,
                'type' => 'order',
                'service' => $serviceCode,
                'data_no' => $dataNo,
            ];

            // Add zone if provided
            if (!empty($dataZone)) {
                $payload['data_zone'] = $dataZone;
            }

            Log::info('VIP Reseller Order Game Request', [
                'url' => $this->apiUrl . '/game-feature',
                'payload' => array_merge($payload, ['key' => '***', 'sign' => '***']), // Hide sensitive data
            ]);

            $response = Http::timeout(60)
                ->connectTimeout(10)
                ->asForm()
                ->post($this->apiUrl . '/game-feature', $payload);

            Log::info('VIP Reseller Order Game Response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['result']) && $data['result'] === true) {
                    return [
                        'success' => true,
                        'data' => $data['data'] ?? [],
                        'message' => $data['message'] ?? 'Pesanan berhasil diproses.',
                    ];
                }

                return [
                    'success' => false,
                    'data' => $data['data'] ?? [],
                    'message' => $data['message'] ?? 'Gagal memproses pesanan.',
                ];
            }

            return [
                'success' => false,
                'data' => [],
                'message' => 'API request failed: ' . $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('VIP Reseller Order Game Error: ' . $e->getMessage(), [
                'service' => $serviceCode,
                'data_no' => $dataNo,
                'data_zone' => $dataZone,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'data' => [],
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Order Prepaid Service (Pulsa, Data, etc)
     * 
     * @param string $serviceCode Kode layanan dari VIP Reseller
     * @param string $phoneNumber Nomor HP tujuan
     * @return array
     */
    public function orderPrepaid(string $serviceCode, string $phoneNumber): array
    {
        if (!$this->ensureConfigured()) {
            return [
                'success' => false,
                'data' => [],
                'message' => 'Konfigurasi VIP Reseller belum lengkap. Mohon lengkapi kredensial API.',
            ];
        }

        try {
            $payload = [
                'key' => $this->apiKey,
                'sign' => $this->sign,
                'type' => 'order',
                'service' => $serviceCode,
                'data_no' => $phoneNumber,
            ];

            Log::info('VIP Reseller Order Prepaid Request', [
                'url' => $this->apiUrl . '/prepaid',
                'payload' => array_merge($payload, ['key' => '***', 'sign' => '***']),
            ]);

            $response = Http::timeout(60)
                ->connectTimeout(10)
                ->asForm()
                ->post($this->apiUrl . '/prepaid', $payload);

            Log::info('VIP Reseller Order Prepaid Response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['result']) && $data['result'] === true) {
                    return [
                        'success' => true,
                        'data' => $data['data'] ?? [],
                        'message' => $data['message'] ?? 'Pesanan berhasil diproses.',
                    ];
                }

                return [
                    'success' => false,
                    'data' => $data['data'] ?? [],
                    'message' => $data['message'] ?? 'Gagal memproses pesanan.',
                ];
            }

            return [
                'success' => false,
                'data' => [],
                'message' => 'API request failed: ' . $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('VIP Reseller Order Prepaid Error: ' . $e->getMessage(), [
                'service' => $serviceCode,
                'phone' => $phoneNumber,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'data' => [],
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }
}
