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
         Schema::create('artesania_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artesania_id')->constrained()->onDelete('cascade'); // La variante pertenece a una artesanía principal

            $table->string('sku')->unique()->nullable()->comment('Stock Keeping Unit (Código de Identificación de la Variación)'); // Útil para inventario y sistemas externos

            // --- Atributos de la variante ---
            $table->string('size')->nullable()->comment('Talla (ej. S, M, L, XL)');
            $table->string('color')->nullable()->comment('Color (ej. Rojo, Azul)');
            $table->string('material_variant')->nullable()->comment('Si hay variantes de material (ej. Algodón, Seda)');
           

            $table->decimal('price_adjustment', 10, 2)->default(0.00)->comment('Ajuste de precio para esta variante (ej. si una talla XL es más cara)');
            $table->integer('stock')->default(0)->comment('Inventario específico para esta variante');
            $table->string('image')->nullable()->comment('Imagen específica para esta variante, si es diferente a la principal');

            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artesania_variants');
    }
};
