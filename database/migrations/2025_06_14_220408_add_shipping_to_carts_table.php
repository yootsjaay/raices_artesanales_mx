<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('shipping_service_id')
                ->nullable()
                ->constrained('shipping_services')
                ->onDelete('set null');

            $table->decimal('shipping_cost', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['shipping_service_id']);
            $table->dropColumn(['shipping_service_id', 'shipping_cost']);
        });
    }
};
