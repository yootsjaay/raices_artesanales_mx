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
            $table->text('descripcion');
            $table->decimal('precio', 8, 2);
            $table->integer('stock')->default(0);
            $table->string('imagen_principal')->nullable(); // ¡CORREGIDO!
            $table->string('imagen_adicionales')->nullable(); // ¡CORREGIDO!
            $table->string('materiales')->nullable(); // ¡CORREGIDO!
            $table->string('dimensiones')->nullable(); // 
            $table->string('historia_piezas')->nullable(); // ¡CORREGIDO!
            $table->string('tecnica_empleada')->nullable();

            // Claves Foráneas
            // Conexión con los artesanos
            $table->foreignId('artesano_id') // : artesanos_id a artesano_id!
                  ->constrained('artesanos')
                  ->onDelete('cascade'); // 

            // Conexión con la categoria
            $table->foreignId('categoria_id') // categorias_id a categoria_id!
                  ->constrained('categorias')
                  ->onDelete('cascade'); //

            //conexion con la ubicacion
            $table->foreignId('ubicacion_id')
                  ->nullable() // 
                  ->constrained('ubicaciones')
                  ->onDelete('set null');


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