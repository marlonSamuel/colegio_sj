<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::name('login')->post('auth/login', 'Usuario\AuthController@login');
Route::name('logout')->get('auth/logout', 'Usuario\AuthController@logout');

Route::resource('usuarios', 'Usuario\UsuarioController', ['except' => ['create', 'edit']]);
Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

Route::name('me')->get('auth/me', 'Usuario\AuthController@user');
Route::name('cambiar_contraseña')->post('usuarios_change_password', 'Usuario\UsuarioController@changePassword');
Route::resource('rols', 'Rol\RolController', ['except' => ['create', 'edit']]);

Route::resource('cursos', 'Curso\CursoController', ['except' => ['create', 'edit']]);
Route::resource('grados', 'Grado\GradoController', ['except' => ['create', 'edit']]);
Route::resource('niveles_educativos', 'NivelEducativo\NivelEducativoController', ['except' => ['create', 'edit']]);
Route::resource('niveles_educativos.grados', 'NivelEducativo\NivelEducativoGradoController', ['except' => ['create', 'edit']]);
Route::resource('secciones', 'Seccion\SeccionController', ['except' => ['create', 'edit']]);
Route::resource('cargos', 'Cargo\CargoController', ['except' => ['create', 'edit']]);
Route::resource('empleados', 'Empleado\EmpleadoController', ['except' => ['create', 'edit']]);

Route::resource('gradoNivelEducativos', 'GradoNivelEducativo\GradoNivelEducativoController', ['except' => ['create', 'edit']]);
Route::resource('gradoNivelEducativosCursos', 'GradoNivelEducativo\CursoGradoNivelController', ['except' => ['create', 'edit']]);
Route::resource('gradoNivelEducativosSecciones', 'GradoNivelEducativo\GradoNivelEducativoSecController', ['except' => ['create', 'edit']]);
Route::resource('gradoNivelEducativosInscripciones', 'GradoNivelEducativo\GradoNivelEducativoInscripcionController', ['except' => ['create', 'edit']]);
Route::name('gradoNivelEducativosGetSecciones')->get('gradoNivelEducativosGetSecciones/{id}', 'GradoNivelEducativo\GradoNivelEducativoController@secciones');

Route::resource('ciclos', 'Ciclo\CicloController', ['except' => ['create', 'edit']]);
Route::resource('concepto_pagos', 'ConceptoPago\ConceptoPagoController', ['except' => ['create', 'edit']]);
Route::resource('cuotas', 'Cuota\CuotaController', ['except' => ['create', 'edit']]);
Route::resource('concepto_pagos.cuotas', 'Cuota\CuotaController', ['except' => ['create', 'edit']]);
Route::resource('alumnos', 'Alumno\AlumnoController', ['except' => ['create', 'edit']]);
Route::name('alumnos')->get('alumnos_search/{search?}', 'Alumno\AlumnoController@searchQuery');
Route::name('alumnos_historial')->get('alumnos_historial', 'Alumno\AlumnoController@historialAlumnos');
Route::name('alumnos_historial_alumno')->get('alumnos_historial_alumno/{id}', 'Alumno\AlumnoController@historialAlumno');
Route::name('alumnos_last_row')->get('alumnos_last_row', 'Alumno\AlumnoController@lastRow');

Route::resource('apoderados', 'Apoderado\ApoderadoController', ['except' => ['create', 'edit']]);
Route::resource('alumnos.apoderados', 'Alumno\AlumnoApoderadoAlumnoController', ['except' => ['create', 'edit']]);
Route::resource('alumnos.inscripciones', 'Alumno\AlumnoInscripcionController', ['except' => ['create', 'edit']]);
Route::resource('apoderado_alumnos', 'Alumno\ApoderadoAlumnoController', ['except' => ['create', 'edit']]);
Route::resource('apoderados.alumnos', 'Apoderado\ApoderadoApoderadoAlumnoController', ['except' => ['create', 'edit']]);
Route::resource('telefono_apoderados', 'TelefonoApoderado\TelefonoApoderadoController', ['except' => ['create', 'edit']]);
Route::resource('apoderado.telefonos', 'Apoderado\ApoderadoTelefonoApoderadoController', ['except' => ['create', 'edit']]);
Route::resource('inscripciones', 'Inscripcion\InscripcionController', ['except' => ['create', 'edit']]);
Route::name('ciclo_actual')->get('inscripciones_ciclo_actual', 'Inscripcion\InscripcionController@getCicloActual');
Route::name('ciclo_actual')->get('inscripciones_contrato/{id}', 'Inscripcion\InscripcionController@getContrato');
Route::resource('municipios', 'Municipio\MunicipioController', ['except' => ['create', 'edit']]);
Route::resource('gradoNivelEducativos.cuotas', 'GradoNivelEducativo\GradoNivelEducativoCuotaController', ['except' => ['create', 'edit']]);
Route::resource('pagos', 'Pago\PagoController', ['except' => ['create', 'edit']]);
Route::resource('inscripciones.pagos', 'Inscripcion\InscripcionPagoController', ['except' => ['create', 'edit']]);
Route::resource('pagos.pagos_parciales', 'Pago\PagoPagoParcialController', ['except' => ['create', 'edit']]);
Route::resource('pagos_parciales', 'Pago\PagoParcialController', ['except' => ['create', 'edit']]);
Route::resource('meses', 'Mes\MesController', ['except' => ['create', 'edit']]);
Route::name('pagos_comprobante')->get('pagos_comprobante/{id}', 'Pago\PagoController@comprobante');
Route::name('pagos_parciales_comprobante')->get('pagos_parciales_comprobante/{id}', 'Pago\PagoParcialController@comprobante');
Route::resource('serie_facturas', 'SerieFactura\SerieFacturaController', ['except' => ['create', 'edit']]);
Route::name('serie_facturas_activa')->get('serie_facturas_activa', 'SerieFactura\SerieFacturaController@serieActiva');

Route::name('gradoNivelEducativosCiclo')->get('gradoNivelEducativosCiclo/{id}/{ciclo_id}', 'GradoNivelEducativo\GradoNivelEducativoCuotaController@getByCiclo');

Route::name('ciclos_get_data')->get('ciclos_get_data/{id}', 'Ciclo\CicloController@getDataCiclo');
Route::name('ciclos_actual')->get('ciclos_actual', 'Ciclo\CicloController@getCicloActual');
Route::name('inscripciones_reporte')->get('inscripciones_reporte/{inicio?}/{fin?}/{grado?}', 'Inscripcion\InscripcionController@getByFechas');
Route::name('pagos_reporte')->get('pagos_reporte/{inicio?}/{fin?}', 'Pago\PagoController@getByFechas');
Route::resource('ciclos.inscripciones', 'Ciclo\CicloInscripcionController', ['except' => ['create', 'edit']]);
Route::resource('ciclos.pagos', 'Ciclo\CicloPagoController', ['except' => ['create', 'edit']]);
Route::name('inscripciones_documento')->post('inscripciones_documento', 'Inscripcion\InscripcionController@documento');
Route::resource('rols.menus', 'Rol\RolMenuController', ['except' => ['create', 'edit']]);
Route::resource('periodos_academicos', 'PeriodoAcademico\PeriodoAcademicoController', ['except' => ['create', 'edit']]);
Route::resource('asignar_cursos_profesores', 'AsignarCursoProfesor\AsignarCursoProfesorController', ['except' => ['create', 'edit']]);
Route::name('asignar_cursos_profesores_info')->get('asignar_cursos_profesores_info','AsignarCursoProfesor\AsignarCursoProfesorController@cursoGradoNivel');

Route::name('asignar_cursos_profesores_get_all')->get('asignar_cursos_profesores_get_all/{profesor_id}/{ciclo_id}','AsignarCursoProfesor\AsignarCursoProfesorController@getAll');
Route::name('asignar_cursos_profesores_show_info')->get('asignar_cursos_profesores_show_info/{profesor_id}/{ciclo_id}','AsignarCursoProfesor\AsignarCursoProfesorController@getInfoProfesor');
//Route::name('asignar_cursos_profesores_get_all')->get('asignar_cursos_profesores_get_all/{profesor_id}','AsignarCursoProfesor\AsignarCursoProfesorController@getAll');

Route::name('asignar_cursos_profesores_get_one')->get('asignar_cursos_profesores_get_one/{id}','AsignarCursoProfesor\AsignarCursoProfesorController@getOne');

Route::name('asignar_cursos_profesores_get_alumnos')->get('asignar_cursos_profesores_get_alumnos/{id}','AsignarCursoProfesor\AsignarCursoProfesorController@getAlumnos');

Route::resource('asignacion_secciones', 'Inscripcion\AsignacionSeccionController', ['except' => ['create', 'edit']]);
Route::name('asignacion_secciones_get_all')->get('asignacion_secciones_get_all/{ciclo_id}/{grado_nivel_educativo_id}', 'Inscripcion\AsignacionSeccionController@getAll');

Route::name('asignacion_secciones_get_all_without_section')->get('asignacion_secciones_get_all_without_section/{ciclo_id}/{grado_nivel_educativo_id}', 'Inscripcion\AsignacionSeccionController@getWithoutSection');


//asignaciones modulo profesores
Route::resource('asignaciones', 'Asignacion\AsignacionController', ['except' => ['create', 'edit']]);
Route::name('asignaciones_update')->post('asignaciones_update/{id}', 'Asignacion\AsignacionController@updateData');
Route::resource('asignaciones.series', 'Asignacion\AsignacionSerieController', ['except' => ['create', 'edit']]);
Route::resource('asignaciones.alumnos', 'Asignacion\AsignacionAlumnoController', ['except' => ['create', 'edit']]);

Route::resource('series', 'Serie\SerieController', ['except' => ['create', 'edit']]);
Route::resource('series.preguntas', 'Serie\SeriePreguntaController', ['except' => ['create', 'edit']]);
Route::resource('preguntas', 'Serie\PreguntaController', ['except' => ['create', 'edit']]);

Route::resource('asignaciones_alumnos', 'AsignacionAlumno\AsignacionAlumnoController', ['except' => ['create', 'edit']]);
Route::name('asignaciones_alumnos_asignaciones')->get('asignaciones_alumnos_asignaciones/{alumno_id}/{ciclo_id}','AsignacionAlumno\AsignacionAlumnoController@getAsignaciones');
Route::name('asignaciones_alumnos_cursos')->get('asignaciones_alumnos_cursos/{alumno_id}/{ciclo_id}','AsignacionAlumno\AsignacionAlumnoController@getCursos');
//Route::resource('rols.menus', 'Rol\RolMenuController', ['except' => ['create', 'edit']]);
