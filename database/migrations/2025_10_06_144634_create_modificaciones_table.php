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
        Schema::create('modificaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('compania_id');
            $table->string('periodo');
            $table->string('movimiento');
            $table->string('valor_ajustado');
            $table->string('saldo_original')->nullable();
            $table->string('campo_modificado')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('compania_id')->references('id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modificaciones');
    }
};
