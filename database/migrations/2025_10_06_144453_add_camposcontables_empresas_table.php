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
            $table->string('tipo')->nullable();
            $table->string('representantelegal')->nullable()->after('Cedula');
            $table->string('contador')->nullable()->after('representantelegal');
            $table->string('firmarepresentante')->default('default.jpg')->after('contador');
            $table->text('actividadeconomica')->nullable()->after('ciiu');
            $table->string('revisorfiscal')->nullable()->after('firmarepresentante');
            $table->string('cedularevisor')->nullable()->after('revisorfiscal');
            $table->string('firmarevisorfiscal')->default('default.jpg')->after('cedularevisor');
            $table->string('logocliente')->default('default.jpg');
            $table->string('gruponiif')->nullable();
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
