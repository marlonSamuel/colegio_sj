<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioRepresentantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_representantes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('apoderado_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('apoderado_id')->references('id')->on('apoderados')->onUpdate('cascade')->onDelete('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('usuario_representantes');
    }
}
