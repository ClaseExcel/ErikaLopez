<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fechas_detalles_tributario', function (Blueprint $table) {
            $table->id(); 
            $table->string('fecha_vencimiento')->nullable();
            $table->string('nombre')->nullable();
            $table->string('codigo_otraentidad')->nullable();
            $table->string('codigo_tributario')->nullable();
            $table->string('NIT')->nullable();
            $table->date('fecha_revision')->nullable();
            $table->string('observacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fechas_detalles_tributario');
    }
};
