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
        Schema::create('clientesmovimientos', function (Blueprint $table) {
            $table->id();
            //movimientos siigo local
            $table->string('Nit');
            $table->string('cuenta_descripcion')->nullable();
            $table->string('cuenta')->nullable();
            $table->string('descripcionct')->nullable();
            $table->string('saldoinicial')->nullable();
            $table->string('comprobante')->nullable();
            $table->string('fecha')->nullable();
            $table->string('nit_sl')->nullable();
            $table->string('nombre')->nullable();
            $table->string('descripcion',1000)->nullable();
            $table->string('inventario_cruce_cheque')->nullable();
            $table->string('base')->nullable();
            $table->string('cc_scc')->nullable();
            $table->string('debitos')->nullable();
            $table->string('creditos')->nullable();
            $table->string('saldo_mov')->nullable();
            $table->string('observacion_sl',1000)->nullable();
            $table->string('fecha_reporte')->nullable();
            //movimientos siigo web
            $table->string('codigo_contable_sw')->nullable();
            $table->string('cuenta_contable_sw')->nullable();
            $table->string('comprobante_sw')->nullable();
            $table->string('secuencia_sw')->nullable();
            $table->string('fecha_elaboracion_sw')->nullable();
            $table->string('identificacion_sw')->nullable();
            $table->string('suc_sw')->nullable();
            $table->string('nombre_tercero_sw')->nullable();
            $table->string('descripcion_sw')->nullable();
            $table->string('detalle_sw')->nullable();
            $table->string('centro_costo_sw')->nullable();
            $table->string('saldo_inicial_sw')->nullable();
            $table->string('debito_sw')->nullable();
            $table->string('credito_sw')->nullable();
            $table->string('saldo_movimiento_sw')->nullable();
            $table->string('salto_total_cuenta_sw')->nullable();
            $table->string('fecha_reporte_sw')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientesmovimientos');
    }
};
