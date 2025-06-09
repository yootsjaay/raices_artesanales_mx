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
         Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nombre de quien recibe
            $table->string('phone'); // Teléfono de contacto
            $table->string('address1'); // Calle y número
            $table->string('address2')->nullable(); // Colonia, complemento, etc.
            $table->string('country_code', 2); // MX
            $table->string('postal_code', 10);
            $table->string('state'); // area_level1
            $table->string('city'); // area_level2
            $table->string('colony'); // area_level3
            $table->string('company')->nullable(); // Opcional
            $table->string('reference')->nullable(); // Opcional, como "Casa azul con reja blanca"
            $table->boolean('is_default')->default(false); // Si quieres una dirección por defecto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
