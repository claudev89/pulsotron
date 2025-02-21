<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Diagnostico extends Model
{
    public function pacientes(): HasMany
    {
        return $this->hasMany(User::class);
    }
    public function enfermedades(): HasMany
    {
        return $this->hasMany(Enfermedad::class);
    }
}
