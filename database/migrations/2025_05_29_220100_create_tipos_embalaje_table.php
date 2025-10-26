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
         Schema::create('tipos_embalaje', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique()->comment('Name of the packaging type (e.g., Envelope, Small Box, Medium Box)');
            $table->text('descripcion')->nullable()->comment('Description of the packaging type');
            $table->decimal('weight', 8, 2)->default(0.00)->comment('Peso de la artesanía embalada en KG');
            $table->decimal('length', 8, 2)->default(0.00)->comment('Largo de la artesanía embalada en CM');
            $table->decimal('width', 8, 2)->default(0.00)->comment('Ancho de la artesanía embalada en CM');
            $table->decimal('height', 8, 2)->default(0.00)->comment('Alto de la artesanía embalada en CM');
            $table->boolean('is_active')->default(true)->comment('Indicates if this packaging type is active for selection');
            $table->unsignedBigInteger('package_envia_id')->nullable()->comment('ID del paquete en Envia.com');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_embalaje');
    }
};
