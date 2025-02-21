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
        Schema::create('evolucion', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('sesion');
            $table->string('paciente_rut', 10);
            $table->foreignId('pulso_id')->constrained()->cascadeOnDelete();
            $table->string('auriculoterapia')->nullable();
            $table->string('fitoterapua')->nullable();
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
        Schema::dropIfExists('evolucion');
    }
};
