<?php

namespace App\Policies;

use App\User;
use App\Alumno;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlumnoPolicy
{
    use HandlesAuthorization, AdminActions;

    //restringe información para alumno
    public function history(User $user, $id)
    {
        //restringiendo acceso a alumno
        if($user->rol->rol == "alumno")
        {
            $user_info = $user->alumno;
            return $user_info->alumno_id === (int)$id;
        }

        //restringiendo acceso a alumnos representandos 
        if($user->rol->rol == "apoderado")
        {
            $user_info = $user->representante->apoderado;
            $alumnos = $user_info->alumnos;

            return $alumnos->contains('alumno_id', (int)$id);
        }
    }
}
