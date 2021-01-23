<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignacions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('asignar_curso_profesor_id');
            $table->unsignedBigInteger('ciclo_periodo_academico_id');
            $table->boolean('cuestionario')->default(0);
            $table->decimal('nota',5,2);
            $table->string('titulo',50);
            $table->string('descripcion',500);
            $table->date('fecha_entrega');
            $table->date('fecha_habilitacion');
            $table->integer('tiempo')->default(0);
            $table->boolean('entrega_tarde')->default(0);
            $table->string('adjunto',100)->nullable();
            $table->boolean('flag_tiempo')->default(0);

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
        Schema::dropIfExists('asignacions');
    }
}
