<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialApoyosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_apoyos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('asignar_curso_profesor_id');
            $table->unsignedBigInteger('ciclo_periodo_academico_id');
            $table->string('descripcion',500);
            $table->string('adjunto',100)->nullable();
            $table->boolean('link')->default(0);
            $table->string('url',200)->nullable();

            $table->foreign('asignar_curso_profesor_id')->references('id')->on('asignar_curso_profesor')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ciclo_periodo_academico_id')->references('id')->on('ciclo_periodo_academicos');
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
        Schema::dropIfExists('material_apoyos');
    }
}
