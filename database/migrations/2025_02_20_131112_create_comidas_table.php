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
        Schema::create('comidas', function (Blueprint $table) {
            $table->id();
            $table->string('paciente_rut');
            $table->enum('dia', ['l', 'm', 'x', 'j', 'v', 's', 'd']);
            $table->string('desayuno')->nullable();
            $table->string('media_maniana')->nullable();
            $table->string('almuerzo')->nullable();
            $table->string('media_tarde')->nullable();
            $table->string('once')->nullable();
            $table->string('cena')->nullable();
            $table->string('comemtarios')->nullable();

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
        Schema::dropIfExists('comidas');
    }
};
