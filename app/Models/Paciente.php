<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paciente extends Model
{
    public function comuna(): BelongsTo
    {
        return $this->belongsTo(Comuna::class);
    }
}
