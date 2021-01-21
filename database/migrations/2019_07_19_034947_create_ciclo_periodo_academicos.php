<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCicloPeriodoAcademicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciclo_periodo_academicos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ciclo_id');
            $table->unsignedBigInteger('periodo_academico_id');
            $table->date('inicio');
            $table->date('fin');
            $table->boolean('actual');
            $table->decimal('nota');
            $table->timestamps();
            $table->foreign('ciclo_id')->references('id')->on('ciclos')->onUpdate('cascade')->onDelete('cascade')->onDelete('cascade');
            $table->foreign('periodo_academico_id')->references('id')->on('periodos_academicos')->onUpdate('cascade')->onDelete('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ciclo_periodo_academicos');
    }
}
