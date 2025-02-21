<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SintomaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sintomas = [
            ['nombre' => 'Depresión' ],
            ['nombre' => 'Irritabilidad' ],
            ['nombre' => 'Falta de concentración' ],
            ['nombre' => 'Estrés' ],
            ['nombre' => 'Ansiedad' ],
            ['nombre' => 'Insomnio' ],
            ['nombre' => 'Sueño perturbado' ],
            ['nombre' => 'Fatiga/Cansancio' ],
            ['nombre' => 'Mareos/Desmayos' ],
            ['nombre' => 'Pérdida/Aumento de peso' ],
            ['nombre' => 'Hemorragias' ],
            ['nombre' => 'Sudor espontáneo' ],
        ];

        DB::table('sintomas')->insert($sintomas);
    }
}
