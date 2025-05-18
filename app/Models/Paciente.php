<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Paciente extends Model
{
    protected $appends = [
        'motivo_consulta_1',
        'motivo_consulta_2',
        'motivo_consulta_3',
    ];
    protected static function booted()
    {
        static::created(function (Paciente $paciente) {
            DB::transaction(function () use ($paciente) {
                $paciente->actualizarMotivosConsulta();
            });
        });

        static::updated(function (Paciente $paciente) {
            DB::transaction(function () use ($paciente) {
                $paciente->actualizarMotivosConsulta();
            });
        });
    }

    public function comuna(): BelongsTo
    {
        return $this->belongsTo(Comuna::class);
    }
    public function actualizarMotivosContulta()
    {
        foreach ([1, 2, 3] as $prioridad) {
            $descripcion = $this->{ 'motivo_consulta_' . $prioridad } ?? null;

            if(!empty($descripcion)) {
                DB::table('motivo_consulta')->updateOrInsert(
                    [
                        'prioridad' => $prioridad,
                        'paciente_rut' => $this->rut
                    ],
                    [
                        'descripcion' => $descripcion
                    ]
                );
            } else
            {
                DB::table('motivo_consulta')
                    ->where('prioridad', $prioridad)
                    ->where('paciente_rut', $this->rut)
                    ->delete();
            }
        }
    }

    public function getMotivoConsulta1Attribute()
    {
        return DB::table('motivo_consulta')
            ->where('paciente_rut', $this->rut)
            ->where('priotidad', 1)
            ->value('descripcion') ?? '';
    }

    public function getMotivoConsulta2Attribute()
    {
        return DB::table('motivo_consulta')
            ->where('paciente_rut', $this->rut)
            ->where('priotidad', 2)
            ->value('descripcion') ?? '';
    }

    public function getMotivoConsulta3Attribute()
    {
        return DB::table('motivo_consulta')
            ->where('paciente_rut', $this->rut)
            ->where('priotidad', 3)
            ->value('descripcion') ?? '';
    }

    public function diagnosticos() : BelongsTo
    {
        return $this->BelongsTo(Diagnostico::class);
    }

    public function sintomasPrevios()
    {
        return $this->belongsToMany(Sintoma::class, 'paciente_sintomas_previos', 'paciente_rut', 'sintoma_id')
            ->withPivot('frecuencia');
    }
}
