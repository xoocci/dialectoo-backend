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
        Schema::create('identificadores_articulo', function (Blueprint $table) {
            $table->string('idarticulo'); 
            $table->foreign('idarticulo')->references('idarticulo')->on('articulo')->onUpdate('cascade');
            $table->string('identificador_articulo')->unique();
            $table->string('numero_serie')->unique();
            $table->string('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identificadores_articulo');
    }
};
