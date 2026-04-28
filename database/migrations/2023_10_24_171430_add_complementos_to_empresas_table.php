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
        Schema::table('empresas', function (Blueprint $table) {
             // *** Se agregan campos de archivos adjuntos
             $table->after('frecuencia_id', function (Blueprint $table) {
                $table->string('rut')->nullable();
                $table->json('obligaciones')->nullable();
                $table->json('obligacionesmunicipales')->nullable();
                $table->string('camaracomercio_id')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas', function (Blueprint $table) {
            //
        });
    }
};
