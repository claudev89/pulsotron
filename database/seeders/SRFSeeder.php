<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SRFSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $srf = [
            ['nombre' => 'Síntomas premenstruales'],
            ['nombre' => 'Menstruación irregular'],
            ['nombre' => 'Menstruación dolorosa'],
            ['nombre' => 'Quistes mamarios'],
            ['nombre' => 'Presencia de coágulos'],
            ['nombre' => 'Síntomas de menopausia'],
        ];

        DB::table('srf')->insert($srf);
    }
}
