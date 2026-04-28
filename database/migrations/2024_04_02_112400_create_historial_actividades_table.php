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
        Schema::create('historial_actividades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reporte_actividades_id');
            $table->foreign('reporte_actividades_id')->references('id')->on('reporte_actividades');
            $table->tinyInteger('estado');
            $table->text('justificacion');
            $table->string('modalidad');
            $table->timestamp('fecha_creacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historial_actividades');
    }
};
