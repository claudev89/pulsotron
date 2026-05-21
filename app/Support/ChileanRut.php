<?php

declare(strict_types=1);

namespace App\Support;

final class ChileanRut
{
    /**
     * Normaliza a mayúsculas, sin puntos ni espacios. Mantiene el guion si existe.
     */
    public static function normalize(string $rut): string
    {
        $rut = strtoupper(trim($rut));
        $rut = str_replace(['.', ' '], '', $rut);

        return $rut;
    }

    /**
     * Calcula el dígito verificador (0-9 o K) para el cuerpo numérico del RUT (sin DV).
     */
    public static function checkDigit(string $body): string
    {
        $body = preg_replace('/\D/', '', $body) ?? '';

        if ($body === '') {
            return '';
        }

        $sum = 0;
        $multiplier = 2;

        for ($i = strlen($body) - 1; $i >= 0; $i--) {
            $sum += (int) $body[$i] * $multiplier;
            $multiplier = $multiplier === 7 ? 2 : $multiplier + 1;
        }

        $remainder = $sum % 11;
        $digit = 11 - $remainder;

        if ($digit === 11) {
            return '0';
        }

        if ($digit === 10) {
            return 'K';
        }

        return (string) $digit;
    }

    /**
     * Valida formato y dígito verificador del RUT chileno.
     * Acepta valores con o sin puntos; el guion puede estar ausente si solo quedan dígitos+K.
     */
    public static function isValid(?string $rut): bool
    {
        if ($rut === null || $rut === '') {
            return false;
        }

        $normalized = self::normalize($rut);

        if ($normalized === '') {
            return false;
        }

        $body = '';
        $dv = '';

        if (str_contains($normalized, '-')) {
            $parts = explode('-', $normalized, 2);
            $body = preg_replace('/\D/', '', $parts[0] ?? '') ?? '';
            $dv = strtoupper(trim($parts[1] ?? ''));
        } else {
            if (strlen($normalized) < 2) {
                return false;
            }
            $dv = strtoupper(substr($normalized, -1));
            $body = preg_replace('/\D/', '', substr($normalized, 0, -1)) ?? '';
        }

        if ($body === '' || $dv === '' || strlen($body) < 7 || strlen($body) > 8) {
            return false;
        }

        if (! preg_match('/^[\dK]$/', $dv)) {
            return false;
        }

        return self::checkDigit($body) === $dv;
    }
}
