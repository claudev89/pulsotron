<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Enfermedad;
use App\Models\Paciente;
use App\Models\Sintoma;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

final class PacienteFormSynchronizer
{
    /**
     * @return array{0: array<string, mixed>, 1: array<string, mixed>}
     */
    public static function splitForPacienteTable(array $data): array
    {
        $columns = array_flip(Schema::getColumnListing('pacientes'));
        $mass = [];
        $extras = [];

        foreach ($data as $key => $value) {
            if ($key === 'edad') {
                continue;
            }

            if (isset($columns[$key])) {
                if ($key === 'medicamentos' && is_array($value)) {
                    $extras[$key] = $value;

                    continue;
                }
                $mass[$key] = $value;
            } else {
                $extras[$key] = $value;
            }
        }

        return [$mass, $extras];
    }

    public static function persistExtras(Paciente $record, array $extras): void
    {
        $rut = $record->rut;

        self::syncMotivosConsulta($rut, $extras);
        self::syncSintomasPrevios($record, $extras);
        self::syncMedicamentos($record, $extras);
        if (Schema::hasTable('enfermedads_pacientes')) {
            self::syncEnfermedadesTabla($rut, $extras, 'enfermedads_pacientes', 'enfermedad_');
        } elseif (Schema::hasTable('enfermedades_previas_paciente')) {
            self::syncEnfermedadesTabla($rut, $extras, 'enfermedades_previas_paciente', 'enfermedad_');
        }
        self::syncEnfermedadesFamiliares($rut, $extras);
        self::syncPacienteSrf($rut, $extras);
        self::syncPacienteSrf2($rut, $extras);
        self::syncZonaDolor($rut, $extras);
        self::syncOtrosRepeater($rut, $extras);
        self::syncPulso($rut, $extras);
        self::syncPuntos($rut, $extras);
    }

    private static function syncMotivosConsulta(string $rut, array $extras): void
    {
        if (! Schema::hasTable('motivo_consulta')) {
            return;
        }

        foreach ([1, 2, 3] as $prioridad) {
            $descripcion = $extras['motivo_consulta_'.$prioridad] ?? null;

            if (! empty($descripcion)) {
                DB::table('motivo_consulta')->updateOrInsert(
                    [
                        'prioridad' => $prioridad,
                        'paciente_rut' => $rut,
                    ],
                    [
                        'descripcion' => $descripcion,
                    ]
                );
            } else {
                DB::table('motivo_consulta')
                    ->where('prioridad', $prioridad)
                    ->where('paciente_rut', $rut)
                    ->delete();
            }
        }
    }

    private static function syncSintomasPrevios(Paciente $record, array $extras): void
    {
        $syncData = [];

        foreach (Sintoma::query()->get(['id']) as $sintoma) {
            $frecuencia = $extras['sintoma_'.$sintoma->id] ?? null;
            if ($frecuencia) {
                $syncData[$sintoma->id] = ['frecuencia' => $frecuencia];
            }
        }

        $record->sintomasPrevios()->sync($syncData);
    }

    private static function syncMedicamentos(Paciente $record, array $extras): void
    {
        if (! array_key_exists('medicamentos', $extras)) {
            return;
        }

        $ids = $extras['medicamentos'];
        $record->medicamentos()->sync(is_array($ids) ? $ids : []);
    }

    private static function syncEnfermedadesTabla(string $rut, array $extras, string $table, string $prefix): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        DB::table($table)->where('paciente_rut', $rut)->delete();

        foreach (Enfermedad::query()->get(['id']) as $enfermedad) {
            if (empty($extras[$prefix.$enfermedad->id])) {
                continue;
            }

            DB::table($table)->insert([
                'enfermedad_id' => $enfermedad->id,
                'paciente_rut' => $rut,
            ]);
        }
    }

    private static function syncEnfermedadesFamiliares(string $rut, array $extras): void
    {
        if (! Schema::hasTable('enfermedades_de_familiares')) {
            return;
        }

        DB::table('enfermedades_de_familiares')->where('rut', $rut)->delete();

        foreach (Enfermedad::query()->get(['id']) as $enfermedad) {
            if (empty($extras['enfermedad_familiar_'.$enfermedad->id])) {
                continue;
            }

            DB::table('enfermedades_de_familiares')->insert([
                'rut' => $rut,
                'enfermedad_id' => $enfermedad->id,
            ]);
        }
    }

    private static function syncPacienteSrf(string $rut, array $extras): void
    {
        if (! Schema::hasTable('paciente_srf') || ! Schema::hasTable('srf')) {
            return;
        }

        DB::table('paciente_srf')->where('paciente_rut', $rut)->delete();

        $hasFrecuencia = Schema::hasColumn('paciente_srf', 'frecuencia');

        foreach (DB::table('srf')->pluck('id') as $srfId) {
            $value = $extras['srf_'.$srfId] ?? null;
            if ($value === null || $value === '') {
                continue;
            }

            $frecuencia = match ($value) {
                'ocasional' => 'o',
                'frecuente' => 'f',
                default => is_string($value) && strlen($value) === 1 ? $value : 'o',
            };

            $row = [
                'paciente_rut' => $rut,
                'srf_id' => $srfId,
            ];

            if ($hasFrecuencia) {
                $row['frecuencia'] = $frecuencia;
            }

            DB::table('paciente_srf')->insert($row);
        }
    }

    private static function syncPacienteSrf2(string $rut, array $extras): void
    {
        if (! Schema::hasTable('paciente_srf2') || ! Schema::hasTable('srf2')) {
            return;
        }

        DB::table('paciente_srf2')->where('paciente_rut', $rut)->delete();

        foreach (DB::table('srf2')->pluck('id') as $srf2Id) {
            $key = 'srf2_'.$srf2Id;
            if (! array_key_exists($key, $extras) || $extras[$key] === null || $extras[$key] === '') {
                continue;
            }

            DB::table('paciente_srf2')->insert([
                'paciente_rut' => $rut,
                'srf2_id' => $srf2Id,
                'valor' => (int) $extras[$key],
            ]);
        }
    }

    private static function syncZonaDolor(string $rut, array $extras): void
    {
        if (! Schema::hasTable('zona_dolor')) {
            return;
        }

        DB::table('zona_dolor')->where('paciente_rut', $rut)->delete();

        $canvas = $extras['zonas-de-dolor'] ?? null;
        $intensidad = $extras['intensidad'] ?? null;

        $imagen = '';

        if (is_string($canvas) && str_starts_with($canvas, 'data:image')
            && preg_match('/^data:image\/\w+;base64,(.+)$/', $canvas, $matches)) {
            $binary = base64_decode($matches[1], true);
            if ($binary !== false) {
                Storage::disk('public')->makeDirectory('zonas-dolor');
                $path = 'zonas-dolor/'.$rut.'-dolor.png';
                Storage::disk('public')->put($path, $binary);
                $imagen = $path;
            }
        }

        if ($imagen === '' && ($intensidad === null || $intensidad === '')) {
            return;
        }

        DB::table('zona_dolor')->insert([
            'imagen' => $imagen !== '' ? $imagen : ' ',
            'intensidad' => (int) ($intensidad ?? 0),
            'paciente_rut' => $rut,
        ]);
    }

    private static function syncOtrosRepeater(string $rut, array $extras): void
    {
        if (! Schema::hasTable('otros')) {
            return;
        }

        DB::table('otros')->where('paciente_rut', $rut)->delete();

        $rows = $extras['Otros'] ?? null;
        if (! is_array($rows)) {
            return;
        }

        foreach ($rows as $row) {
            $text = is_array($row) ? ($row['Otro'] ?? '') : '';
            if (trim((string) $text) === '') {
                continue;
            }

            DB::table('otros')->insert([
                'paciente_rut' => $rut,
                'descripcion' => $text,
            ]);
        }
    }

    private static function syncPulso(string $rut, array $extras): void
    {
        if (! Schema::hasTable('pulsos')) {
            return;
        }

        DB::table('pulsos')
            ->where('relacionable_id', $rut)
            ->where('relacionable_type', Paciente::class)
            ->delete();

        $pulso = $extras['pulso'] ?? null;
        $canvas = $extras['pulso_canvas'] ?? null;

        $binary = null;
        if (is_string($canvas) && str_starts_with($canvas, 'data:image')
            && preg_match('/^data:image\/\w+;base64,(.+)$/', $canvas, $matches)) {
            $binary = base64_decode($matches[1], true);
            if ($binary === false) {
                $binary = null;
            }
        }

        if (empty($pulso) && $binary === null) {
            return;
        }

        DB::table('pulsos')->insert([
            'pulso' => is_array($pulso) ? implode(',', $pulso) : '',
            'lugar' => 'Izquierdo',
            'imagen' => $binary ?? '',
            'relacionable_id' => $rut,
            'relacionable_type' => Paciente::class,
        ]);
    }

    private static function syncPuntos(string $rut, array $extras): void
    {
        if (! Schema::hasTable('puntos')) {
            return;
        }

        DB::table('puntos')
            ->where('relacionable_id', $rut)
            ->where('relacionable_type', Paciente::class)
            ->delete();

        $canvas = $extras['puntos'] ?? null;

        $binary = null;
        if (is_string($canvas) && str_starts_with($canvas, 'data:image')
            && preg_match('/^data:image\/\w+;base64,(.+)$/', $canvas, $matches)) {
            $binary = base64_decode($matches[1], true);
            if ($binary === false) {
                $binary = null;
            }
        }

        if ($binary === null) {
            return;
        }

        DB::table('puntos')->insert([
            'imagen' => $binary,
            'relacionable_id' => $rut,
            'relacionable_type' => Paciente::class,
        ]);
    }
}
