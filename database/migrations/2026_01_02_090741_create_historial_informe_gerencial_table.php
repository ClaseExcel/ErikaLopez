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
        Schema::create('historial_informe_gerencial', function (Blueprint $table) {
            $table->id();
            //id_empresa
            $table->unsignedBigInteger('id_empresa');
            $table->foreign('id_empresa')->references('id')->on('empresas')->onDelete('cascade');
            //fecha_inicial
            $table->date('fecha_inicial');
            //fecha_final
            $table->date('fecha_final');    
            //seccion string max 13 characters
            $table->string('seccion', 13);
            //url_imagen
            $table->string('url_imagen')->nullable();
            //descripcion
            $table->text('descripcion')->nullable();
            //observaciones
            $table->text('observaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historial_informe_gerencial');
    }
};
