<?php

namespace App\Filament\Resources\PacienteResource\Pages;

use App\Filament\Resources\PacienteResource;
use App\Services\PacienteFormSynchronizer;
use Filament\Resources\Pages\CreateRecord;

class CreatePaciente extends CreateRecord
{
    protected static string $resource = PacienteResource::class;

    /** @var array<string, mixed> */
    protected array $patientFormExtras = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        [$data, $this->patientFormExtras] = PacienteFormSynchronizer::splitForPacienteTable($data);

        return $data;
    }

    protected function afterCreate(): void
    {
        PacienteFormSynchronizer::persistExtras($this->record, $this->patientFormExtras);
    }
}
