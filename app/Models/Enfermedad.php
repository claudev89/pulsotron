<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enfermedad extends Model
{
    public $timestamps = false;

    public function sintomas(): HasMany
    {
        return $this->hasMany(Sintoma::class);
    }

    public function diagnosticos(): BelongsToMany
    {
        return $this->belongsToMany(Diagnostico::class);
    }
}
