<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActividadClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividad_cliente', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->unsignedBigInteger('actividad_id');
            $table->string('progreso');
            $table->string('prioridad')->nullable();
            $table->date('fecha_vencimiento');
            $table->string('periodicidad')->nullable()->default(0);
            $table->string('periodicidad_corte')->nullable()->default(0);
            $table->string('recordatorio');
            $table->string('recordatorio_distancia');
            $table->text('nota')->nullable();
            $table->unsignedBigInteger('responsable_id');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('usuario_id');
            $table->timestamps();

            $table->foreign('actividad_id')->references('id')->on('actividad');
            $table->foreign('responsable_id')->references('id')->on('responsable');
            $table->foreign('cliente_id')->references('id')->on('empresas');
            $table->foreign('usuario_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actividad_cliente');
    }
}
