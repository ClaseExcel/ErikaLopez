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
        Schema::create('contapyme_balance', function (Blueprint $table) {
            $table->id();
            $table->string('Nit');
            $table->string('CodCuentaNivel1')->nullable();
            $table->string('CodCuentaNivel2')->nullable();
            $table->string('CodCuentaNivel3')->nullable();
            $table->string('CodCuentaNivel4')->nullable();
            $table->string('CodCuentaNivel5')->nullable();
            $table->string('SaldoInicialDet5')->nullable();
            $table->string('TotalDebitosDet5')->nullable();
            $table->string('TotalCreditosDet5')->nullable();
            $table->string('SaldoFinalDet5')->nullable();
            $table->string('Cuenta_o_Tercero')->nullable();
            $table->string('SaldoInicialDet')->nullable();
            $table->string('TotalDebitosDet')->nullable();
            $table->string('TotalCreditosDet')->nullable();
            $table->string('SaldoFinalDet')->nullable();
            $table->string('fechareporte')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contapyme_balance');
    }
};
