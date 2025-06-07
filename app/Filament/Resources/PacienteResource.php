<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PacienteResource\Pages;
use App\Filament\Resources\PacienteResource\RelationManagers;
use App\Models\Comuna;
use App\Models\Enfermedad;
use App\Models\Paciente;
use App\Models\Region;
use Carbon\Carbon;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;

class PacienteResource extends Resource
{
    protected static ?string $model = Paciente::class;
    protected static ?string $recordRouteKeyName = 'rut';

    protected static ?string $navigationIcon = 'heroicon-o-users';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información básica y de contacto')
                ->schema([
                    TextInput::make('nombre')
                        ->columnSpan(3)
                        ->autofocus()
                        ->required(),
                    DatePicker::make('created_at')
                        ->columnSpan(2)
                        ->label('Fecha de ingreso')
                        ->default(now())
                        ->required(),
                    TextInput::make('ocupacion')
                        ->label('Ocupación')
                        ->columnSpan(2),

                    DatePicker::make('fecha_nacimiento')
                        ->label('Fecha de nacimiento')
                        ->reactive()
                        ->debounce(300)
                        ->required()
                        ->afterStateUpdated(fn ($state, callable $set) =>
                            $set('edad', $state ? Carbon::parse($state)->age . ' años' : null)
                        )
                        ->columnSpan(3),
                    TextInput::make('edad')->disabled(),
                    TextInput::make('rut')
                        ->mask(RawJs::make(<<<'JS'
        $input.replace(/\D/g, '').length <= 7
            ? '9.999.999-**'
            : '99.999.999-**'
    JS))
                        ->stripCharacters(['.'])
                        ->placeholder('12.345.678-9')
                        ->required()->columnSpan(3),

                    Select::make('region_id')
                        ->label('Región')
                        ->options(Region::all()->sortBy('id')->pluck('nombre', 'id'))
                        ->live()
                        ->required()
                        ->searchable()
                        ->dehydrated(false)
                        ->columnSpan(2),
                    Select::make('comuna_id')
                        ->label('Comuna')
                        ->options(fn(callable $get) => Comuna::where('region_id', $get('region_id'))->orderBy('nombre')->pluck('nombre', 'id')
                        )
                        ->live()
                        ->required()
                        ->searchable()
                        ->disabled(fn(callable $get) => empty($get('region_id')))
                        ->columnSpan(2),
                    TextInput::make('direccion_calle')
                        ->label('Calle')
                        ->required()
                        ->disabled(fn(callable $get) => empty($get('comuna_id')))
                        ->columnSpan(2),
                    TextInput::make('direccion_numero')
                        ->label('Número')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(999999)
                        ->disabled(fn(callable $get) => empty($get('comuna_id'))),

                    TextInput::make('telefono')
                        ->label('Teléfono fijo')
                        ->numeric()
                        ->maxValue(999999999)
                        ->columnSpan(2),
                    TextInput::make('celular')
                        ->label('Celular (+569)')
                        ->numeric()
                        ->maxValue(99999999)
                        ->columnSpan(2),
                    TextInput::make('correo')
                        ->label('Correo electrónico')
                        ->email()
                        ->columnSpan(3),

                    TextInput::make('contacto_nombre')
                        ->label('Contacto de emergencia')
                        ->columnSpan(3),
                    TextInput::make('contacto_telefono')
                        ->label('Teléfono fijo')
                        ->numeric()
                        ->maxValue(999999999)
                        ->columnSpan(2),
                    TextInput::make('contacto_celular')
                        ->label('Celular (+569)')
                        ->numeric()
                        ->maxValue(99999999)
                        ->columnSpan(2),
                ])->columns(7),

                Section::make('Estilo de vida')
                    ->columns(6)
                    ->schema([
                        Section::make()
                        ->schema([
                            Radio::make('deportes')
                                ->dehydrated(false)
                                ->boolean()
                                ->inline()
                                ->label('¿Haces deporte?')
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $set('deporte', null);
                                })
                            ->reactive(),
                            TextInput::make('deporte')
                            ->label('veces por semana')
                            ->numeric()
                            ->maxValue(7)
                            ->minValue(0)
                                ->reactive()
                            ->hidden(fn ($get) => $get('deportes') !== '1'),

                             Radio::make('alcohols')
                                 ->dehydrated(false)
                                 ->boolean()
                                 ->inline()
                                 ->label('¿Tomas alcohol?')
                                 ->afterStateUpdated(function ($state, callable $set) {
                                     $set('alcohol', null);
                                 })
                                 ->reactive(),
                            TextInput::make('alcohol')
                                ->label('veces por semana')
                                ->numeric()
                                ->maxValue(7)
                                ->minValue(0)
                                ->reactive()
                                ->hidden(fn ($get) => $get('alcohols') !== '1')
                        ])->columnSpan(2),

                        Section::make()
                            ->schema([
                                Radio::make('fumas')
                                    ->dehydrated(false)
                                    ->boolean()
                                    ->inline()
                                    ->label('¿Fumas?')
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('fuma', null);
                                    })
                                    ->reactive(),
                                TextInput::make('fuma')
                                    ->label('cantidad por día')
                                    ->numeric()
                                    ->maxValue(80)
                                    ->minValue(0)
                                    ->reactive()
                                    ->hidden(fn ($get) => $get('fumas') !== '1'),

                                TextInput::make('cafe')
                                    ->label('N° tazas de té/café al día')
                                    ->numeric()
                                    ->maxValue(20)
                                    ->minValue(0),

                                TextInput::make('agua')
                                    ->label('N° litros de agua al día')
                                    ->numeric()
                                    ->maxValue(10)
                                    ->minValue(0)
                            ])->columnSpan(2),

                        Section::make()
                            ->schema([
                                Textarea::make('medicamentos')
                                    ->label('¿Qué medicamentos has tomado en los últimos dos meses?
                                                    (vitaminas, drogas, anticonceptivos, hierbas, etc.)')
                                ->autosize()
                            ])
                            ->columnSpan(2)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rut')->searchable(),
                TextColumn::make('nombre')->sortable()->searchable(),
                TextColumn::make('diagnostico')->label('Diagnóstico occidental'),
                TextColumn::make('fecha_nacimiento')->label('Edad')
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->diff(now())->y . ' años'),
                TextColumn::make('comuna.nombre'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPacientes::route('/'),
            'create' => Pages\CreatePaciente::route('/create'),
            'edit' => Pages\EditPaciente::route('/{record}/edit'),
        ];
    }
}
