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
        Schema::create('municipiosciudades', function (Blueprint $table) {
            $table->id();
            $table->string('region');
            $table->string('c_digo_dane_del_departamento');
            $table->string('departamento');
            $table->string('c_digo_dane_del_municipio');
            $table->string('municipio');
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
        Schema::dropIfExists('municipiosciudades');
    }
};
