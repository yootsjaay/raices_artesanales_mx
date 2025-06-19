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
                Schema::create('shipments', function (Blueprint $table) {
                $table->id();
                
                // Relación con carrito 
                $table->foreignId('cart_id')->nullable()->constrained()->onDelete('set null');

                // Datos de destino
                $table->string('destination_name');
                $table->string('destination_company')->nullable();
                $table->string('destination_email');
                $table->string('destination_phone');
                $table->string('destination_street');
                $table->string('destination_number');
                $table->string('destination_district')->nullable();
                $table->string('destination_city');
                $table->string('destination_state');
                $table->string('destination_postal_code');
                $table->string('destination_country')->default('MX');
                $table->string('destination_reference')->nullable();

                // Datos del envío
                $table->string('carrier'); // fedex, dhl, etc.
                $table->string('service'); // ground, express, etc.
                $table->string('tracking_number')->nullable();
                $table->string('label_url')->nullable();
                $table->string('order_id')->nullable();
                $table->string('status')->default('pending'); // pendiente, enviado, entregado, etc.

                // Peso y medidas totales
                $table->decimal('total_weight', 8, 2);
                $table->decimal('length', 8, 2);
                $table->decimal('width', 8, 2);
                $table->decimal('height', 8, 2);

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
