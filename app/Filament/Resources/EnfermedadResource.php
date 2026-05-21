<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnfermedadResource\Pages;
use App\Filament\Resources\EnfermedadResource\RelationManagers;
use App\Models\Enfermedad;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class EnfermedadResource extends Resource
{
    protected static ?string $model = Enfermedad::class;

    protected static ?int $navigationSort = 80;

    protected static ?string $label = "Enfermedades";

    protected static ?string $navigationIcon = 'heroicon-o-face-frown';

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
                Tables\Actions\DeleteAction::make()
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
            RelationManagers\PacienteRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnfermedads::route('/'),
            'create' => Pages\CreateEnfermedad::route('/create'),
            'edit' => Pages\EditEnfermedad::route('/{record}/edit'),
        ];
    }
    public static function getModelLabel(): string
    {
        return 'enfermedad';
    }

    public static function getPluralLabel(): ?string
    {
        return 'enfermedades';
    }


}
