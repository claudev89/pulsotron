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
        Schema::create('enfermedades_de_familiares', function (Blueprint $table) {
            $table->string('rut', 10);
            $table->foreignId('enfermedad_id')->constrained()->cascadeOnDelete();

            $table->foreign('rut')->references('rut')->on('pacientes')->cascadeOnDelete();

            $table->primary(['rut', 'enfermedad_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enfermedades_de_familiares');
    }
};
