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
        Schema::create('documentos_actividades', function (Blueprint $table) {
            $table->id();
            $table->text('nombre')->nullable();
            $table->unsignedBigInteger('actividad_id')->nullable();
            $table->foreign('actividad_id')->references('id')->on('actividad_cliente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_actividades');
    }
};
