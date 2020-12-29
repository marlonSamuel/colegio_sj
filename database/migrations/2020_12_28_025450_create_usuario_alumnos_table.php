<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioAlumnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_alumnos', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('alumno_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('alumno_id')->references('id')->on('alumnos')->onUpdate('cascade')->onDelete('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_alumnos');
    }
}
