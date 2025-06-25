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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // El usuario puede ser null si se borra

            $table->decimal('subtotal_amount', 10, 2); // Nuevo: solo el total de productos
            $table->decimal('shipping_cost', 10, 2)->default(0.00); // Costo del envío, tomado del carrito
            $table->decimal('total_amount', 10, 2); // Suma de subtotal + shipping_cost

            $table->string('status')->default('pending')->comment('pending, processing, completed, cancelled, refunded');
            $table->string('payment_status')->default('pending')->comment('pending, paid, failed, refunded');

            $table->string('payment_id_mp')->nullable()->comment('ID de la transacción en Mercado Pago'); // ID de la transacción de MP
            $table->string('preference_id_mp')->nullable()->comment('ID de la preferencia de checkout de Mercado Pago'); // Solo si usas Checkout Pro

            // Copia de la dirección de envío al momento del pedido (JSON)
            $table->json('shipping_address_snapshot')->nullable();
            // Copia de la dirección de facturación al momento del pedido (JSON), si es diferente
            $table->json('billing_address_snapshot')->nullable();
            // Copia de los detalles del servicio de envío (JSON)
            $table->json('shipping_details_snapshot')->nullable()->comment('Snapshot de carrier, service, tracking, label, etc.');

            $table->timestamps();

            // Si quieres indexar para búsquedas rápidas por el ID de transacción de MP
            $table->index('payment_id_mp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
