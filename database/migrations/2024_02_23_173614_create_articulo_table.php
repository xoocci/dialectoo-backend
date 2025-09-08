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
        Schema::create('articulo', function (Blueprint $table) {
            $table->string('idarticulo')->primary();
            $table->string('nombre');
            $table->string('descripcion');
            $table->string('marca');
            $table->string('color');
            $table->integer('stock');
            $table->integer('stockmin');
            $table->integer('stockmax');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulo');
    }
};
