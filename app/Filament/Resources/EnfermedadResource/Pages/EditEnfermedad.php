<?php

namespace App\Filament\Resources\EnfermedadResource\Pages;

use App\Filament\Resources\EnfermedadResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnfermedad extends EditRecord
{
    protected static string $resource = EnfermedadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
