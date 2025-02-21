<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pulsos', function (Blueprint $table) {
            $table->id();
            $table->set('pulso', [
                'Taiyin',
                'Jueyin',
                'Shaoyang',
                'Shaoyin',
                'Shaoyin invertido',
                'Gancho',
                'Yangming',
                'Taiyang',
                'Disperso',
                'Expansivo',
                'Constrictivo',
                'Pulso dentro de un pulso',
                'Encogido',
                'Fluido',
                'Estancado',
                'Corto',
                'Comprimido',
                'Cuerda',
                'Convexo',
                'Cóncavo',
                'Flotante',
                'Fino',
                'Ancho',
                'Sin forma',
                'Vacío',
                'Gu',
                'Duro',
                'Rápido',
                'Lento',
                'Intermitente',
            ]);
            $table->enum('lugar', ['Izquierdo', 'Derecho']);
            $table->binary('imagen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pulsos');
    }
};
