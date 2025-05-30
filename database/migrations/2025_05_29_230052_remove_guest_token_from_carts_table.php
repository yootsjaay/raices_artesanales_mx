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
        Schema::table('carts', function (Blueprint $table) {
        $table->dropColumn('guest_token');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('carts', function (Blueprint $table) {
        // Puedes volver a añadirla si cambias de opinión en el futuro
        $table->string('guest_token')->nullable()->unique();
    });
    }
};
