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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            // Si solo usuarios logeados pueden comentar:
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // O si también permites comentarios de invitados (sin user_id):
            // $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

            $table->foreignId('artesania_id')->constrained()->onDelete('cascade');
            $table->text('content'); // El texto del comentario
            $table->unsignedTinyInteger('rating')->nullable(); // Opcional: para calificación de 1 a 5 estrellas
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Para moderación
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
