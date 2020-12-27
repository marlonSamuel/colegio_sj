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
            $table->unsignedBigInteger('asignar_curso_prof_sec_id');
            $table->boolean('cuestionario')->default(0);
            $table->decimal('nota',5,2);
            $table->string('descripcion',500);
            $table->date('fecha_entrega');
            $table->date('fecha_habilitacion');
            $table->integer('tiempo')->nullable();
            $table->boolean('entrega_tarde')->default(0);
            $table->string('adjunto',100)->nullable();

            $table->foreign('asignar_curso_prof_sec_id')->references('id')->on('asignar_curso_prof_sec')->onUpdate('cascade')->onDelete('cascade');
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
