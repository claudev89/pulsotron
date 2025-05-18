<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PacienteResource\Pages;
use App\Filament\Resources\PacienteResource\RelationManagers;
use App\Models\Comuna;
use App\Models\Enfermedad;
use App\Models\Paciente;
use App\Models\Region;
use App\Models\Sintoma;
use Carbon\Carbon;
use Faker\Provider\Text;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Radio;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;

class PacienteResource extends Resource
{
    protected static ?string $model = Paciente::class;

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
                    DatePicker::make('created')
                        ->columnSpan(2)
                        ->label('Fecha')
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
                    TextInput::make('rut')->required()->columnSpan(3),

                    Select::make('region_id')
                        ->label('Región')
                        ->options(Region::all()->sortBy('id')->pluck('nombre', 'id'))
                        ->live()
                        ->required()
                        ->searchable()
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

                Section::make('Historial médico')
                    ->description('Principal motivo de la consulta y tiempo de dolencia (enumerar en orden de prioridad)')
                    ->columns(6)
                    ->schema([
                        Section::make()
                        ->schema([
                            TextInput::make('motivo_consulta_1')
                                ->label('1.')
                                ->required()
                                ->reactive(),
                            TextInput::make('motivo_consulta_2')
                                ->label('2.')
                                ->disabled(fn(callable $get) => empty($get('motivo_consulta_1')))
                                ->reactive(),
                            TextInput::make('motivo_consulta_3')
                                ->label('3.')
                                ->disabled(fn(callable $get) => empty($get('motivo_consulta_2')))
                                ->reactive(),
                        ])->columnSpan(3),

                        Section::make()
                            ->schema([
                                TextInput::make('diagnostico_occidental')
                                    ->label('Diagnóstico occidental'),
                                TextInput::make('tratamiento')
                                    ->label('Tratamiento'),
                                TextInput::make('cirugía')
                                    ->label('¿Ha tenido alguna cirugía?')
                            ])->columnSpan(3),

                        Section::make('Has tenido alguno de los siguientes síntomas?')
                            ->schema([
                                Radio::make('depresion')
                                    ->label('Depresión')
                                    ->inline()
                                    ->options(['ocasional', 'frecuente']),
                                Radio::make('irritabilidad')
                                    ->inline()
                                    ->options(['ocasional', 'frecuente']),
                                Radio::make('falta_de_concentracion')
                                    ->label('Falta de concentración')
                                    ->inline()
                                    ->options(['ocasional', 'frecuente']),
                                Radio::make('estres')
                                    ->label('Estrés')
                                    ->inline()
                                    ->options(['ocasional', 'frecuente']),
                                Radio::make('ansiedad')
                                    ->inline()
                                    ->options(['ocasional', 'frecuente']),
                                Radio::make('insomnio')
                                    ->inline()
                                    ->options(['ocasional', 'frecuente']),
                                Radio::make('suenio_perturbado')
                                    ->label('Sueño perturbado')
                                    ->inline()
                                    ->options(['ocasional', 'frecuente']),
                                Radio::make('fatiga_cansancio')
                                    ->label('Fatiga/Cansancio')
                                    ->inline()
                                    ->options(['ocasional', 'frecuente']),
                                Radio::make('mareos_desmayos')
                                    ->label('Mareos/Desmayos')
                                    ->inline()
                                    ->options(['ocasional', 'frecuente']),
                                Radio::make('perdida_aumento_de_peso')
                                    ->label('Pérdida/Aumento de peso')
                                    ->inline()
                                    ->options(['ocasional', 'frecuente']),
                                Radio::make('depresion')
                                    ->label('Depresión')
                                    ->inline()
                                    ->options(['ocasional', 'frecuente']),
                                Radio::make('hemorragias')
                                    ->inline()
                                    ->options(['ocasional', 'frecuente']),
                                Radio::make('sudor_espontaneo')
                                    ->label('Sudor espontáneo')
                                    ->inline()
                                    ->options(['ocasional', 'frecuente'])
                            ])
                            ->columnSpan(2),

                        Section::make('¿Has tenido o tienes alguna de las siguientes enfermedades?')
                            ->schema([
                                Checkbox::make('ave')->label('AVE'),
                                Checkbox::make('alergias'),
                                Checkbox::make('anemia'),
                                Checkbox::make('artritis'),
                                Checkbox::make('asma'),
                                Checkbox::make('cancer'),
                                Checkbox::make('enfermedades_coronarias')->label('Enfermedades coronarias'),
                                Checkbox::make('tiroides')->label('Enfermedades a la tiroides'),
                                Checkbox::make('hepatitis'),
                                Checkbox::make('hipertension')->label('Hipertensión'),
                                Checkbox::make('vih')->label('VIH'),
                                Checkbox::make('diabetes'),
                            ])
                            ->columnSpan(2),

                        Section::make('Alguno de tus familiares ha tenido o tiene alguna de estas enfermedades?')
                            ->schema([
                                Checkbox::make('ave')->label('AVE'),
                                Checkbox::make('artritis'),
                                Checkbox::make('asma'),
                                Checkbox::make('cancer'),
                                Checkbox::make('enfermedades_coronarias')->label('Enfermedades coronarias'),
                                Checkbox::make('tiroides')->label('Enfermedades a la tiroides'),
                                Checkbox::make('hipertension')->label('Hipertensión'),
                                Checkbox::make('diabetes'),
                            ])
                            ->columnSpan(2),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
