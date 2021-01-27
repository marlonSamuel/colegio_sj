class AsignacionAlumnoService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}asignaciones_alumnos`
    }

    getAll() {
        let self = this;
        return self.axios.get(`${self.baseUrl}_get_all/${idProfesor}/${idCiclo}`);
    }

    get(idAsignacion) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${idAsignacion}`);
    }

    getAsignacion(idAlumno,idCiclo) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_asignaciones/${idAlumno}/${idCiclo}`);
    }

    getCuestionario(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_cuestionario/${id}`);
    }

    getAsignacionByCurso(inscripcion_id,curso_grado_nivel_id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_asignacion_by_curso/${inscripcion_id}/${curso_grado_nivel_id}`);
    }

    iniciar(data) {
        let self = this;
        return self.axios.put(`${self.baseUrl}_start/${data.id}`,data);
    }

    terminar(data) {
        let self = this;
        return self.axios.put(`${self.baseUrl}_finish/${data.id}`,data);
    }


    getCurso(idAlumno,idCiclo) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_cursos/${idAlumno}/${idCiclo}`);
    }

    getInfo(){
        let self = this;
        return self.axios.get(`${self.baseUrl}_info`);
    }

    create(data) {
        let self = this;
        return self.axios.post(`${self.baseUrl}`, data);
    }

    update(data) {
        let self = this;
        return self.axios.put(`${self.baseUrl}/${data.id}`,data);
    }
    
    updateData(id,data) {
        let self = this;
        return self.axios.post(`${self.baseUrl}_update/${id}`,data,
            { headers: 
                {'Content-Type': 'multipart/form-data' }
            }
        )
    }

    asignarNota(data) {
        let self = this;
        return self.axios.put(`${self.baseUrl}_asignar_nota/${data.id}`,data);
    }

    destroy(data){
        console.log(data);
        let self = this;
        return self.axios.delete(`${self.baseUrl}/${data.id}`);
    }
}

export default AsignacionAlumnoService