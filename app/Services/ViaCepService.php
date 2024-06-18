<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ViaCepService
{
    /**
     * URL base da API do ViaCEP.
     *
     * @var string
     */
    private $baseUrl = 'https://viacep.com.br/ws';

    /**
     * Busca endereços usando a API do ViaCEP.
     *
     * @param string $uf
     * @param string $cidade
     * @param string $endereco
     * @return array|null
     */
    public function searchAddress(string $uf, string $cidade, string $endereco): ?array
    {
        $url = "{$this->baseUrl}/{$uf}/{$cidade}/{$endereco}/json/";

        try {
            $response = Http::withOptions(['verify' => false])->get($url);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            // Tratar exceções de requisição HTTP, se necessário
            Log::error('Erro na requisição ViaCEP: ' . $e->getMessage());
        }

        return null;
    }
}