<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SRF2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $srf2 = [
            ['nombre' => 'Edad menarquía'],
            ['nombre' => 'Edad menopausia'],
            ['nombre' => 'Duración de la menstruación'],
            ['nombre' => 'Duración del ciclo'],
            ['nombre' => 'Número de embarazos'],
            ['nombre' => 'Número de partos'],
        ];

        DB::table('srf2')->insert($srf2);
    }
}
