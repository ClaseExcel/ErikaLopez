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
        Schema::create('informesgenericos', function (Blueprint $table) {
            $table->id();
            $table->string('Nit');
            $table->string('cuenta')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('tercero')->nullable();
            $table->string('nombre')->nullable();
            $table->string('saldo_anterior')->nullable();
            $table->string('debitos')->nullable();
            $table->string('creditos')->nullable();
            $table->string('saldo_final')->nullable();
            $table->string('fechareporte')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informesgenericos');
    }
};
