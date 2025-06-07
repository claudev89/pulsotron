<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasSindromesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Síndromes del corazón'],
            ['nombre' => 'Síndromes bazo'],
            ['nombre' => 'Síndromes de pulmón'],
            ['nombre' => 'Síndromes de riñón'],
            ['nombre' => 'Síndromes del hígado'],
            ['nombre' => 'Síndromes de intestino delgado'],
            ['nombre' => 'Síndromes de intestino grueso'],
            ['nombre' => 'Síndromes de estómago'],
            ['nombre' => 'Síndromes de vejiga'],
            ['nombre' => 'Síndromes de vesícula biliar']
        ];

        DB::table('categorias_sindromes')->insert($categorias);
    }
}
