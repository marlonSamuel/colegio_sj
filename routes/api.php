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
Route::resource('ciclos.periodos', 'Ciclo\CicloPeriodoAcademicoController', ['except' => ['create', 'edit']]);

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
Route::name('preguntas_update')->post('preguntas_update/{id}', 'Serie\PreguntaController@updateData');

Route::resource('asignaciones_alumnos', 'AsignacionAlumno\AsignacionAlumnoController', ['except' => ['create', 'edit']]);

Route::name('asignaciones_alumnos_asignaciones')->get('asignaciones_alumnos_asignaciones/{alumno_id}/{ciclo_id}','AsignacionAlumno\AsignacionAlumnoController@getAsignaciones');
Route::name('asignaciones_alumnos_cursos')->get('asignaciones_alumnos_cursos/{alumno_id}/{ciclo_id}','AsignacionAlumno\AsignacionAlumnoController@getCursos');

Route::name('asignaciones_alumnos_cuestionario')->get('asignaciones_alumnos_cuestionario/{id}','AsignacionAlumno\AsignacionAlumnoController@cuestionario');

Route::name('asignaciones_alumnos_update')->post('asignaciones_alumnos_update/{id}', 'AsignacionAlumno\AsignacionAlumnoController@updateData');
Route::name('asignaciones_alumnos_start')->put('asignaciones_alumnos_start/{id}', 'AsignacionAlumno\AsignacionAlumnoController@iniciarCuestionario');
Route::name('asignaciones_alumnos_finish')->put('asignaciones_alumnos_finish/{id}', 'AsignacionAlumno\AsignacionAlumnoController@terminarCuestionario');
Route::name('asignaciones_alumnos_asignar_nota')->put('asignaciones_alumnos_asignar_nota/{alumno_id}','AsignacionAlumno\AsignacionAlumnoController@asignarNota');
Route::name('asignaciones_alumnos_asignacion_by_curso')->get('asignaciones_alumnos_asignacion_by_curso/{inscripcion_id}/{curso_grado_nivel_id}','AsignacionAlumno\AsignacionAlumnoController@getAsignacionesByCurso');


Route::resource('materiales', 'MaterialApoyo\MaterialApoyoController', ['except' => ['create', 'edit']]);
Route::name('materiales_update')->post('materiales_update/{id}', 'MaterialApoyo\MaterialApoyoController@updateData');
Route::name('materiales_get_all')->get('materiales_get_all/{id}', 'MaterialApoyo\MaterialApoyoController@getAll');

Route::name('materiales_get_by_curso_ciclo')->get('materiales_get_by_curso_ciclo/{ciclo_id}/{curso_grado_nivel_id}', 'MaterialApoyo\MaterialApoyoController@getByCursoCiclo');

Route::resource('notas', 'Nota\NotaController', ['except' => ['create', 'edit']]);
Route::name('notas_periodos')->get('notas_periodos/{id}', 'Nota\NotaController@getPeriodoAcademico');
Route::name('notas_get_all')->get('notas_get_all/{idAsignCursoProf}/{idPeriodoAcademico}', 'Nota\NotaController@getAll');
//Route::resource('rols.menus', 'Rol\RolMenuController', ['except' => ['create', 'edit']]);

Route::resource('serie_respuesta_alumnos', 'Serie\SerieRespuestaAlumnoController', ['except' => ['create', 'edit']]);

Route::name('notas_boleta')->get('notas_boleta/{inscripcion_id}', 'Nota\NotaController@boleta');
Route::name('notas_boletas')->get('notas_boletas/{ciclo_id}/{grado_nivel_educativo_id}', 'Nota\NotaController@boletas');
Route::name('notas_get_one_nota')->get('notas_get_one_nota/{inscripcion_id}', 'Nota\NotaController@getOneNota');
Route::name('notas_get_all_notas')->get('notas_get_all_notas/{ciclo_id}/{grado_nivel_educativo_id}', 'Nota\NotaController@getAllNotas');
Route::name('reset_contraseña')->post('usuarios_reset_password', 'Usuario\UsuarioController@resetPassword');
Route::name('cuotas_start_ciclo')->post('cuotas_start_ciclo', 'Cuota\CuotaController@startCuotaCiclo');
