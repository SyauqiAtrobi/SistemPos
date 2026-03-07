<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PakasirService
{
    protected $baseUrl;
    protected $project;
    protected $apiKey;
    protected $isSandbox;

    public function __construct()
    {
        $this->baseUrl = config('pakasir.base_url', 'https://app.pakasir.com');
        $this->project = config('pakasir.project');
        $this->apiKey = config('pakasir.api_key');
        $this->isSandbox = config('pakasir.is_sandbox', true);
    }

    /**
     * Membuat Transaksi (Khusus Metode QRIS)
     */
    public function createQrisTransaction($orderId, $amount)
    {
        // Gunakan endpoint simulasi jika mode sandbox (testing) aktif
        $endpoint = $this->isSandbox 
            ? '/api/paymentsimulation' 
            : '/api/transactioncreate/qris';

        $response = Http::post($this->baseUrl . $endpoint, [
            'project'  => $this->project,
            'order_id' => $orderId,
            'amount'   => (int) $amount, // Pastikan format angka tanpa titik/koma
            'api_key'  => $this->apiKey,
        ]);

        if ($response->failed()) {
            Log::error('Pakasir API Error:', $response->json());
            throw new \Exception('Gagal terhubung ke server pembayaran.');
        }

        return $response->json();
    }
}