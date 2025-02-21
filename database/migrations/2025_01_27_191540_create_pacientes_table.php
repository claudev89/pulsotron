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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->string('rut', 10);
            $table->date('fecha_nacimiento');
            $table->string('nombre');
            $table->string('direccion_calle');
            $table->unsignedSmallInteger('direccion_numero');
            $table->foreignId('comuna_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('telefono')->nullable();
            $table->unsignedInteger('celular');
            $table->string('correo')->nullable();
            $table->string('contacto_nombre')->nullable();
            $table->unsignedInteger('contacto_telefono')->nullable();
            $table->unsignedInteger('contacto_celular')->nullable();
            $table->string('tratamiento')->nullable();
            $table->string('cirugia')->nullable();
            $table->unsignedTinyInteger('deporte')->nullable();
            $table->unsignedTinyInteger('alcohol')->nullable();
            $table->unsignedTinyInteger('fumar')->nullable();
            $table->unsignedTinyInteger('cafe')->nullable();
            $table->unsignedTinyInteger('agua')->nullable();
            $table->string('medicamentos')->nullable();
            $table->string('comentarios')->nullable();
            $table->timestamps();

            $table->primary('rut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
