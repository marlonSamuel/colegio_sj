<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlumnoPreguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumno_preguntas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('alumno_serie_id');
            $table->unsignedBigInteger('pregunta_id');
            $table->decimal('nota',5,2)->default(0);
            $table->timestamps();

            $table->foreign('alumno_serie_id')->references('id')->on('alumno_series')->onDelete('cascade');
            $table->foreign('pregunta_id')->references('id')->on('preguntas')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumno_preguntas');
    }
}
