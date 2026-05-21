<?php

namespace App\Filament\Resources\SintomaResource\Pages;

use App\Filament\Resources\SintomaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSintomas extends ListRecords
{
    protected static string $resource = SintomaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
