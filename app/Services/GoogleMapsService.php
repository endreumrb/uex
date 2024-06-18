<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleMapsService
{
    /**
     * URL base da API do Google Maps.
     *
     * @var string
     */
    private $baseUrl = 'https://maps.googleapis.com/maps/api';

    /**
     * Chave de API do Google Maps.
     *
     * @var string|null
     */
    protected $apiKey;

    /**
     * GoogleMapsService constructor.
     */
    public function __construct()
    {
        $this->apiKey = env('GOOGLE_MAPS_API_KEY');
    }

    /**
     * Obtém a latitude e longitude com base no endereço fornecido.
     *
     * @param string $endereco
     * @return array
     */
    public function getLatLong(string $endereco): array
    {
        try {
            $response = Http::withOptions(['verify' => false])->get("{$this->baseUrl}/geocode/json", [
                'address' => $endereco,
                'key' => $this->apiKey,
            ]);

            if ($response->successful() && $response->json('status') === 'OK') {
                $results = $response->json('results');
                $location = $results[0]['geometry']['location'];

                return [$location['lat'], $location['lng']];
            }
        } catch (\Exception $e) {
            // Tratar exceções de requisição HTTP, se necessário
            Log::error('Erro na requisição do Google Maps: ' . $e->getMessage());
        }

        return [null, null];
    }
}