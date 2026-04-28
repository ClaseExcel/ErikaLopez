<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileToActividadClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('actividad_cliente', function (Blueprint $table) {
            // *** Se agregan campos de archivos adjuntos
            $table->after('usuario_id', function (Blueprint $table) {
                $table->string('file_documento_1')->nullable();
                $table->string('file_documento_2')->nullable();
                $table->string('file_documento_3')->nullable();
                $table->string('file_documento_4')->nullable();
                $table->string('file_documento_5')->nullable();
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
        Schema::table('actividad_cliente', function (Blueprint $table) {
            //
        });
    }
}
