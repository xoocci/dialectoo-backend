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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->string('idusuario')->primary();
            $table->string('rfc');
            $table->foreign('rfc')->references('rfc')->on('empleado')->onUpdate('cascade');
            $table->string('password');
            $table->string('rol');
            $table->string('restart');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
