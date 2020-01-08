<?php

use Illuminate\Database\Seeder;
//use App\Inscripcion;
use App\Institucion;

class InstitucionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$data = new Institucion();
        $data = new Institucion;
        $data->definicion = 'Colegio de ciencias comerciales';
        $data->nombre = 'SAN JOSÉ';
        $data->representante_legal = 'Norma Yolanda ozorio';
        $data->municipio_id = 1;
        $data->profesion = 'Perito en administracion de empresas';
        $data->calidad_de = 'Directora educativa';
        $data->estado_civil = 'Casado';
        $data->nacionalidad = 'Guatemalteca';
        $data->fecha_nac = "1980-5-23";
        $data->cui ="2548966180501";
        $data->nit ="168263-3";
        $data->telefono ="7881-1076";
        $data->direccion = 'Avenida del comercio 1-27 zona 1 San Jose Escuintla';
        $data->save();
    }
}
