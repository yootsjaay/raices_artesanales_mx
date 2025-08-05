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
            $table->foreignId('artesania_id')->constrained('artesanias')->onDelete('cascade')->comment('Foreign key to the artesanias table (general product)');

            $table->string('sku')->unique()->comment('Unique SKU code for this specific variant');
            $table->string('variant_name')->nullable()->comment('Descriptive name of the variant (e.g., White Guayabera Size M)');
            $table->text('description_variant')->nullable()->comment('Short specific description of this variant');

            // Fixed and common attributes for variants
            $table->string('size')->nullable()->comment('Size of the variant (e.g., S, M, L, 28, 30)');
            $table->string('color')->nullable()->comment('Color of the variant (e.g., Blue, Red, Natural)');
            $table->string('material_variant')->nullable()->comment('Main material of this variant (e.g., Cotton, Linen, Leather)');

            $table->decimal('precio', 10, 2)->comment('Specific price of this variant');
            $table->integer('stock')->default(0)->comment('Specific inventory for this variant');
            $table->json('imagen_variant')->nullable()->comment('URL of the main image of this variant');
            // --- ADJUSTED SHIPPING FIELDS ---
            $table->foreignId('tipo_embalaje_id')->nullable()->constrained('tipos_embalaje')->onDelete('set null')->comment('ID of the predefined packaging type for this variant');
            $table->decimal('peso_item_kg', 8, 2)->default(0.00)->comment('Weight of the craft item without packaging in KG'); // 

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
