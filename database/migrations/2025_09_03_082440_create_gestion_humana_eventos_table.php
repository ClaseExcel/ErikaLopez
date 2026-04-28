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
        Schema::create('gestion_humana_eventos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gestion_humana_id');
            $table->unsignedBigInteger('concepto_id');
            $table->foreign('gestion_humana_id')->references('id')->on('gestion_humana');
            $table->foreign('concepto_id')->references('id')->on('conceptos');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->longText('observacion')->nullable();
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
        Schema::dropIfExists('gestion_humana_eventos');
    }
};
