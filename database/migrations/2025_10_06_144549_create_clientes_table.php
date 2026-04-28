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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('Nit');
            $table->string('grupo')->nullable();
            $table->string('cuenta')->nullable();
            $table->string('subcuenta')->nullable();
            $table->string('auxiliar')->nullable();
            $table->string('subauxiliar')->nullable();
            $table->string('sucursal')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('ultimo_movimiento')->nullable();
            $table->string('saldo_anterior')->nullable();
            $table->string('debitos')->nullable();
            $table->string('creditos')->nullable();
            $table->string('nuevo_saldo')->nullable();
            $table->string('fechareporte')->nullable();
            $table->string('nivel_ga')->nullable();
            $table->string('transacional_ga')->nullable();
            $table->string('codigo_cuenta_contable_ga')->nullable();
            $table->string('nombre_cuenta_contable_ga')->nullable();
            $table->string('saldo_inicial_ga')->nullable();
            $table->string('movimiento_debito_ga')->nullable();
            $table->string('movimiento_credito_ga')->nullable();
            $table->string('saldo_final_ga')->nullable();
            $table->string('fechareporte_ga')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
