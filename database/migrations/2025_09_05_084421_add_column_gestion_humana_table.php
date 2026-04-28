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
        Schema::table('gestion_humana', function (Blueprint $table) {
            $table->text('certificado_ccf')->nullable();
            $table->text('certificado_arl')->nullable();
            $table->text('certificado_afp')->nullable();
            $table->text('ccf')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
