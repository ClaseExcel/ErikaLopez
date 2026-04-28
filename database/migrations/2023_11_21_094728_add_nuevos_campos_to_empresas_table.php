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
            $table->after('tipocliente', function (Blueprint $table) {
                $table->string('Cedula')->nullable();
                $table->text('ciiu')->nullable();
                $table->integer('dv')->nullable();
                $table->string('contrasenadian')->nullable();
                $table->string('preguntadian')->nullable();
                $table->string('firmadian')->nullable();
                $table->string('camaracomercioclaveportal')->nullable();
                $table->string('firmacamaracomercio', 500)->nullable();
                $table->text('icaclaveportal')->nullable();
                $table->string('arl')->nullable();
                $table->string('clavearl')->nullable();
                $table->string('aportes')->nullable();
                $table->string('ccf')->nullable();
                $table->string('usuario_clave_eps')->nullable();
                $table->string('usuario_clave_ugpp')->nullable();
                $table->string('usuario_fac_nomina')->nullable();
                $table->string('clave_fact_nomina')->nullable();
                $table->string('usuario_sistema_contable')->nullable();
                $table->string('clave_sistema_contable')->nullable();
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
