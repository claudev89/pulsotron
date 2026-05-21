<?php

declare(strict_types=1);

namespace App\Rules;

use App\Support\ChileanRut;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidChileanRut implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (! is_string($value) && ! is_numeric($value)) {
            $fail('El RUT no tiene un formato válido.');

            return;
        }

        if (! ChileanRut::isValid((string) $value)) {
            $fail('El RUT no es válido. Revise el número y el dígito verificador.');
        }
    }
}
