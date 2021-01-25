<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignarcursoProfesorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignar_curso_profesor', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('curso_grad_niv_edu_id');
            $table->unsignedBigInteger('ciclo_id');
            $table->char('jornada',1);
            $table->foreign('curso_grad_niv_edu_id')->references('id')->on('curso_grad_niv_edu');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('ciclo_id')->references('id')->on('ciclos');
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
        Schema::dropIfExists('cursos_grados_niveles_educativos_profesores');
    }
}
