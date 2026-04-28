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
        Schema::table('agenda_empresas', function (Blueprint $table) {
            $table->unsignedBigInteger('agenda_id');
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('agenda_id')->references('id')->on('agenda_empleados');
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agenda_empresas', function (Blueprint $table) {
            //
        });
    }
};
