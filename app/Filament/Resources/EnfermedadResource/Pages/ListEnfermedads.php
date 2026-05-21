<?php

namespace App\Filament\Resources\EnfermedadResource\Pages;

use App\Filament\Resources\EnfermedadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEnfermedads extends ListRecords
{
    protected static string $resource = EnfermedadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
