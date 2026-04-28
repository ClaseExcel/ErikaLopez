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
        Schema::table('fechas_municipales_ct', function (Blueprint $table) {
            $table->string('correo')->nullable();
        });
    }

    public function down()
    {
        Schema::table('fechas_municipales_ct', function (Blueprint $table) {
            $table->dropColumn('correo');
        });
    }

};
