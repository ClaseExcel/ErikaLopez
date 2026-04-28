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
        Schema::create('uvt', function (Blueprint $table) {
            $table->id();
            $table->text('anio')->nullable();
            $table->integer('valor_pesos')->nullable();
            $table->integer('salario_minimo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uvt');
    }
};
