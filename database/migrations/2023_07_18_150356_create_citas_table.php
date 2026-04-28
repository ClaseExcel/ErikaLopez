<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Cita;

class CreateCitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->string('motivo');
            $table->string('direccion')->nullable();
            $table->string('link')->nullable();
            $table->string('observacion', 800)->nullable();
            $table->enum('estados', [Cita::vigente, Cita::reservado, Cita::cancelado])->default(Cita::vigente);
            $table->datetime('fecha_inicio');
            $table->datetime('fecha_fin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('citas');
    }
}
