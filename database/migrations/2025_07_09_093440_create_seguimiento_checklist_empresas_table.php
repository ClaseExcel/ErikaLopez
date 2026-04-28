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
        Schema::create('seguimiento_checklist_empresas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('checklist_empresa_id')->nullable();
            $table->foreign('checklist_empresa_id')->references('id')->on('checklist_empresas');
            $table->json('observaciones')->nullable();
            $table->json('actividades_presentadas')->nullable();
            $table->string('mes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seguimiento_checklist_empresas');
    }
};
