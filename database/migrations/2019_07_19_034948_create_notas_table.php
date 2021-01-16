<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('inscripcion_id');
            $table->unsignedBigInteger('ciclo_periodo_academico_id');
            $table->unsignedBigInteger('asignar_curso_profesor_id');
            $table->decimal('nota',5,2);

            $table->foreign('inscripcion_id')->references('id')->on('inscripciones');
            $table->foreign('ciclo_periodo_academico_id')->references('id')->on('ciclo_periodo_academicos');
            $table->foreign('asignar_curso_profesor_id')->references('id')->on('asignar_curso_profesor');
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
        Schema::dropIfExists('notas');
    }
}
