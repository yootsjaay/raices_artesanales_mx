<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atributo_artesania_variant', function (Blueprint $table) {
    $table->id();
    $table->foreignId('artesania_variant_id')
          ->constrained('artesania_variants')
          ->onDelete('cascade');
    $table->foreignId('atributo_id')
          ->constrained('atributo')
          ->onDelete('cascade');
    $table->string('valor');
    $table->timestamps();

    // Índice único con nombre corto
    $table->unique(['artesania_variant_id', 'atributo_id'], 'atributo_variant_atributo_unique');
});

    }

    public function down(): void
    {
        Schema::dropIfExists('atributo_artesania_variant');
    }
};
