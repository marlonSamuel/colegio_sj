<?php

use Illuminate\Database\Seeder;
use App\Ciclo;
use App\TipoPeriodo;
use App\PeriodoAcademico;
use App\CicloPeriodoAcademico;

class CicloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //crear tipos de periodos
        $data = new TipoPeriodo();
        $data->nombre = "Bimestre";
        $data->save();

        //crear periodos academicos
        $data = new PeriodoAcademico();
        $data->nombre = "Primer Bimestre";
        $data->tipo_periodo_id = 1;
        $data->save();

        $data = new PeriodoAcademico();
        $data->nombre = "Segundo Bimestre";
        $data->tipo_periodo_id = 1;
        $data->save();

        $data = new PeriodoAcademico();
        $data->nombre = "Tercer Bimestre";
        $data->tipo_periodo_id = 1;
        $data->save();

        $data = new PeriodoAcademico();
        $data->nombre = "Cuarto Bimestre";
        $data->tipo_periodo_id = 1;
        $data->save();

        $data = new Ciclo();
        $data->ciclo = 2021;
        $data->actual = true;
        $data->inicio = '2021-01-08';
        $data->fin = '2021-10-24';
        $data->save();


      /*  $data = new CicloPeriodoAcademico();
        $data->ciclo_id = 1;
        $data->periodo_academico_id = 1;
        $data->inicio = '2020-01-08';
        $data->fin = '2020-03-08';
        $data->actual = 1;
        $data->save();

        $data = new CicloPeriodoAcademico();
        $data->ciclo_id = 1;
        $data->periodo_academico_id = 2;
        $data->actual = 0;
        $data->inicio = '2020-03-09';
        $data->fin = '2020-05-12';
        $data->save();

        $data = new CicloPeriodoAcademico();
        $data->ciclo_id = 1;
        $data->periodo_academico_id = 3;
        $data->inicio = '2020-05-13';
        $data->fin = '2020-07-13';
        $data->actual = 0;
        $data->save();

        $data = new CicloPeriodoAcademico();
        $data->ciclo_id = 1;
        $data->periodo_academico_id = 4;
        $data->inicio = '2020-07-14';
        $data->fin = '2020-24-10';
        $data->actual = 0;
        $data->save();*/
    }
}
