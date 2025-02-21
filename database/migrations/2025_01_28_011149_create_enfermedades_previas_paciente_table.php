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
        Schema::create('enfermedades_previas_paciente', function (Blueprint $table) {
            $table->foreignId('enfermedad_id')->constrained()->cascadeOnDelete();
            $table->string('paciente_rut', 10);

            $table->foreign('paciente_rut')
                ->references('rut')
                ->on('pacientes')
                ->cascadeOnDelete();

            $table->primary(['enfermedad_id', 'paciente_rut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enfermedades_previas_paciente');
    }
};
