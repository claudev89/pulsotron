<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnfermedadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enfermedades = [
            ['nombre' => 'AVE'],
            ['nombre' => 'Alergias'],
            ['nombre' => 'Anemia'],
            ['nombre' => 'Artritis'],
            ['nombre' => 'Asma'],
            ['nombre' => 'Cáncer'],
            ['nombre' => 'Enfermedades coronarias'],
            ['nombre' => 'Enfermedades a la tiroides'],
            ['nombre' => 'Hepatitis'],
            ['nombre' => 'Hipertensión'],
            ['nombre' => 'VIH'],
            ['nombre' => 'Diabetes'],
        ];

        DB::table('enfermedads')->insert($enfermedades);
    }
}
