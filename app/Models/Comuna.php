<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comuna extends Model
{
    public $timestamps = false;

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function pacientes(): HasMany
    {
        return $this->hasMany(Paciente::class);
    }
}
