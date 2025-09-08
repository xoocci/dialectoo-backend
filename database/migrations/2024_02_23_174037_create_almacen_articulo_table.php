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
        Schema::create('almacen_articulo', function (Blueprint $table) {
            $table->foreignId('idalmacen')->constrained('almacen','idalmacen')->onUpdate('cascade'); 
            $table->string('idarticulo'); // Ajusta el tipo a string
            $table->foreign('idarticulo')->references('idarticulo')->on('articulo')->onUpdate('cascade');
            $table->integer('cantidad');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('almacen_articulo');
    }
};
