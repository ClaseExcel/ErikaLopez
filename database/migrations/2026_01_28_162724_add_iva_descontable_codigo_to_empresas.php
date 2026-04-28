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
        Schema::table('empresas', function (Blueprint $table) {
            //iva_descontable_codigo_1, iva_descontable_codigo_2, iva_descontable_codigo_3, iva_descontable_codigo_4,
            $table->string('iva_descontable_codigo_1', 30)->nullable()->after('iva_generado_codigo_3');
            $table->string('iva_descontable_codigo_2', 30)->nullable()->after('iva_descontable_codigo_1');
            $table->string('iva_descontable_codigo_3', 30)->nullable()->after('iva_descontable_codigo_2');
            $table->string('iva_descontable_codigo_4', 30)->nullable()->after('iva_descontable_codigo_3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            //
            $table->dropColumn('iva_descontable_codigo_1');
            $table->dropColumn('iva_descontable_codigo_2');
            $table->dropColumn('iva_descontable_codigo_3');
        });
    }
};
