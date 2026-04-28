<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignRequerimientoIdToSeguimientoRequerimientos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seguimiento_requerimientos', function (Blueprint $table) {
            $table->unsignedBigInteger('requerimiento_id');
            $table->foreign('requerimiento_id')->references('id')->on('requerimientos');
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
