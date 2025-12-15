<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IrisService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('iris.api_key');
        $this->baseUrl = config('iris.is_production') 
            ? 'https://app.midtrans.com/iris/api/v1'
            : 'https://app.sandbox.midtrans.com/iris/api/v1';
    }

    /**
     * Check if API key is configured
     */
    protected function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Get Iris balance
     */
    public function getBalance()
    {
        if (!$this->isConfigured()) {
            return ['balance' => 0, 'error' => 'Iris API key not configured'];
        }

        try {
            $response = Http::withBasicAuth($this->apiKey, '')
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/balance");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Iris Balance Error', ['response' => $response->body()]);
            return ['balance' => 0];
        } catch (\Exception $e) {
            Log::error('Iris Balance Exception', ['error' => $e->getMessage()]);
            return ['balance' => 0];
        }
    }

    /**
     * Create payout to bank account
     */
    public function createPayout(array $data)
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'error' => 'Iris API key belum dikonfigurasi. Silakan set IRIS_API_KEY di .env',
            ];
        }

        try {
            $payload = [
                'payouts' => [
                    [
                        'beneficiary_name' => $data['account_name'],
                        'beneficiary_account' => $data['account_number'],
                        'beneficiary_bank' => $data['bank_code'],
                        'beneficiary_email' => $data['email'] ?? null,
                        'amount' => (int) $data['amount'],
                        'notes' => $data['notes'] ?? 'Withdrawal from Ulu Coffee',
                    ]
                ]
            ];

            $response = Http::withBasicAuth($this->apiKey, '')
                ->withOptions(['verify' => false])
                ->post("{$this->baseUrl}/payouts", $payload);

            if ($response->successful()) {
                $result = $response->json();
                return [
                    'success' => true,
                    'data' => $result,
                    'reference' => $result['payouts'][0]['reference_no'] ?? null,
                ];
            }

            Log::error('Iris Payout Error', ['response' => $response->body()]);
            return [
                'success' => false,
                'error' => $response->json()['error_message'] ?? 'Payout failed',
            ];
        } catch (\Exception $e) {
            Log::error('Iris Payout Exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Approve payout (if required)
     */
    public function approvePayout(array $referenceNumbers)
    {
        try {
            $response = Http::withBasicAuth($this->apiKey, '')
                ->withOptions(['verify' => false])
                ->post("{$this->baseUrl}/payouts/approve", [
                    'reference_nos' => $referenceNumbers,
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Iris Approve Exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Get list of supported banks
     */
    public function getBanks()
    {
        // Common Indonesian banks
        return [
            ['code' => 'bca', 'name' => 'Bank Central Asia (BCA)'],
            ['code' => 'bni', 'name' => 'Bank Negara Indonesia (BNI)'],
            ['code' => 'bri', 'name' => 'Bank Rakyat Indonesia (BRI)'],
            ['code' => 'mandiri', 'name' => 'Bank Mandiri'],
            ['code' => 'permata', 'name' => 'Bank Permata'],
            ['code' => 'cimb', 'name' => 'CIMB Niaga'],
            ['code' => 'danamon', 'name' => 'Bank Danamon'],
            ['code' => 'bsi', 'name' => 'Bank Syariah Indonesia (BSI)'],
            ['code' => 'mega', 'name' => 'Bank Mega'],
            ['code' => 'btpn', 'name' => 'Bank BTPN'],
        ];
    }
}
