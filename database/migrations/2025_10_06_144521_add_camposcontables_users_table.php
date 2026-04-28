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
         Schema::table('users', function (Blueprint $table) {
            //agregar columna firma digital y numero tarjeta profesional
            $table->string('tarje_profesional')->default('0')->after('email');
            $table->string('firma')->default('default.jpg')->after('tarje_profesional');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
