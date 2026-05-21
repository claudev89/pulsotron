<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PacienteResource\Pages;
use App\Filament\Resources\PacienteResource\RelationManagers;
use App\Models\Comuna;
use App\Models\Enfermedad;
use App\Models\Lengua;
use App\Models\Medicamento;
use App\Models\Paciente;
use App\Models\Region;
use App\Models\Sintoma;
use App\Rules\ValidChileanRut;
use Carbon\Carbon;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuelLuna\CanvasPointer\Forms\Components\CanvasPointerField;

class PacienteResource extends Resource
{
    protected static ?string $model = Paciente::class;
    protected static ?string $recordRouteKeyName = 'rut';

    protected static ?string $navigationIcon = 'heroicon-o-users';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Información básica y de contacto')
                        ->icon('heroicon-m-user')
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
                                ->afterStateHydrated(fn ($state, callable $set) =>
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
                                ->validationAttribute('RUT')
                                ->rules([new ValidChileanRut])
                                ->required()->columnSpan(3),

                            Select::make('region_id')
                                ->label('Región')
                                ->options(Region::orderBy('id')->pluck('nombre', 'id')->toArray())
                                ->live()
                                ->required()
                                ->searchable()
                                ->dehydrated(false)
                                ->columnSpan(2)
                                ->afterStateHydrated(function (Get $get, Set $set) {
                                    if (! $get('region_id') && $get('comuna_id')) {
                                        $comuna = Comuna::find($get('comuna_id'));
                                        if ($comuna) {
                                            $set('region_id', $comuna->region_id);
                                        }
                                    }
                                })
                                ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                    $comunaId = $get('comuna_id');
                                    if (! $comunaId) {
                                        return;
                                    }
                                    $comuna = Comuna::find($comunaId);
                                    if (! $comuna || $comuna->region_id != $state) {
                                        $set('comuna_id', null);
                                    }
                                })
                            ,

                            Select::make('comuna_id')
                                ->label('Comuna')
                                ->options(function (Get $get) {
                                    $regionId = $get('region_id');

                                    if (!$regionId && $get('comuna_id')) {
                                        $regionId = optional(Comuna::find($get('comuna_id')))->region_id;
                                    }

                                    if (! $regionId) {
                                        return [];
                                    }

                                    return Comuna::where('region_id', $regionId)
                                        ->orderBy('nombre')
                                        ->pluck('nombre', 'id')
                                        ->toArray();
                                })
                                ->live()
                                ->required()
                                ->searchable()
                                ->disabled(fn (Get $get) => ! $get('region_id') && ! $get('comuna_id'))
                                ->columnSpan(2),
                            TextInput::make('direccion_calle')
                                ->label('Calle')
                                ->required()
                                ->disabled(fn(callable $get) => empty($get('comuna_id')))
                                ->columnSpan(2)
                                ->afterStateHydrated(function (Get $get, Set $set) {
                                    if (! $get('region_id') && $get('comuna_id')) {
                                        $comuna = Comuna::find($get('comuna_id'));
                                        if ($comuna) {
                                            $set('region_id', $comuna->region_id);
                                        }
                                    }
                                })
                            ,
                            TextInput::make('direccion_numero')
                                ->label('Número')
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(999999)
                                ->columnSpan(1)
                                ->disabled(fn(callable $get) => empty($get('comuna_id'))),

                            TextInput::make('direccion_complemento')
                                ->label('Casa, depto u otro complemento')
                                ->placeholder('Ej: Depto. 402 Torre B, Casa 5, Villa Los Aromos…')
                                ->maxLength(255)
                                ->columnSpan(3)
                                ->disabled(fn (callable $get) => empty($get('comuna_id')))
                                ->helperText('Opcional: departamento, casa, condominio, referencia, etc.'),

                            TextInput::make('telefono')
                                ->label('Teléfono fijo')
                                ->numeric()
                                ->maxValue(999999999)
                                ->columnSpan(2),
                            TextInput::make('celular')
                                ->label('Celular (+569)')
                                ->required()
                                ->numeric()
                                ->maxValue(99999999)
                                ->columnSpan(2),
                            TextInput::make('correo')
                                ->label('Correo electrónico')
                                ->email()
                                ->columnSpan(7),

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
                        ])
                        ->columns(7),
                    Wizard\Step::make('Estilo de vida')
                        ->icon('heroicon-m-heart')
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('deporte')
                                    ->label('¿Haces deporte?')
                                    ->hint('Veces por semana:')
                                    ->numeric()
                                    ->maxValue(7)
                                    ->minValue(0)
                                    ->default(0),

                                TextInput::make('alcohol')
                                    ->label('¿Tomas alcohol?')
                                    ->hint('Veces por semana:')
                                    ->numeric()
                                    ->maxValue(7)
                                    ->minValue(0)
                                    ->default(0)
                                    ->reactive()

                            ])->columnSpan(2),

                        Section::make()
                            ->schema([
                                TextInput::make('fumar')
                                    ->label('¿Fumas?')
                                    ->hint('Cantidad por día:')
                                    ->numeric()
                                    ->maxValue(80)
                                    ->minValue(0)
                                    ->default(0),

                                TextInput::make('cafe')
                                    ->label('N° tazas de té/café al día')
                                    ->numeric()
                                    ->maxValue(20)
                                    ->minValue(0)
                                    ->default(0),

                                TextInput::make('agua')
                                    ->label('N° litros de agua al día')
                                    ->numeric()
                                    ->maxValue(10)
                                    ->minValue(0)
                                    ->default(0)
                            ])->columnSpan(2),
                        Section::make()
                            ->schema([
                                Select::make('medicamentos')
                                    ->label('¿Qué medicamentos has tomado en los últimos dos meses?
                                                    (vitaminas, drogas, anticonceptivos, hierbas, etc.)')
                                    ->multiple()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('nombre')->required()->label('Nombre medicamento')
                                    ])
                                ->relationship('medicamentos', 'nombre')
                            ])->columnSpan(2),
                    ])->columns(6),

                    Wizard\Step::make('Historial médico')
                        ->icon('heroicon-m-document-text')
                        ->schema([
                            Section::make([
                                TextInput::make('motivo_consulta_1')
                                ->label('Motivo consulta 1')->maxLength(255),
                                TextInput::make('motivo_consulta_2')
                                ->label('Motivo consulta 2')->maxLength(255),
                                TextInput::make('motivo_consulta_3')
                                ->label('Motivo consulta 3')->maxLength(255),
                            ])->columnSpan(4),

                            Section::make([
                                TextInput::make('diagnostico_occidental')->label('Diagnóstico occidental'),
                                TextInput::make('tratamiento'),
                                TextInput::make('cirugia')->label('¿Has tenido alguna cirugía?')
                            ])->columnSpan(4),

                            Section::make()
                                ->schema(function ($get, $set, $record) {
                                $sintomas = Sintoma::all();
                                $fields = [];

                                foreach ($sintomas as $sintoma)
                                {
                                    $fields[] = Radio::make('sintoma_' . $sintoma->id)
                                        ->label($sintoma->nombre)
                                        ->options(['ocasional', 'frecuente'])
                                        ->inline()
                                        ->nullable()
                                        ->default(
                                            optional($record?->sintomasPrevios?->firstWhere('id', $sintoma->id))->pivot?->frecuencia
                                        );
                                }
                                return $fields;

                            })->columnSpan(4)->description('¿Has tenido alguno de los siguientes síntomas?'),

                            Section::make()
                            ->schema(function ($set, $get, $record) {
                                $enfermedades = Enfermedad::all();
                                $fields = [];

                                foreach ($enfermedades as $enfermedad)
                                {
                                    $fields[] = Checkbox::make('enfermedad_' . $enfermedad->id)
                                        ->label($enfermedad->nombre);
                                }
                                return $fields;
                            }
                            )->columnSpan(2)->description('¿Has tenido o tienes alguna de las siguientes enfermedades?'),

                            Section::make()
                            ->schema(function ($set, $get, $record) {
                                $enfermedades = Enfermedad::all();
                                $fields = [];

                                foreach ($enfermedades as $enfermedad)
                                {
                                    $fields[] = Checkbox::make('enfermedad_familiar_' . $enfermedad->id)
                                        ->label($enfermedad->nombre);
                                }
                                return $fields;
                            })->columnSpan(2)->description('¿Alguno de tus familiares ha tenido o tiene alguna de las siguientes enfermedades?'),

                            Repeater::make('Otros')
                            ->schema([
                                TextInput::make('Otro')
                            ])->columnSpanFull(),

                            Section::make('Sistema Reproductor Femenino')
                            ->schema(function ($set, $get, $record) {
                                $srfs = DB::table('srf')->get();
                                $srfs2 = DB::table('srf2')->get();
                                $fields1 = [];
                                $fields2 = [];

                                foreach ($srfs as $srf)
                                {
                                    $fields1[] = Radio::make('srf_' . $srf->id)
                                        ->label($srf->nombre)
                                        ->options(['ocasional' => 'Ocasional', 'frecuente' => 'Frecuente'])
                                        ->inline();
                                }

                                foreach ($srfs2 as $srf2)
                                {
                                    $fields2[] = TextInput::make('srf2_' . $srf2->id)
                                        ->label($srf2->nombre)
                                        ->numeric();
                                }

                                return array_merge($fields1, $fields2);

                            })->columnSpan(4),

                            Section::make('Zonas de Dolor')
                            ->schema([
                                CanvasPointerField::make('zonas-de-dolor')
                                ->label('Si sientes dolor en alguna zona del cuerpo, marca la zona afectada en el siguiente dibujo')
                                ->width(420)
                                ->height(360)
                                ->imageUrl(asset('images/canvas/zdd.png')),

                                ToggleButtons::make('intensidad')
                                ->label('Marca la intensidad del dolor')
                                    ->inline()
                                ->options([
                                    1, 2, 3, 4, 5, 6, 7, 8, 9, 10
                                ])

                            ])->columnSpan(4),

                            Section::make('Lengua')
                            ->schema([
                                TextInput::make('color')->placeholder('Color')->label(''),
                                Radio::make('humedad')->label('')
                                    ->options(['humeda' => 'Húmeda', 'seca' => 'Seca'])->inline(),
                                Radio::make('grosor')->label('')
                                    ->options(['delgada' => 'Delgada', 'hinchada' => 'Hinchada'])->inline(),
                                Radio::make('movimiento')->label('')
                                    ->options(['temblorosa' => 'Temblorosa', 'rigida' => 'Rígida'])->inline(),
                                Radio::make('flacidez')->label('')
                                    ->options(['flacida' => 'Flácida', 'enroscada' => 'Enroscada'])->inline(),
                                CheckboxList::make('patron')->label('')
                                    ->options(['fisurada' => 'Fisurada', 'dentada' => 'Dentada'])->columns(2),
                                CheckboxList::make('patron2')->label('')
                                    ->options(['puntos' => 'Con puntos', 'ulcerada' => 'Ulcerada'])->columns(2),
                                Radio::make('sublinguales')->label('Sublinguales estancadas')
                                    ->boolean()->inline(),
                                Textarea::make('otros')->maxLength(255)->autosize()
                            ])->columnSpan(4),

                            Section::make()
                            ->schema([
                                Textarea::make('observaciones')->rows(22)
                            ])->columnSpan(4),

                             Section::make()
                                 ->schema([
                                     Select::make('diagnostico')
                                         ->options([])
                                         ->multiple()
                                     ->columnSpan(4),
                                     DatePicker::make('inicio_del_tratamiento')
                                         ->default(today())
                                         ->columnSpan(4),

                                     TextInput::make('acupuntura')->columnSpan(4),
                                    TextInput::make('fitoterapia')->columnSpan(4)
                                 ])->columns(8),

                                Section::make()
                                ->schema([
                                    Select::make('pulso')->multiple(),

                                    CanvasPointerField::make('pulso_canvas')
                                        ->label('')
                                        ->imageUrl(asset('images/canvas/pulso.png'))
                                        ->width(660)
                                        ->height(400)
                                ])->columnSpan(5),

                                Section::make()
                                ->schema([
                                    CanvasPointerField::make('puntos')
                                        ->label('')
                                        ->imageUrl(asset('images/canvas/puntos.png'))
                                        ->width(360)
                                        ->height(400)
                                ])->columnSpan(3)

                        ])->columns(8),
                ])->columnSpanFull()->persistStepInQueryString('paso'),
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

