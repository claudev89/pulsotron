<?php

namespace App\Filament\Resources\PacienteResource\Pages;

use App\Filament\Resources\PacienteResource;
use App\Services\PacienteFormSynchronizer;
use App\Models\Paciente;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $rut = $this->record->rut;

        // 1. Motivos de consulta
        foreach ([1, 2, 3] as $prioridad) {
            $data['motivo_consulta_' . $prioridad] = DB::table('motivo_consulta')
                ->where('paciente_rut', $rut)
                ->where('prioridad', $prioridad)
                ->value('descripcion') ?? '';
        }

        // 2. Enfermedades del paciente
        $tableEnfermedades = Schema::hasTable('enfermedads_pacientes') 
            ? 'enfermedads_pacientes' 
            : 'enfermedades_previas_paciente';

        if (Schema::hasTable($tableEnfermedades)) {
            $enfermedadesIds = DB::table($tableEnfermedades)
                ->where('paciente_rut', $rut)
                ->pluck('enfermedad_id')
                ->toArray();
            foreach ($enfermedadesIds as $id) {
                $data['enfermedad_' . $id] = true;
            }
        }

        // 3. Enfermedades de familiares
        if (Schema::hasTable('enfermedades_de_familiares')) {
            $familiarIds = DB::table('enfermedades_de_familiares')
                ->where('rut', $rut)
                ->pluck('enfermedad_id')
                ->toArray();
            foreach ($familiarIds as $id) {
                $data['enfermedad_familiar_' . $id] = true;
            }
        }

        // 4. Sintomatología previa
        foreach ($this->record->sintomasPrevios as $sintoma) {
            $data['sintoma_' . $sintoma->id] = $sintoma->pivot->frecuencia;
        }

        // 5. Sistema reproductor femenino (srf y srf2)
        if (Schema::hasTable('paciente_srf')) {
            $srfRows = DB::table('paciente_srf')->where('paciente_rut', $rut)->get();
            foreach ($srfRows as $row) {
                $frecuencia = $row->frecuencia;
                $val = $frecuencia === 'o' ? 'ocasional' : ($frecuencia === 'f' ? 'frecuente' : $frecuencia);
                $data['srf_' . $row->srf_id] = $val;
            }
        }

        if (Schema::hasTable('paciente_srf2')) {
            $srf2Rows = DB::table('paciente_srf2')->where('paciente_rut', $rut)->get();
            foreach ($srf2Rows as $row) {
                $data['srf2_' . $row->srf2_id] = $row->valor;
            }
        }

        // 6. Zonas de dolor (Canvas)
        if (Schema::hasTable('zona_dolor')) {
            $zona = DB::table('zona_dolor')->where('paciente_rut', $rut)->first();
            if ($zona) {
                $data['intensidad'] = $zona->intensidad;
                if (!empty($zona->imagen) && trim($zona->imagen) !== '') {
                    if (Storage::disk('public')->exists($zona->imagen)) {
                        $fileContents = Storage::disk('public')->get($zona->imagen);
                        $data['zonas-de-dolor'] = 'data:image/png;base64,' . base64_encode($fileContents);
                    }
                }
            }
        }

        // 7. Otros repeater
        if (Schema::hasTable('otros')) {
            $otros = DB::table('otros')->where('paciente_rut', $rut)->pluck('descripcion');
            $data['Otros'] = $otros->map(fn($text) => ['Otro' => $text])->toArray();
        }

        // 8. Pulsos (Tipos y Canvas)
        if (Schema::hasTable('pulsos')) {
            $pulsoRow = DB::table('pulsos')
                ->where('relacionable_id', $rut)
                ->where('relacionable_type', Paciente::class)
                ->first();
            if ($pulsoRow) {
                $data['pulso'] = !empty($pulsoRow->pulso) ? explode(',', $pulsoRow->pulso) : [];
                if (!empty($pulsoRow->imagen)) {
                    $data['pulso_canvas'] = 'data:image/png;base64,' . base64_encode($pulsoRow->imagen);
                }
            }
        }

        // 9. Puntos de acupuntura (Canvas)
        if (Schema::hasTable('puntos')) {
            $puntosRow = DB::table('puntos')
                ->where('relacionable_id', $rut)
                ->where('relacionable_type', Paciente::class)
                ->first();
            if ($puntosRow && !empty($puntosRow->imagen)) {
                $data['puntos'] = 'data:image/png;base64,' . base64_encode($puntosRow->imagen);
            }
        }

        return $data;
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
