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
        Schema::table('actividad_cliente', function (Blueprint $table) {
            $table->after('empresa_asociada_id', function (Blueprint $table) {
                $table->unsignedBigInteger('user_crea_act_id')->nullable();
                $table->unsignedBigInteger('user_update_act_id')->nullable();
                $table->foreign('user_crea_act_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('user_update_act_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::table('actividadcliente', function (Blueprint $table) {
            //
        });
    }
};
