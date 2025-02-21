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
            ['nombre' => 'Taiyin'],
            ['nombre' => 'Jueyin'],
            ['nombre' => 'Shaoyang'],
            ['nombre' => 'Shaoyin'],
            ['nombre' => 'Shaoyin invertido'],
            ['nombre' => 'Gancho'],
            ['nombre' => 'Yangming'],
            ['nombre' => 'Taiyang'],
            ['nombre' => 'Disperso'],
            ['nombre' => 'Expansivo'],
            ['nombre' => 'Constrictivo'],
            ['nombre' => 'Pulso dentro de un pulso'],
            ['nombre' => 'Encogido'],
            ['nombre' => 'Fluido'],
            ['nombre' => 'Estancado'],
            ['nombre' => 'Corto'],
            ['nombre' => 'Comprimido'],
            ['nombre' => 'Cuerda'],
            ['nombre' => 'Convexo'],
            ['nombre' => 'Cóncavo'],
            ['nombre' => 'Flotante'],
            ['nombre' => 'Fino'],
            ['nombre' => 'Ancho'],
            ['nombre' => 'Sin forma'],
            ['nombre' => 'Vacío'],
            ['nombre' => 'Gu'],
            ['nombre' => 'Duro'],
            ['nombre' => 'Rápido'],
            ['nombre' => 'Lento'],
            ['nombre' => 'Intermitente'],
        ];

        DB::table('pulsos')->insert($pulsos);
    }
}
