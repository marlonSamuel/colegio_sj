<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlumnoRespuestasTable extends Migration
{
    /**
     * Run the migrations. respuesta_alumno
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumno_respuestas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('alumno_pregunta_id');
            $table->unsignedBigInteger('respuesta_id');
            $table->boolean('correcto')->default(0);
            $table->boolean('correcto_alumno')->default(0); //respuesta que el alumno considera correcta
            $table->decimal('nota',5,2)->default(0);
            $table->string('respuesta',500)->nullable();
            $table->timestamps();

            $table->foreign('alumno_pregunta_id')->references('id')->on('alumno_preguntas')->onDelete('cascade');
            $table->foreign('respuesta_id')->references('id')->on('respuestas')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumno_respuestas');
    }
}
