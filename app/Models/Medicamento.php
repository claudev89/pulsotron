<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
    public $timestamps = false;
    protected $fillable = ['nombre'];

    public function pacientes()
    {
        return $this->belongsToMany(Paciente::class);
    }
}
