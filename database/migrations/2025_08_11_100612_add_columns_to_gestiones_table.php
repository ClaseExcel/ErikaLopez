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
        Schema::table('gestiones', function (Blueprint $table) {
            $table->longText('asistentes_cliente')->nullable();
            $table->longText('asistentes_help')->nullable();
            $table->longText('compromisos_cliente')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gestiones', function (Blueprint $table) {
            //
        });
    }
};
