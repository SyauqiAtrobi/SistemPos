<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PakasirService
{
    protected $baseUrl;
    protected $project;
    protected $apiKey;
    // Hapus properti isSandbox dari sini jika tidak dipakai lagi untuk pembuatan transaksi

    public function __construct()
    {
        $this->baseUrl = config('pakasir.base_url', 'https://app.pakasir.com');
        $this->project = config('pakasir.project');
        $this->apiKey = config('pakasir.api_key');
    }

    /**
     * Membuat Transaksi (Khusus Metode QRIS)
     */
    public function createQrisTransaction($orderId, $amount)
    {
        if (blank($this->project) || blank($this->apiKey)) {
            throw new \Exception('Konfigurasi Pakasir belum lengkap. Isi PAKASIR_PROJECT_SLUG dan PAKASIR_API_KEY pada .env.');
        }

        // Selalu gunakan endpoint transactioncreate untuk men-generate QRIS
        $endpoint = '/api/transactioncreate/qris';

        $response = Http::post($this->baseUrl . $endpoint, [
            'project'  => $this->project,
            'order_id' => $orderId,
            'amount'   => (int) $amount, // Pastikan format angka
            'api_key'  => $this->apiKey,
        ]);

        if ($response->failed()) {
            Log::error('Pakasir API Error:', $response->json());
            throw new \Exception('Gagal terhubung ke server pembayaran.');
        }

        return $response->json();
    }
}