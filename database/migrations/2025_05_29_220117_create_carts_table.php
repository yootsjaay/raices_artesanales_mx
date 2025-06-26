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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Puede ser nulo para invitados
            // --- CAMPOS AGREGADOS/AJUSTADOS PARA ENVÍO ---
            $table->foreignId('shipping_service_id')->nullable()->constrained('shipping_services')->onDelete('set null');
            $table->decimal('shipping_cost', 10, 2)->default(0.00);
            // ---------------------------------------------

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
