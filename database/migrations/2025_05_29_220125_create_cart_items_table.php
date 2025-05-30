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
       Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->onDelete('cascade');
            $table->foreignId('artesania_id')->constrained('artesanias')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // Precio al momento de añadir, para evitar problemas si el precio de la artesanía cambia
            $table->timestamps();

            // Asegura que no se dupliquen items de la misma artesanía en el mismo carrito
            $table->unique(['cart_id', 'artesania_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
