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
        Schema::create('paciente_sintomas_previos', function (Blueprint $table) {
            $table->string('paciente_rut');
            $table->foreignId('sintoma_id')->constrained();
            $table->set('frecuencia', ['ocasional', 'frecuente'])->nullable();

            $table->foreign('paciente_rut')
                ->references('rut')
                ->on('pacientes')
                ->cascadeOnDelete();

            $table->primary(['paciente_rut', 'sintoma_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_paciente_sintomas_previos');
    }
};
