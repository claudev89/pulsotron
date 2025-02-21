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
        Schema::create('paciente_srf2', function (Blueprint $table) {
            $table->string('paciente_rut', 10);
            $table->unsignedBigInteger('srf2_id');
            $table->unsignedTinyInteger('valor');

            $table->foreign('paciente_rut')
                ->references('rut')
                ->on('pacientes')
                ->cascadeOnDelete();

            $table->foreign('srf2_id')
                ->references('id')
                ->on('srf2')
                ->cascadeOnDelete();

            $table->primary(['paciente_rut', 'srf2_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paciente_srf2');
    }
};
