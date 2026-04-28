<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarioTributarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendario_tributario', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo_tributario')->nullable();
            $table->integer('detalle_tributario')->nullable();
            $table->string('ultimos_digitos')->nullable();
            $table->string('ultimo_digito')->nullable();
            $table->integer('rango_inicial')->nullable();
            $table->integer('rango_final')->nullable();
            $table->string('fecha_vencimiento')->nullable();
            $table->string('codigo_municipio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendario_tributario');
    }
}
