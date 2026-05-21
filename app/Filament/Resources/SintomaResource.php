<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SintomaResource\Pages;
use App\Filament\Resources\SintomaResource\RelationManagers;
use App\Models\Sintoma;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class SintomaResource extends Resource
{
    protected static ?string $model = Sintoma::class;
    protected static ?string $label = "Síntoma";

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre'),
                Textarea::make('descripcion')->label('Descripción')->rows(5)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre'),
                TextColumn::make('descripcion')->label('Descripción')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSintomas::route('/'),
            'create' => Pages\CreateSintoma::route('/create'),
            'edit' => Pages\EditSintoma::route('/{record}/edit'),
        ];
    }
}
