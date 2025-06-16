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
        Schema::create('shipping_services', function (Blueprint $table) {
        $table->id();
        $table->foreignId('shipping_carrier_id')->constrained()->onDelete('cascade');
        $table->string('nombre'); // Ej: express, ground, overnight
        $table->string('descripcion')->nullable();
        $table->decimal('costo_fijo', 10, 2)->nullable(); // si tienes tarifa fija
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
