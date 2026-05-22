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
        Schema::table('puntos', function (Blueprint $table) {
            $table->unsignedBigInteger('relacionable_id');
            $table->string('relacionable_type');

            $table->index(['relacionable_id', 'relacionable_type'], 'puntos_relacionable_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('puntos', function (Blueprint $table) {
            $table->dropIndex('puntos_relacionable_index');
            $table->dropColumn(['relacionable_id', 'relacionable_type']);
        });
    }
};
