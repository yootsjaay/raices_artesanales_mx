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
          Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Clave foránea al usuario

            // Información de contacto
            $table->string('company')->nullable()->comment('Nombre de la empresa (según Envia.com)');
            $table->string('name')->comment('Nombre de la persona de contacto para la dirección');
            $table->string('email')->nullable();
            $table->string('phone')->comment('Número de teléfono para la entrega');

            // Datos de la dirección física
            $table->string('street')->comment('Nombre de la calle');
            $table->string('number')->comment('Número exterior del domicilio');
            $table->string('internal_number')->nullable()->comment('Número interior (si aplica)');
            $table->string('district')->comment('Colonia o barrio');
            $table->string('city')->comment('Ciudad');
            $table->string('state')->comment('Estado (código corto, ej. NL)');
            $table->string('postal_code')->comment('Código postal');
            $table->string('country')->comment('País (código ISO 3166-1 alpha-2, ej. MX)');
            $table->string('phone_code')->nullable()->comment('Código de país del teléfono (ej. MX)');

            // Campos específicos de Envia.com
            $table->integer('category')->nullable()->comment('Categoría de la dirección para Envia.com (ej. 1)');
            $table->string('identification_number')->nullable()->comment('Número de identificación (ej. RFC) para Envia.com');
            $table->text('reference')->nullable()->comment('Referencias adicionales para el repartidor');

            // Campos internos de tu aplicación
            $table->string('type_address')->default('shipping')->comment('Tipo de dirección: shipping, billing, etc.');
            $table->boolean('is_default')->default(false)->comment('Indica si es la dirección predeterminada del usuario');
            $table->timestamps();

            // Índices útiles para búsquedas rápidas
            $table->index(['user_id', 'type_address']);
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
