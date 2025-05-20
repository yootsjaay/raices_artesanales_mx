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
        Schema::create('artesanos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('biografica')->nullable;
            $table->string('contacto_email')->unique()->nullable;
            $table->string('contacto_telefono')->nullable;
            $table->string('red_social_facebook')->nullable;
            $table->string('red_social_instagram')->nullable;
            
            $table->foreignId('ubicacion_id')->constrained('ubicaciones')->OnDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artesanos');
    }
};
