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
            $table->string('carrier_name'); // Ej. 'DHL', 'Estafeta'
            $table->string('service_name'); // Ej. 'Terrestre', 'Express'
            $table->string('service_code')->unique(); // El código único de Envia.com (ej. 'dhl_express')
            $table->string('currency', 3)->default('MXN'); // MXN
            $table->decimal('total_price', 10, 2); // Costo total cotizado
            $table->string('delivery_estimate'); // Ej. '1 - 3 días hábiles'
            $table->string('tracking_link')->nullable(); // Si Envia.com proporciona un link genérico de seguimiento
            $table->json('raw_response_data')->nullable(); // Guarda la respuesta completa de Envia.com si es útil para depurar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_services');
    }
};
