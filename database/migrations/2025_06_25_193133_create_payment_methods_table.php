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
         Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Clave foránea al usuario

            // IDs de Mercado Pago
            $table->string('customer_id_mp')->nullable()->comment('ID del cliente en Mercado Pago');
            $table->string('card_id_mp')->nullable()->comment('ID del método de pago/tarjeta en Mercado Pago'); // Si MP devuelve un ID para la tarjeta

            // Información no sensible para mostrar al usuario
            $table->string('card_brand')->nullable(); // Ej. 'visa', 'mastercard'
            $table->string('last_four_digits', 4)->nullable(); // Últimos 4 dígitos para identificación
            $table->string('expiration_month', 2)->nullable();
            $table->string('expiration_year', 4)->nullable();

            $table->boolean('is_default')->default(false); // Marca como método de pago predeterminado
            $table->timestamps();

            // Índices
            $table->index('customer_id_mp'); // Para búsquedas rápidas por el ID del cliente de MP
            $table->index('card_id_mp');    // Para búsquedas rápidas por el ID de la tarjeta de MP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
