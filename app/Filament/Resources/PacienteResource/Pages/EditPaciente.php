<?php

namespace App\Filament\Resources\PacienteResource\Pages;

use App\Filament\Resources\PacienteResource;
use App\Services\PacienteFormSynchronizer;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaciente extends EditRecord
{
    protected static string $resource = PacienteResource::class;

    /** @var array<string, mixed> */
    protected array $patientFormExtras = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        [$data, $this->patientFormExtras] = PacienteFormSynchronizer::splitForPacienteTable($data);

        return $data;
    }

    protected function afterSave(): void
    {
        PacienteFormSynchronizer::persistExtras($this->record, $this->patientFormExtras);
    }
}
