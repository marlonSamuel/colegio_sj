<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRespuestaAlumnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respuesta_alumnos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('asignacion_alumno_id');
            $table->unsignedBigInteger('respuesta_id');
            $table->boolean('correcto');
            $table->decimal('resultado',5,2);
            $table->string('descripcion',500);
            $table->timestamps();

            $table->foreign('asignacion_alumno_id')->references('id')->on('asignacion_alumnos')->onUpdate('cascade');
            $table->foreign('respuesta_id')->references('id')->on('respuestas')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('respuesta_alumnos');
    }
}
