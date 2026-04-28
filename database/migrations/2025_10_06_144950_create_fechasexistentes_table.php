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
        Schema::create('fechas_existentes_i_c_s', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_creacion')->nullable();
            $table->unsignedBigInteger('user_crea_id')->nullable();
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fechasexistentes');
    }
};
