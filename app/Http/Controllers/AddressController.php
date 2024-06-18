<?php

namespace App\Http\Controllers;

use App\Services\ViaCepService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * @var ViaCepService
     */
    private $viaCepService;

    /**
     * AddressController constructor.
     *
     * @param ViaCepService $viaCepService
     */
    public function __construct(ViaCepService $viaCepService)
    {
        $this->viaCepService = $viaCepService;
    }

    /**
     * Busca endereços com base nos parâmetros fornecidos.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $uf = $request->input('uf', '');
        $cidade = $request->input('cidade', '');
        $trechoEndereco = $request->input('trecho_endereco', '');

        $enderecos = [];

        if (!empty($uf) && !empty($cidade) && !empty($trechoEndereco)) {
            $enderecos = $this->searchAddressByFragment($uf, $cidade, $trechoEndereco);
        }

        return response()->json($enderecos);
    }

    /**
     * Busca endereços por trecho usando o serviço ViaCEP.
     *
     * @param string $uf
     * @param string $cidade
     * @param string $trechoEndereco
     * @return array
     */
    private function searchAddressByFragment(string $uf, string $cidade, string $trechoEndereco): array
    {
        return $this->viaCepService->searchAddress($uf, $cidade, $trechoEndereco);
    }
}