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
       Schema::create('atributos_artesania_variant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artesania_variant_id')->constrained('artesania_variants')->onDelete('cascade')->comment('Foreign key to the artesania_variants table');
            $table->string('nombre_atributo')->comment('Attribute name (e.g., Embroidery Type, Pattern, Finishing Technique)');
            $table->string('valor_atributo')->comment('Attribute value (e.g., Fine Drawnwork, Geometric, Hand Polished)');
            $table->timestamps();

            // Indexes to optimize searches
            $table->index(['nombre_atributo', 'valor_atributo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atributos_artesania_variant');
    }
};
