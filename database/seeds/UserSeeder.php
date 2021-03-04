<?php

use App\User;
use App\Empleado;
use App\Cargo;
use App\UsuarioEmpleado;
use App\UsuarioAlumno;
use App\UsuarioRepresentante;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //agregado cargos
        $cargo = new Cargo;
        $cargo->nombre = 'director';
        $cargo->save();

        $cargo = new Cargo;
        $cargo->nombre = 'profesor';
        $cargo->save();

        //agregar un empleado, director y profesor

        $empleado = new Empleado;
        $empleado->cui = '1234567891911';
        $empleado->codigo = '2020-001';
        $empleado->primer_nombre = 'Juan';
        $empleado->segundo_nombre = 'Pablo';
        $empleado->primer_apellido = 'Lopez';
        $empleado->segundo_apellido = 'Perez';
        $empleado->fecha_nac = '1995-3-5';
        $empleado->telefono = '55994477';
        $empleado->email = 'secret@secret.com';
        $empleado->direccion = 'escuintla';
        $empleado->cargo_id = 1;
        $empleado->save();

        $empleado = new Empleado;
        $empleado->cui = '1234567891933';
        $empleado->codigo = '2-2020-002';
        $empleado->primer_nombre = 'Erick';
        $empleado->segundo_nombre = 'Fernando';
        $empleado->primer_apellido = 'Montenegro';
        $empleado->segundo_apellido = 'Estrada';
        $empleado->fecha_nac = '1990-3-5';
        $empleado->telefono = '56765456';
        $empleado->email = 'erick@gmail.com';
        $empleado->direccion = 'escuintla';
        $empleado->cargo_id = 2;
        $empleado->save();


        //usuario empleado
        $data = new User();
        $data->email = "secret@secret.com";
        $data->password = bcrypt("secret");
        $data->codigo = "2-2020-001";
        $data->rol_id = 1;
        $data->save();

        $data_empleado = new UsuarioEmpleado;
        $data_empleado->user_id = $data->id;
        $data_empleado->empleado_id = 1;
        $data_empleado->save();

        //usuario empleado profesor
        $data = new User();
        $data->email = "erick@gmail.com";
        $data->password = bcrypt("secret");
        $data->codigo = "2-2020-002";
        $data->rol_id = 5;
        $data->save();

        $data_empleado = new UsuarioEmpleado;
        $data_empleado->user_id = $data->id;
        $data_empleado->empleado_id = 2;
        $data_empleado->save();


    /*    //usuario alumno
        $data = new User();
        $data->email = "marlon@gmail.com";
        $data->password = bcrypt("secret");
        $data->codigo = "1-2020-001";
        $data->rol_id = 6;
        $data->save();

        $data_alumno = new UsuarioAlumno;
        $data_alumno->user_id = $data->id;
        $data_alumno->alumno_id = 1;
        $data_alumno->save();

        //alumno 2
        $data = new User();
        $data->email = "pedro@gmail.com";
        $data->password = bcrypt("secret");
        $data->codigo = "1-2020-001";
        $data->rol_id = 6;
        $data->save();

        $data_alumno = new UsuarioAlumno;
        $data_alumno->user_id = $data->id;
        $data_alumno->alumno_id = 2;
        $data_alumno->save();

        //ususario representante
        $data = new User();
        $data->email = "faustino@gmail.com";
        $data->password = bcrypt("secret");
        $data->codigo = "5568978987999";
        $data->rol_id = 7;
        $data->save();

        $data_representante = new UsuarioRepresentante;
        $data_representante->user_id = $data->id;
        $data_representante->apoderado_id = 1;
        $data_representante->save(); */

    }
}
