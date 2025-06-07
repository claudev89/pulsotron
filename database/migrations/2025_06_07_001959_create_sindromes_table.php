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
        Schema::create('sindromes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 64);
            $table->unsignedBigInteger('categoria_id');

            $table->foreign('categoria_id')->references('id')->on('categorias_sindromes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sindromes');
    }
};
