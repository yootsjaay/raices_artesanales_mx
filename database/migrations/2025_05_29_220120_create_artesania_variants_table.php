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
        Schema::create('artesania_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artesania_id')
                  ->constrained('artesanias')
                  ->onDelete('cascade')
                  ->comment('Foreign key to the artesanias table (general product)');

            $table->string('sku')->unique()->comment('Unique SKU code for this specific variant');
            $table->string('variant_name')->nullable()->comment('Descriptive name of the variant (e.g., White Guayabera Size M)');

            $table->decimal('precio', 10, 2)->comment('Specific price of this variant');
            $table->integer('stock')->default(0)->comment('Specific inventory for this variant');
            $table->json('imagen_variant')->nullable()->comment('Array of images for this variant');

            // --- SHIPPING / PACKAGING ---
            $table->foreignId('tipo_embalaje_id')
                  ->nullable()
                  ->constrained('tipos_embalaje')
                  ->onDelete('set null')
                  ->comment('ID of the predefined packaging type for this variant');

            $table->decimal('peso_item_kg', 8, 2)->default(0.00)->comment('Weight of the craft item without packaging in KG');

            $table->boolean('is_active')->default(true)->comment('Indicates if the variant is active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artesania_variants');
    }
};
