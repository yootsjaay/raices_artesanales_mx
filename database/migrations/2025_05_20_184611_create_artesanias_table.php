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
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->integer('stock')->default(0);
            $table->string('imagen_principal')->nullable();
            $table->json('imagen_adicionales')->nullable(); // JSON para múltiples imágenes
            $table->string('materiales')->nullable();
            // Ya no es 'dimensiones' como string, sino campos individuales
            $table->text('historia_piezas')->nullable();

            // Claves foráneas
            // $table->foreignId('artesano_id')->constrained('artesanos')->onDelete('cascade'); // Si tienes tabla de artesanos
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->foreignId('ubicacion_id')->nullable()->constrained('ubicaciones')->onDelete('set null');

            // --- CAMPOS AGREGADOS/AJUSTADOS PARA ENVÍO ---
            $table->decimal('weight', 8, 2)->default(0.00)->comment('Peso de la artesanía embalada en KG');
            $table->decimal('length', 8, 2)->default(0.00)->comment('Largo de la artesanía embalada en CM');
            $table->decimal('width', 8, 2)->default(0.00)->comment('Ancho de la artesanía embalada en CM');
            $table->decimal('height', 8, 2)->default(0.00)->comment('Alto de la artesanía embalada en CM');
            // ---------------------------------------------

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