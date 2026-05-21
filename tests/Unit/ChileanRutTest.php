<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Support\ChileanRut;
use PHPUnit\Framework\TestCase;

class ChileanRutTest extends TestCase
{
    public function test_check_digit_known_values(): void
    {
        $this->assertSame('5', ChileanRut::checkDigit('12345678'));
        $this->assertSame('1', ChileanRut::checkDigit('11111111'));
        $this->assertSame('2', ChileanRut::checkDigit('22222222'));
        $this->assertSame('3', ChileanRut::checkDigit('33333333'));
    }

    public function test_is_valid_accepts_with_and_without_dots(): void
    {
        $this->assertTrue(ChileanRut::isValid('12.345.678-5'));
        $this->assertTrue(ChileanRut::isValid('12345678-5'));
        $this->assertTrue(ChileanRut::isValid('123456785'));
    }

    public function test_is_valid_rejects_wrong_check_digit(): void
    {
        $this->assertFalse(ChileanRut::isValid('12345678-0'));
        $this->assertFalse(ChileanRut::isValid('11111111-5'));
    }

    public function test_is_valid_rejects_k_when_digit_expected(): void
    {
        $this->assertFalse(ChileanRut::isValid('12345678-K'));
    }
}
