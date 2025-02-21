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
        Schema::create('lenguas', function (Blueprint $table) {
            $table->id();
            $table->string('color', 40);
            $table->enum('humedad', ['seca', 'humeda']);
            $table->enum('grosor', ['delgada', 'hinchada']);
            $table->enum('movimiento', ['temblorosa, rigida']);
            $table->enum('flacidez', ['flacida', 'enroscada']);
            $table->set('patron', ['fisurada', 'dentada']);
            $table->set('patron2', ['con_puntos', 'ulcerada']);
            $table->boolean('sublinguales_estancadas');
            $table->string('otros', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lenguas');
    }
};
