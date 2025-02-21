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
        Schema::create('diagnostico_procedimiento', function (Blueprint $table) {
            $table->foreignId('diagnostico_id')->constrained()->cascadeOnDelete();
            $table->foreignId('procedimiento_id')->constrained()->cascadeOnDelete();

            $table->primary(['diagnostico_id', 'procedimiento_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostico_procedimiento');
    }
};
