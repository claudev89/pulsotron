<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SindromeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sindromes = [

            // Síndromes del corazón
            ['nombre' => 'Deficiencia de Qi de corazón ', 'categoria_id' => 1],
            ['nombre' => 'Deficiencia de yang de corazón', 'categoria_id' => 1],
            ['nombre' => 'Deficiencia de yin de corazón', 'categoria_id' => 1],
            ['nombre' => 'Deficiencia de sangre de corazón ', 'categoria_id' => 1],
            ['nombre' => 'Estancamiento de sangre de corazón ', 'categoria_id' => 1],
            ['nombre' => 'Estancamiento de Qi de corazón ', 'categoria_id' => 1],
            ['nombre' => 'Flema en el corazón', 'categoria_id' => 1],
            ['nombre' => 'Calor en el corazón', 'categoria_id' => 1],
            ['nombre' => 'Fuego en el corazón ', 'categoria_id' => 1],

            //Síndromes del bazo
            ['nombre' => 'Deficiencia de Qi de bazo', 'categoria_id' => 2],
            ['nombre' => 'Deficiencia de yang de bazo', 'categoria_id' => 2],
            ['nombre' => 'Deficiencia de yin de bazo', 'categoria_id' => 2],
            ['nombre' => 'Deficiencia de sangre de bazo', 'categoria_id' => 2],
            ['nombre' => 'Humedad en el bazo', 'categoria_id' => 2],
            ['nombre' => 'Flema en el bazo', 'categoria_id' => 2],

            //Síndromes de pulmón
            ['nombre' => 'Deficiencia de Qi de pulmón ', 'categoria_id' => 3],
            ['nombre' => 'Deficiencia de yin de pulmón ', 'categoria_id' => 3],
            ['nombre' => 'Humedad en el pulmón ', 'categoria_id' => 3],
            ['nombre' => 'Flema en el pulmón ', 'categoria_id' => 3],
            ['nombre' => 'Calor en el pulmón ', 'categoria_id' => 3],
            ['nombre' => 'Calor humedad en el pulmón', 'categoria_id' => 3],
            ['nombre' => 'Fuego en el pulmón ', 'categoria_id' => 3],
            ['nombre' => 'Estancamiento de sangre en el pulmón', 'categoria_id' => 3],
            ['nombre' => 'Frío en el pulmón', 'categoria_id' => 3],
            ['nombre' => 'Viento calor en el pulmón', 'categoria_id' => 3],
            ['nombre' => 'Viento frío en el pulmón', 'categoria_id' => 3],
            ['nombre' => 'Viento sequedad en el pulmón', 'categoria_id' => 3],
            ['nombre' => 'Sequedad en el pulmón ', 'categoria_id' => 3],

            //Síndromes de riñón
            ['nombre' => 'Deficiencia de Qi de riñón', 'categoria_id' => 4],
            ['nombre' => 'Riñón no ancla el Qi', 'categoria_id' => 4],
            ['nombre' => 'Deficiencia de yang de riñón ', 'categoria_id' => 4],
            ['nombre' => 'Deficiencia de yin de riñón ', 'categoria_id' => 4],
            ['nombre' => 'Deficiencia de yang y yin de riñón', 'categoria_id' => 4],
            ['nombre' => 'Calor por deficiencia', 'categoria_id' => 4],
            ['nombre' => 'Deficiencia de escencia', 'categoria_id' => 4],
            ['nombre' => 'Fuego o calor en el mingmen', 'categoria_id' => 4],
            ['nombre' => 'Desarmonía corazón-riñón', 'categoria_id' => 4],

            //Síndromes del hígado
            ['nombre' => 'Deficiencia de Qi de hígado', 'categoria_id' => 5],
            ['nombre' => 'Deficiencia de sangre de hígado ', 'categoria_id' => 5],
            ['nombre' => 'Deficiencia de Yin de hígado ', 'categoria_id' => 5],
            ['nombre' => 'Estancamiento de Qi de hígado', 'categoria_id' => 5],
            ['nombre' => 'Estancamiento de sangre de hígado', 'categoria_id' => 5],
            ['nombre' => 'Alzamiento de yang de hígado ', 'categoria_id' => 5],
            ['nombre' => 'Viento interno', 'categoria_id' => 5],
            ['nombre' => 'Fuego de hígado', 'categoria_id' => 5],
            ['nombre' => 'Calor en el hígado', 'categoria_id' => 5],
            ['nombre' => 'Humedad calor en el hígado', 'categoria_id' => 5],
            ['nombre' => 'Humedad en el hígado', 'categoria_id' => 5],

            //Síndromes del intestino delgado
            ['nombre' => 'Estancamiento de Qi en intestino delgado ', 'categoria_id' => 6],
            ['nombre' => 'Calor en intestino delgado ', 'categoria_id' => 6],
            ['nombre' => 'Humedad en intestino delgado ', 'categoria_id' => 6],
            ['nombre' => 'Calor humedad en intestino delgado', 'categoria_id' => 6],

            //Síndromes del intestino grueso
            ['nombre' => 'Estancamiento de Qi en intestino grueso', 'categoria_id' => 7],
            ['nombre' => 'Humedad en intestino grueso', 'categoria_id' => 7],
            ['nombre' => 'Humedad calor en intestino grueso', 'categoria_id' => 7],
            ['nombre' => 'Frío en intestino grueso', 'categoria_id' => 7],
            ['nombre' => 'Calor en intestino grueso', 'categoria_id' => 7],
            ['nombre' => 'Fuego en intestino grueso', 'categoria_id' => 7],

            //Síndromes de estómago
            ['nombre' => 'Estancamiento de Qi en estómago', 'categoria_id' => 8],
            ['nombre' => 'Frío en estómago', 'categoria_id' => 8],
            ['nombre' => 'Humedad en el estómago', 'categoria_id' => 8],
            ['nombre' => 'Calor humedad en el estómago', 'categoria_id' => 8],
            ['nombre' => 'Estancamiento de sangre en el estómago', 'categoria_id' => 8],
            ['nombre' => 'Retención de alimentos en el estómago', 'categoria_id' => 8],
            ['nombre' => 'Calor en el estómago', 'categoria_id' => 8],
            ['nombre' => 'Fuego en el estómago', 'categoria_id' => 8],

            //Síndromes de vejiga
            ['nombre' => 'Estancamiento de Qi en vejiga', 'categoria_id' => 9],
            ['nombre' => 'Calor en la vejiga', 'categoria_id' => 9],
            ['nombre' => 'Calor humedad en la vejiga', 'categoria_id' => 9],
            ['nombre' => 'Frío en la vejiga', 'categoria_id' => 9],
            ['nombre' => 'Humedad en la vejiga', 'categoria_id' => 9],

            //Síndromes de vesícula biliar
            ['nombre' => 'Humedad en la vesícula', 'categoria_id' => 10],
            ['nombre' => 'Calor humedad en la vesícula', 'categoria_id' => 10],
            ['nombre' => 'Estancamiento de Qi la vesícula', 'categoria_id' => 10],
        ];

        DB::table('sindromes')->insert($sindromes);
    }
}
