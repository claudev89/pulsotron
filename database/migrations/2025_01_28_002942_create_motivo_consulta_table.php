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
        Schema::create('motivo_consulta', function (Blueprint $table) {
            $table->unsignedBigInteger('prioridad');
            $table->string('paciente_rut', 10);
            $table->string('descripcion');

            $table->foreign('paciente_rut')
                ->references('rut')
                ->on('pacientes')
                ->cascadeOnDelete();

            $table->primary(['prioridad', 'paciente_rut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motivo_consulta');
    }
};
