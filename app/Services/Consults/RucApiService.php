<?php

namespace App\Services\Consults;

use Illuminate\Support\Facades\Http;

class RucApiService
{
    public function fetchData(string $identifier)
    {
        $url = "https://api.apis.net.pe/v1/ruc?numero=$identifier";

        $response = Http::timeout(10)->get($url);

        if ($response->successful()) {
            $data = $response->json();

            $data = $data['data'] ?? $data;

            return $this->mapResponse($data);
        }

        return null;
    }

    public function mapResponse(array $response): array
    {
        return [
            'razon_social' => $response['nombre'] ?? null,
            'estado' => $response['estado'] ?? null,
            'condicion' => $response['condicion'] ?? null,
            'direccion' => $response['direccion'] ?? null,
            'ubigeo' => $response['ubigeo'] ?? null,
            'viaTipo' => $response['viaTipo'] ?? null,
            'viaNombre' => $response['viaNombre'] ?? null,
            'zonaCodigo' => $response['zonaCodigo'] ?? null,
            'zonaTipo' => $response['zonaTipo'] ?? null,
            'numero' => $response['numero'] ?? null,
            'interior' => $response['interior'] ?? null,
            'lote' => $response['lote'] ?? null,
            'dpto' => $response['dpto'] ?? null,
            'manzana' => $response['manzana'] ?? null,
            'kilometro' => $response['kilometro'] ?? null,
            'distrito' => $response['distrito'] ?? null,
            'departamento' => $response['departamento'] ?? null,
            'provincia' => $response['provincia'] ?? null,
        ];
    }
}
