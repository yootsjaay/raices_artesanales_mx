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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // El ítem pertenece a un pedido

            // Referencia a la artesanía original (opcional, pero buena práctica)
            $table->foreignId('artesania_id')->nullable()->constrained()->onDelete('set null');

            // Snapshot de los datos de la artesanía al momento de la compra
            $table->string('name'); // Nombre de la artesanía
            $table->decimal('price', 10, 2); // Precio de la artesanía en el momento de la compra
            $table->integer('quantity'); // Cantidad comprada

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
