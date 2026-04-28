<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignEstadoActividadIdToReporteActividades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reporte_actividades', function (Blueprint $table) {
            $table->unsignedBigInteger('estado_actividad_id');
            $table->foreign('estado_actividad_id')->references('id')->on('estado_actividades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reporte_actividades', function (Blueprint $table) {
            //
        });
    }
}
