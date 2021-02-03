import Vue from 'vue'
import Router from 'vue-router'
import store from '../store/index'
import multiguard from 'vue-router-multiguard'

import Default from '@/components/Default'
import Login from '@/components/login/Index'
import Curso from '@/components/administracion/Curso'
import Grado from '@/components/administracion/Grado'
import Usuario from '@/components/accesos/Usuario'
import NivelEducativo from '@/components/administracion/NivelEducativo'
import Seccion from '@/components/administracion/Seccion'
import ConfigurarNivel from '@/components/administracion/ConfigurarNivelEducativo'
import ConceptoPago from '@/components/administracion/ConceptoPago'
import Ciclo from '@/components/administracion/Ciclo'
import Empleado from '@/components/administracion/Empleado'
import ConfigurarCuota from '@/components/administracion/ConfigurarCuota'
import AlumnoIndex from '@/components/inscripciones/Alumno/Index'
import AlumnoCreate from '@/components/inscripciones/Alumno/Create'
import AlumnoEdit from '@/components/inscripciones/Alumno/Edit'
import Inscripcion from '@/components/inscripciones/Inscripcion/Inscripcion'
import AsignacionSeccionIndex from '@/components/inscripciones/asignacion_seccion/Index'
import SeleccionarAlumno from '@/components/Pagos/SeleccionarAlumno'
import PagoAlumno from '@/components/Pagos/PagoAlumno'
import SerieFactura from '@/components/Pagos/SerieFactura'
import AsignarCuota from '@/components/administracion/AsignarCuotaGrado'
import ConsultaInscripcion from '@/components/consultas/ConsultaInscripcion'
import ConsultaPago from '@/components/consultas/ConsultaPago'
import ConsultaCiclo from '@/components/consultas/ConsultaCiclo'
import AlumnoMoroso from '@/components/consultas/AlumnoMoroso'
import CambiarContrasenia from '@/components/accesos/CambiarContrasenia'
import HistorialPagos from '@/components/inscripciones/Alumno/HistorialPagos'
import HistorialAcademico from '@/components/inscripciones/Alumno/HistorialAcademico'
import AsignarCursoProfesor from '@/components/administracion/AsignarCursoProfesor'
import AsignacionIndex from '@/components/profesores/Asignacion/Index'
import AsignarNota from '@/components/profesores/Asignacion/AsignarNota'
import Serie from '@/components/profesores/Asignacion/cuestionarios/Serie'
import AsignacionAlumno from '@/components/inscripciones/Alumno/asignacion/Index'
import Pregunta from '@/components/profesores/Asignacion/cuestionarios/Pregunta'
import ViewAsignacion from '@/components/profesores/Asignacion/cuestionarios/View'
import ViewAlumnos from '@/components/profesores/alumnos/Index'
import EntregaAsignacion from '@/components/inscripciones/Alumno/asignacion/EntregaAsignacion'
import CursosIndex from '@/components/profesores/cursos/Index'
import MaterialApoyo from '@/components/profesores/materiales/Index'
import Nota from '@/components/profesores/notas/Index'
import Cuestionario from '@/components/inscripciones/Alumno/asignacion/Cuestionario'
import ViewCuestionario from '@/components/inscripciones/Alumno/asignacion/ViewCuestionario'
import CursoAlumnoIndex from '@/components/inscripciones/Alumno/cursos/Index'
import InfoCursoAlumno from '@/components/inscripciones/Alumno/cursos/InfoCurso'
import NotaAlumno from '@/components/inscripciones/Alumno/Nota'
import NotasIndex from '@/components/inscripciones/notas/Index'

Vue.use(Router)

//validar authenticacion
const isLoggedIn = (to, from, next) => {
    return store.state.is_login ? next() : next('/login')
}

const isLoggedOut = (to, from, next) => {
    return store.state.is_login ? next('/') : next()
}

//proteger rutas de los sistema, verificar si tiene acceso
const permissionValidations = (to, from, next) => {
    var permisos = store.state.permisos //obtener permisos del usuario
    let name = to.name
    var permiso = _.includes(permisos, name) //verificar si permiso existe
    return permiso ? next() : next('/')
}

const routes = [
    { path: '*', redirect: '/' },
    { path: '/', name: 'Default', component: Default, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/login', name: 'Login', component: Login, beforeEnter: multiguard([isLoggedOut]) },
    { path: '/usuario', name: 'Usuario', component: Usuario, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/curso', name: 'Curso', component: Curso, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/grado', name: 'Grado', component: Grado, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/nivel_educativo', name: 'NivelEducativo', component: NivelEducativo, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/seccion', name: 'Seccion', component: Seccion, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/configurar_nivel/:id', name: 'ConfigurarNivel', component: ConfigurarNivel, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/ciclo', name: 'Ciclo', component: Ciclo, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/concepto_pago', name: 'ConceptoPago', component: ConceptoPago, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/empleado', name: 'Empleado', component: Empleado, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/configurar_cuota/:id', name: 'ConfigurarCuota', component: ConfigurarCuota, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/alumno_index', name: 'AlumnoIndex', component: AlumnoIndex, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/alumno_create', name: 'AlumnoCreate', component: AlumnoCreate, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/alumno_edit/:id', name: 'AlumnoEdit', component: AlumnoEdit, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/inscripcion/:id', name: 'Inscripcion', component: Inscripcion, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/asignacion_seccion_index', name: 'AsignacionSeccionIndex', component: AsignacionSeccionIndex, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/seleccionar_alumno', name: 'SeleccionarAlumno', component: SeleccionarAlumno, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/pago_alumno/:id', name: 'PagoAlumno', component: PagoAlumno, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/serie_factura', name: 'SerieFactura', component: SerieFactura, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/asignar_cuota/:id-:grado_id', name: 'AsignarCuota', component: AsignarCuota, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/consulta_inscripcion', name: 'ConsultaInscripcion', component: ConsultaInscripcion, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/consulta_pago', name: 'ConsultaPago', component: ConsultaPago, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/consulta_ciclo', name: 'ConsultaCiclo', component: ConsultaCiclo, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/alumno_moroso', name: 'AlumnoMoroso', component: AlumnoMoroso, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/change_password', name: 'CambiarContrasenia', component: CambiarContrasenia, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/historial_pagos/:id', name: 'HistorialPagos', component: HistorialPagos, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/historial_academico/:id', name: 'HistorialAcademico', component: HistorialAcademico, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/asignar_profesores', name: 'AsignarProfesores', component: AsignarCursoProfesor, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/asignacion_index/:id', name: 'AsignacionIndex', component: AsignacionIndex, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/serie/:curso_id/asignacion/:id', name: 'Serie', component: Serie, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/asignacion_alumno', name: 'AsignacionAlumnos', component: AsignacionAlumno, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/pregunta/:curso_id/asignacion/:asignacion_id/serie/:id', name: 'Pregunta', component: Pregunta, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/view_asignacion/curso/:curso_id/asignacion/:asignacion_id', name: 'ViewAsignacion', component: ViewAsignacion, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/view_alumnos/:id', name: 'ViewAlumnos', component: ViewAlumnos, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/entrega_asignacion/:id', name: 'EntregaAsignacion', component: EntregaAsignacion, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/asignacion_nota/:curso_id/asignacion/:id', name: 'AsignarNota', component: AsignarNota, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/cursos_index', name: 'CursosIndex', component: CursosIndex, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/materiales_index/:id', name: 'MaterialApoyo', component: MaterialApoyo, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/notas_index/:id', name: 'Nota', component:Nota, beforeEnter: multiguard([isLoggedIn, permissionValidations])},
    { path: '/cuestionario/curso/:curso_id/asignacion_alumno/:asignacion_alumno_id', name: 'Cuestionario', component: Cuestionario, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/view_cuestionario/curso/:curso_id/asignacion_alumno/:id', name: 'ViewCuestionario', component: ViewCuestionario, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/cursos_alumnos_index/:id', name: 'CursoAlumnoIndex', component: CursoAlumnoIndex, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/info_cursos_alumnos/:inscripcion_id/curso/:curso_grado_nivel_id', name: 'InfoCursoAlumno', component: InfoCursoAlumno, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/nota_alumno/:id', name: 'NotaAlumno', component: NotaAlumno, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/notas_index', name: 'NotasIndex', component: NotasIndex, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
]


const router = new Router({ routes })

export default router