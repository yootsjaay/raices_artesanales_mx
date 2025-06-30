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
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Clave foránea al usuario propietario de la dirección');

            // Información de contacto
            $table->string('company')->nullable()->comment('Nombre de la empresa (opcional)'); // Añadido: campo 'company'
            $table->string('name')->comment('Nombre completo de la persona de contacto para la dirección');
            $table->string('email')->nullable()->comment('Correo electrónico de contacto (opcional)');
            $table->string('phone')->comment('Número de teléfono de contacto para la entrega (ej. 10 dígitos para MX)');

            // Datos de la dirección física
            $table->string('street')->comment('Nombre de la calle');
            $table->string('number')->comment('Número exterior del domicilio');
            $table->string('internal_number')->nullable()->comment('Número interior o de apartamento (si aplica)');
            $table->string('district')->comment('Nombre de la colonia o barrio');
            $table->string('city')->comment('Nombre de la ciudad');
            $table->string('state')->comment('Nombre completo del Estado o abreviatura (ej. NL, Oaxaca)');
            $table->string('postal_code')->comment('Código postal');
            $table->string('country')->comment('País (código ISO 3166-1 alpha-2, ej. MX)');
            $table->string('phone_code')->nullable()->comment('Código de país del teléfono (ej. MX, +52)');

            // Campos específicos para APIs de envío como Envia.com
            $table->integer('category')->nullable()->comment('Categoría de la dirección para la API de envío (ej. 1 para residencial)');
            $table->string('identification_number')->nullable()->comment('Número de identificación fiscal (ej. RFC, CURP) para la API de envío');
            $table->text('reference')->nullable()->comment('Referencias adicionales para el repartidor (ej. "Casa con portón rojo")');

            // Campos internos de tu aplicación para gestión de direcciones
            $table->string('type_address')->default('shipping')->comment('Tipo de dirección: "shipping", "billing", "origin", etc.');
            $table->boolean('is_default')->default(false)->comment('Indica si es la dirección predeterminada del usuario para su tipo');
            $table->timestamps();

            // Índices útiles para búsquedas rápidas y rendimiento
            $table->index(['user_id', 'type_address']);
            $table->index('postal_code'); // Útil para búsquedas o validaciones de CP
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