<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PulsoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pulsos = [
            ['pulso' => 'Taiyin', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Jueyin', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Shaoyang', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Shaoyin', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Shaoyin invertido', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Gancho', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Yangming', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Taiyang', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Disperso', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Expansivo', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Constrictivo', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Pulso dentro de un pulso', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Encogido', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Fluido', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Estancado', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Corto', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Comprimido', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Cuerda', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Convexo', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Cóncavo', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Flotante', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Fino', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Ancho', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Sin forma', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Vacío', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Gu', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Duro', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Rápido', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Lento', 'lugar' => null, 'imagen' => null],
            ['pulso' => 'Intermitente', 'lugar' => null, 'imagen' => null],
        ];

        DB::table('pulsos')->insert($pulsos);
    }
}
