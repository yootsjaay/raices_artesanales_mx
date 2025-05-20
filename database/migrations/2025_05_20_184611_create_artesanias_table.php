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
            $table->decimal('precio',8,2);
            $table->integer('stock')->default(0);
            $table->string('imagen_principal')->nullable;
            $table->string('imagen_adicionales')->nullable;
            $table->string('materiales')->nullable;
            $table->string('dimensiones')->nullable;
            $table->string('historia_piezas')->nullable;
            //Claves Foreaneas

            //Conexion con los artesanos 
            $table->foreignId('artesanos_id')
            ->constrained('artesanos')
            ->onDelete('cascade');
            //conexion con la categoria
            $table->foreignId('categorias_id')
            ->constrained('categorias')
            ->onDelete('cascade');

            //conexion con la ubucacion
            $table->foreignId('ubucacion_id')
            ->constrained('ubicaciones')
            ->OnDelete('set null');

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
