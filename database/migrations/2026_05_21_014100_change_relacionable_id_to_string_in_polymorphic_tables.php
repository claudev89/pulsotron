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
        Schema::table('pulsos', function (Blueprint $table) {
            $table->string('relacionable_id', 10)->change();
        });
        Schema::table('lenguas', function (Blueprint $table) {
            $table->string('relacionable_id', 10)->change();
        });
        Schema::table('puntos', function (Blueprint $table) {
            $table->string('relacionable_id', 10)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pulsos', function (Blueprint $table) {
            $table->unsignedBigInteger('relacionable_id')->change();
        });
        Schema::table('lenguas', function (Blueprint $table) {
            $table->unsignedBigInteger('relacionable_id')->change();
        });
        Schema::table('puntos', function (Blueprint $table) {
            $table->unsignedBigInteger('relacionable_id')->change();
        });
    }
};
