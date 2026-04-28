<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clasificaciones', function (Blueprint $table) {
            $table->id();
            $table->text('anio')->nullable();
            $table->text('regimen_simple_tributacion')->nullable();
            $table->text('ingresos_gravados')->nullable();
            $table->text('ingresos_exentos')->nullable();
            $table->text('ingresos_excluidos')->nullable();
            $table->text('ingresos_no_gravados')->nullable();
            $table->text('devoluciones')->nullable();
            $table->text('total_ingresos')->nullable();
            $table->text('actividad_1')->nullable();
            $table->text('actividad_2')->nullable();
            $table->text('actividad_3')->nullable();
            $table->text('actividad_4')->nullable();
            $table->text('operaciones_excentas')->nullable();
            $table->text('actividades_exp_imp')->nullable();
            $table->text('gran_contribuyente')->nullable();
            $table->text('ingresos_brutos_fiscales_anio_anterior')->nullable();
            $table->text('formato_conciliacion_fiscal')->nullable();
            $table->text('activos_brutos_diciembre_anio_anterior')->nullable();
            $table->text('ingreso_brutos_diciembre_anio_anterior')->nullable();
            $table->text('revisor_fiscal')->nullable();
            $table->text('patrimonio_brutos_diciembre_anio_anterior')->nullable();
            $table->text('ingreso_brutos_tributario_diciembre_anio_anterior')->nullable();
            $table->text('declaracion_tributaria_firma_contador')->nullable();
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clasificaciones');
    }
};
