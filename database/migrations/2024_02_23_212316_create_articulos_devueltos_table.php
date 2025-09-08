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
        Schema::create('articulos_devueltos', function (Blueprint $table) {
            $table->unsignedBigInteger('idalmacen');
            $table->foreign('idalmacen')->references('idalmacen')->on('almacen')->onUpdate('cascade');
            $table->string('rfc_docente'); 
            $table->foreign('rfc_docente')->references('rfc')->on('empleado')->onUpdate('cascade');
            $table->string('rfc_responsable'); 
            $table->foreign('rfc_responsable')->references('rfc')->on('empleado')->onUpdate('cascade');
            $table->string('idarticulo'); 
            $table->foreign('idarticulo')->references('idarticulo')->on('articulo')->onUpdate('cascade');
            $table->string('identificador_articulo'); 
            $table->foreign('identificador_articulo')->references('identificador_articulo')->on('identificadores_articulo')->onUpdate('cascade');
            $table->date('fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulos_devueltos');
    }
};
