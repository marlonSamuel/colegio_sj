<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlumnoSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumno_series', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('asignacion_alumno_id');
            $table->unsignedBigInteger('serie_id');
            $table->boolean('respondida')->default(0);
            $table->decimal('nota',5,2)->default(0);
            $table->timestamps();

            $table->foreign('asignacion_alumno_id')->references('id')->on('asignacion_alumnos')->onDelete('cascade');
            $table->foreign('serie_id')->references('id')->on('series')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumno_series');
    }
}
