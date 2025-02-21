<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sintoma extends Model
{
    public $timestamps = false;

    public function enfermedad(): BelongsToMany
    {
        return $this->belongsToMany(Enfermedad::class);
    }
}
