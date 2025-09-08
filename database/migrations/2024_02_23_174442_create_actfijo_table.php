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
        Schema::create('actfijo', function (Blueprint $table) {
            $table->string('idarticulo')->unique(); 
            $table->foreign('idarticulo')->references('idarticulo')->on('articulo')->onDelete('cascade')->onUpdate('cascade');
            $table->string('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actfijo');
    }
};
