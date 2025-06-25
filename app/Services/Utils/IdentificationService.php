<?php

namespace App\Services\Utils;

use App\Actions\External\GetInfo;
use Filament\Notifications\Notification;

class IdentificationService
{
    public function getIdentificationData(string $identification): mixed
    {
        return match (strlen($identification)) {
            8 => GetInfo::handle($identification)?->getData(),
            11 => GetInfo::handleRuc($identification)?->getData(),
            default => null,
        };
    }

    public function setFullName(string $identification, callable $set): void
    {
        if (empty($identification)) {
            return;
        }
        $data = $this->getIdentificationData($identification);

        if ($data) {
            if (isset($data->nombres, $data->apellido_paterno, $data->apellido_materno)) {
                $set('full_name', "{$data->nombres} {$data->apellido_paterno} {$data->apellido_materno}");
            } elseif (isset($data->razon_social)) {
                $set('full_name', $data->razon_social);
            }
        } else {
            Notification::make()
                ->title('Sin información')
                ->body('No se pudo encontrar información para el número de documento proporcionado.')
                ->danger()
                ->send();
        }
    }

    public function setFullNameAndAddress(string $identification, callable $set, callable $get): void
    {
        if (empty($identification)) {
            return;
        }

        $data = $this->getIdentificationData($identification);

        if ($data) {
            if (isset($data->nombres, $data->apellido_paterno, $data->apellido_materno)) {
                $set('full_name', "{$data->nombres} {$data->apellido_paterno} {$data->apellido_materno}");
                $set('../../addresses', [
                    [
                        'address' => $data->direccion ?? '',
                        'ubigeo_cod' => $data->ubigeo ?? '080101',
                    ],
                ]);
            } elseif (isset($data->razon_social)) {
                $set('full_name', $data->razon_social);
                $set('../../addresses', [
                    [
                        'address' => $data->direccion ?? '',
                        'ubigeo_cod' => $data->ubigeo ?? '',
                    ],
                ]);
            }
        } else {
            Notification::make()
                ->title('Sin información')
                ->body('No se pudo encontrar información para el número de documento proporcionado.')
                ->danger()
                ->send();
        }
    }
}
