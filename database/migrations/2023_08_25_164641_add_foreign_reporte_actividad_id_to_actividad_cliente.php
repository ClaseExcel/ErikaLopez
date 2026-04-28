<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignReporteactividadIdToActividadCliente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('actividad_cliente', function (Blueprint $table) {
            $table->unsignedBigInteger('reporte_actividad_id');
            $table->foreign('reporte_actividad_id')->references('id')->on('reporte_actividades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('actividad_cliente', function (Blueprint $table) {
            //
        });
    }
}
