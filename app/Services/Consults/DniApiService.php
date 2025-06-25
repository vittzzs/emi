<?php

namespace App\Services\Consults;

use Illuminate\Support\Facades\Http;

class DniApiService
{
    private string $token;

    private string $endpoint;

    public function __construct()
    {
        $this->token = config('services.data.info.token');
        $this->endpoint = config('services.data.info.endpoint');
    }

    public function fetchData(string $identifier): ?array
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->token}",
            'Accept' => 'application/json',
        ])->get($this->endpoint.$identifier);

        return $response->successful() ? $this->mapResponse($response->json()) : null;
    }

    public function mapResponse(array $response): array
    {
        return [
            'dni' => $response['dni'] ?? null,
            'codigo_verificacion' => $response['codigo_verificacion'] ?? null,
            'nombres' => $response['nombres'] ?? null,
            'apellido_paterno' => $response['apellido_paterno'] ?? null,
            'apellido_materno' => $response['apellido_materno'] ?? null,
            'genero' => $response['genero'] ?? null,
            'fecha_nacimiento' => $response['fecha_nacimiento'] ?? null,
            'fecha_defuncion' => $response['fecha_defuncion'] ?? null,
            'departamento' => $response['departamento'] ?? null,
            'provincia' => $response['provincia'] ?? null,
            'distrito' => $response['distrito'] ?? null,
            'estado_civil' => $response['estado_civil'] ?? null,
            'nivel_educativo' => $response['nivel_educativo'] ?? null,
            'altura' => $response['altura'] ?? null,
            'fecha_inscripcion' => $response['fecha_inscripcion'] ?? null,
            'fecha_emision' => $response['fecha_emision'] ?? null,
            'fecha_expiracion' => $response['fecha_expiracion'] ?? null,
            'padre' => $response['padre'] ?? null,
            'madre' => $response['madre'] ?? null,
            'restricciones' => $response['restricciones'] ?? null,
            'direccion' => $response['direccion'] ?? null,
            'ubigeo' => $response['ubigeo'] ?? null,
            'ubigeo_inei' => $response['ubigeo_inei'] ?? null,
            'ubigeo_sunat' => $response['ubigeo_sunat'] ?? null,
            'codigo_postal' => $response['codigo_postal'] ?? null,
            'foto' => $response['foto'] ?? null,
        ];
    }
}
