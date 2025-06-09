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
        Schema::table('artesanias', function (Blueprint $table) {
            $table->float('weight')->nullable()->after('stock')->comment('Peso en kilogramos');
            $table->integer('length')->nullable()->after('weight')->comment('Longitud en centímetros');
            $table->integer('width')->nullable()->after('length')->comment('Ancho en centímetros');
            $table->integer('height')->nullable()->after('width')->comment('Altura en centímetros');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artesanias', function (Blueprint $table) {
            $table->dropColumn(['weight', 'length', 'width', 'height']);
        });
    }
};
