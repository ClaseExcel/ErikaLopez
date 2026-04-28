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
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->integer('numero_cotizacion');
            $table->date('fecha_envio');
            $table->date('fecha_vigencia');
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('empresas');
            $table->unsignedBigInteger('responsable_id');
            $table->foreign('responsable_id')->references('id')->on('users');
            $table->string('nombre_contacto');
            $table->string('telefono_contacto');
            $table->text('servicio_cotizado');
            $table->integer('precio_venta');
            $table->text('observaciones')->nullable();
            $table->string('cotizacion_adjunta')->nullable();
            $table->date('fecha_primer_seguimiento');
            $table->text('observacion_primer_seguimiento');
            $table->string('estado_cotizacion');
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
        Schema::dropIfExists('cotizacions');
    }
};
