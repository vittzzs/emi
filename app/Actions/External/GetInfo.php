<?php

namespace App\Actions\External;

use App\Services\Consults\DniApiService;
use App\Services\Consults\RucApiService;

class GetInfo
{
    public static function handle(string $dni)
    {
        if (! preg_match('/^\d{8}$/', $dni)) {
            return response()->json(['error' => 'Invalid DNI'], 422);
        }

        $data = app(DniApiService::class)->fetchData($dni);

        return response()->json($data);
    }

    public static function handleRuc(string $ruc)
    {
        if (! preg_match('/^\d{11}$/', $ruc)) {
            return response()->json(['error' => 'Invalid RUC'], 422);
        }

        $data = app(RucApiService::class)->fetchData($ruc);

        return response()->json($data);
    }
}
