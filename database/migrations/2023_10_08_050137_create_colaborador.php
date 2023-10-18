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
        Schema::create('colaborador', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Columna para la clave foránea a 'users'
            $table->unsignedBigInteger('diagrama_secuencia_id'); // Columna para la clave foránea a 'diagrama_secuencias'
            $table->timestamps();

            // Claves foráneas
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('diagrama_secuencia_id')->references('id')->on('diagrama_secuencias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colaborador');
    }
};
