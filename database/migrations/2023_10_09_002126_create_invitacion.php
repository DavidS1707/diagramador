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
        Schema::create('invitaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_envia_id');
            $table->unsignedBigInteger('usuario_recibe_id');
            $table->unsignedBigInteger('diagrama_id');
            $table->boolean('aceptada')->default(false);
            $table->timestamps();

            $table->foreign('usuario_envia_id')->references('id')->on('users');
            $table->foreign('usuario_recibe_id')->references('id')->on('users');
            $table->foreign('diagrama_id')->references('id')->on('diagrama_secuencias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitacion');
    }
};
