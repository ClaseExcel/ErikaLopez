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
         Schema::create('agrupaciones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable(); // Ejemplo: '3'
            $table->string('descripcion');
            $table->text('mensaje')->nullable(); // mensaje predeterminado
            $table->string('gruponiif')->nullable(); // Ejemplo: '3'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agrupaciones');
    }
};
