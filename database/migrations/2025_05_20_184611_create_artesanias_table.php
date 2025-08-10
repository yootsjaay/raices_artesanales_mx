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
        Schema::create('artesanias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->comment('Nombre general del tipo de artesanía (ej. Guayabera Tradicional, Calzado de Cuero)');
            $table->string('slug')->unique()->comment('URL-friendly unique identifier');
            $table->text('descripcion')->nullable()->comment('Descripción general del tipo de artesanía');
            $table->json('imagen_artesanias')->nullable()->comment('URL de una imagen representativa del tipo de artesanía (general, no de una variante específica)');
            $table->text('historia_piezas_general')->nullable()->comment('Historia o contexto cultural general del tipo de artesanía');
            
            // Campos de precio, peso y dimensiones para la artesanía general
            $table->decimal('precio', 10, 2)->nullable()->comment('Precio base de la artesanía (puede ser anulable si solo las variantes tienen precio)');
          
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade')->comment('ID of the category this type of craft belongs to');
            $table->foreignId('ubicacion_id')->nullable()->constrained('ubicaciones')->onDelete('set null')->comment('ID of the origin or sales location for this type of craft');
            $table->boolean('is_active')->default(true)->comment('indica si la artesanía está activa o no');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artesanias');
    }
};
