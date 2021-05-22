<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\User;
use App\Empleado;
use App\UsuarioEmpleado;
use App\UsuarioAlumno;
use App\UsuarioRepresentante;
use App\ApoderadoAlumno;
use App\TelefonoApoderado;
use App\Alumno;
use App\Apoderado;
use Illuminate\Support\Facades\DB;

class AlumnoImport implements ToCollection
{
    /**
    * @param Collection $collection

    */
    public function collection(Collection $collection)
    {
    	$c = 1;
    	DB::beginTransaction();
        foreach ($collection as $row) {
            $alumno = new Alumno;
            $alumno->codigo = '1-'.'2021-'.$c;
            $alumno->primer_nombre = $row[1];
            $alumno->segundo_nombre = $row[2];
            $alumno->tercer_nombre = $row[3];
            $alumno->primer_apellido = $row[4];
            $alumno->segundo_apellido = $row[5];
            $alumno->fecha_nac = '2021-01-01';
            $alumno->genero = $row[7];
            $alumno->telefono = $row[8];
            $alumno->email = $row[9];
            $alumno->direccion = $row[10];


            $exists_alumno = Alumno::where('primer_nombre',$row[1])->where('primer_apellido',$row[4])->first();

            if(!is_null($exists_alumno)){
                $exists_alumno->codigo = $alumno->codigo;
                $exists_alumno->save();

                $user = UsuarioAlumno::where('alumno_id',$exists_alumno->id)->with('user')->first();

                $password_a = strtoupper($alumno->primer_apellido).'2021';
                $user->user->codigo = $alumno->codigo;
                $user->user->password = bcrypt($password_a);
                $user->user->save();

                echo "alumno ".$alumno->codigo." actualizado\n";
                $c++;
                continue;
            }


            $alumno->save();

            echo "alumno ".$c." creado\n";

            $user= new User;
            $password_a = strtoupper($alumno->primer_apellido).'2021';
	        $user->email = $alumno->email;
	        $user->password = bcrypt($password_a);
	        $user->codigo = $alumno->codigo;
	        $user->rol_id = 6;
	        $user->save();

	        $data_alumno = new UsuarioAlumno;
	        $data_alumno->user_id = $user->id;
	        $data_alumno->alumno_id = $alumno->id;
	        $data_alumno->save();

	        echo "usuario para alumno ".$c." creado\n";

	        $cui = str_replace(' ', '', $row[13]);
            $apoderado = Apoderado::where('cui',$cui)->first();

            if(is_null($apoderado) && $cui != "" && !is_null($cui)){
            	$apoderado = new Apoderado;
            	$apoderado->cui = $cui;
            	$apoderado->nit = $row[14];
            	$apoderado->municipio_id = 66;
            	$apoderado->primer_nombre = $row[16];
            	$apoderado->segundo_nombre = $row[17];
            	$apoderado->primer_apellido = $row[18];
            	$apoderado->segundo_apellido = $row[19];
            	$apoderado->email = $row[18];
            	$apoderado->fecha_nac = $row[19];
            	$apoderado->ocupacion = $row[20];
            	$apoderado->direccion = $row[21];
            	$apoderado->nacionalidad = "GUATEMALTECO(A)";
            	$apoderado->estado_civil = "CASADO(A)";

            	$apoderado->save();

            	echo "apoderado para alumno ".$c." creado\n";

            	$user_apoderado = new User;
		        //$user_apoderado->email = $apoderado->email;
		        $user_apoderado->password = bcrypt($apoderado->cui);
		        $user_apoderado->codigo = $apoderado->cui;
		        $user_apoderado->rol_id = 7;
		        $user_apoderado->save();

		        $data_representante = new UsuarioRepresentante;
		        $data_representante->user_id = $user_apoderado->id;
		        $data_representante->apoderado_id = $apoderado->id;
		        $data_representante->save();

		        echo "usuario para apoderado de alumno ".$c." creado\n";

		        $telefono = str_replace('-', '', $row[8]);
		        $telefonos = explode("/",$telefono);

		        foreach ($telefonos as $t) {
			        TelefonoApoderado::create([
			            'tipo_telefono' => 'P',
			            'telefono' => $t,
			            'apoderado_id' => $apoderado->id
			        ]);
		        }


		        $apoderado_alumno = new Apoderadoalumno;

		        ApoderadoAlumno::create([
		            'apoderado_id' => $apoderado->id,
		            'alumno_id' => $alumno->id,
		            'responsable' => true,
		            'tipo_apoderado' => 'P'
		        ]);

		        echo "alumno y apoderado ".$c." asignados\n";

            }elseif (!is_null($apoderado)) {
            	$apoderado_alumno = new Apoderadoalumno;

		        ApoderadoAlumno::create([
		            'apoderado_id' => $apoderado->id,
		            'alumno_id' => $alumno->id,
		            'responsable' => true,
		            'tipo_apoderado' => 'P'
		        ]);

		        echo "alumno y apoderado ".$c." asignados\n";
            }

            $c++;
            echo "=================================================================================================\n\n";
        }

        DB::commit();
    }
}
