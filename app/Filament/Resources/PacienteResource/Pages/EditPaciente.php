<?php

namespace App\Filament\Resources\PacienteResource\Pages;

use App\Filament\Resources\PacienteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaciente extends EditRecord
{
    protected static string $resource = PacienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! $data['deportes']) {
            $data['deporte'] = 0;
        }

        if (! $data['alcohols']) {
            $data['alcohol'] = 0;
        }

        if (! $data['fumas']) {
            $data['fumar'] = 0;
        }

        unset($data['deportes'], $data['alcohols'], $data['fumas']);




        return $data;
    }
}
