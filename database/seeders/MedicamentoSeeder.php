<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicamentos = [
            ['nombre' => 'Paracetamol (acetaminofén)'],
            ['nombre' => 'Ibuprofeno'],
            ['nombre' => 'Aspirina (ácido acetilsalicílico)'],
            ['nombre' => 'Naproxeno'],
            ['nombre' => 'Ketorolaco'],
            ['nombre' => 'Diclofenaco'],
            ['nombre' => 'Metocarbamol'],
            ['nombre' => 'Ciclobenzaprina'],
            ['nombre' => 'Orfenadrina'],
            ['nombre' => 'Loratadina'],
            ['nombre' => 'Cetirizina'],
            ['nombre' => 'Fexofenadina'],
            ['nombre' => 'Clorfenamina'],
            ['nombre' => 'Desloratadina'],
            ['nombre' => 'Salbutamol'],
            ['nombre' => 'Bromhexina'],
            ['nombre' => 'Ambroxol'],
            ['nombre' => 'Montelukast'],
            ['nombre' => 'Budesonida (inhalador)'],
            ['nombre' => 'Enalapril'],
            ['nombre' => 'Losartán'],
            ['nombre' => 'Atenolol'],
            ['nombre' => 'Amlodipino'],
            ['nombre' => 'Furosemida'],
            ['nombre' => 'Aspirina 100'],
            ['nombre' => 'Simvastatina / Atorvastatina'],
            ['nombre' => 'Omeprazol'],
            ['nombre' => 'Pantoprazol'],
            ['nombre' => 'Ranitidina'],
            ['nombre' => 'Metoclopramida'],
            ['nombre' => 'Domperidona'],
            ['nombre' => 'Loperamida'],
            ['nombre' => 'Metformina'],
            ['nombre' => 'Glibenclamida'],
            ['nombre' => 'Insulina'],
            ['nombre' => 'Sertralina'],
            ['nombre' => 'Fluoxetina'],
            ['nombre' => 'Clonazepam'],
            ['nombre' => 'Alprazolam'],
            ['nombre' => 'Diazepam'],
            ['nombre' => 'Amoxicilina'],
            ['nombre' => 'Azitromicina'],
            ['nombre' => 'Cefalexina'],
            ['nombre' => 'Ciprofloxacino'],
            ['nombre' => 'Clavulánico'],
            ['nombre' => 'Hierro'],
            ['nombre' => 'Calcio'],
            ['nombre' => 'Vitamina D'],
            ['nombre' => 'Multivitamínicos'],
            ['nombre' => 'Ácido fólico'],
        ];

        DB::table('medicamentos')->insert($medicamentos);
    }
}
