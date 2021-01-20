<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignacionAlumnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignacion_alumnos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('asignacion_id');
            $table->unsignedBigInteger('inscripcion_id');
            $table->decimal('nota',5,2)->nullable(0);
            $table->datetime('fecha_entrega')->nullable();
            $table->boolean('entrega_tarde')->default(0);
            $table->string('adjunto',100)->nullable();
            $table->boolean('entregado')->default(0);
            $table->boolean('calificado')->default(0);
            $table->string('observaciones',500)->nullable();
            $table->datetime('hora_inicio_cuestionario')->nullable();
            $table->timestamps();

            $table->foreign('asignacion_id')->references('id')->on('asignacions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('inscripcion_id')->references('id')->on('inscripciones')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asignacion_alumnos');
    }
}
