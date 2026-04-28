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
        Schema::create('gestiones', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_visita');
            $table->longText('tipo_visita');
            $table->longText('detalle_visita');
            $table->longText('compromisos')->nullable();
            $table->longText('hallazgos')->nullable();
            $table->string('documento_uno')->nullable();
            $table->string('documento_dos')->nullable();
            $table->string('documento_tres')->nullable();
            $table->string('documento_cuatro')->nullable();
            $table->string('documento_cinco')->nullable();
            $table->string('documento_seis')->nullable();
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
        Schema::dropIfExists('gestiones');
    }
};
