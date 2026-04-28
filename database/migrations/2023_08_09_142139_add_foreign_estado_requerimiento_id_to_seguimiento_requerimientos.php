<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignEstadoRequerimientoIdToSeguimientoRequerimientos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seguimiento_requerimientos', function (Blueprint $table) {
            $table->unsignedBigInteger('estado_requerimiento_id');
            $table->foreign('estado_requerimiento_id')->references('id')->on('estado_requerimientos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seguimiento_requerimientos', function (Blueprint $table) {
            //
        });
    }
}
