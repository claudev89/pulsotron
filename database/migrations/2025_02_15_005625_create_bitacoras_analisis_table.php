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
        Schema::create('bitacoras_analisis', function (Blueprint $table) {
            $table->id();
            $table->string('paciente_rut');
            $table->string('comentario');
            $table->timestamps();

            $table->foreign('paciente_rut')
                ->references('rut')
                ->on('pacientes')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacoras_analisis');
    }
};
