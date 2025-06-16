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
       // migration: create_locations_table.php
            Schema::create('locations', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');       // Nombre del local o sucursal
                $table->string('direccion');    // Dirección completa
                $table->string('ciudad')->nullable();
                $table->string('estado')->nullable();
                $table->string('codigo_postal')->nullable();
                $table->string('telefono')->nullable();
                $table->string('email')->nullable();
                $table->timestamps();
            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
