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
        Schema::create('gestion_humana', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->text('documento_cedula')->nullable();
            $table->text('certificado_eps')->nullable();
            $table->text('documento_contrato')->nullable();
            $table->text('nombres')->nullable();
            $table->text('cedula')->nullable();
            $table->text('cargo')->nullable();
            $table->text('salario')->nullable();
            $table->text('auxilio_transporte')->nullable();
            $table->text('bonificacion')->nullable();
            $table->text('direccion')->nullable();
            $table->text('municipio_residencia')->nullable();
            $table->text('telefono')->nullable();
            $table->text('correo_electronico')->nullable();
            $table->text('tipo_contrato')->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->date('fecha_finalizacion')->nullable();
            $table->text('eps')->nullable();
            $table->text('afp')->nullable();
            $table->text('cesantias')->nullable();
            $table->text('arl')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->text('estado_civil')->nullable();
            $table->text('numero_beneficiarios')->nullable();
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
        Schema::dropIfExists('gestion_humana');
    }
};
