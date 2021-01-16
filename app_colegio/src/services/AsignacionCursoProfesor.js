class AsignacionCursoProfesorService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}asignar_cursos_profesores`
    }

    getAll(idProfesor,idCiclo) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_get_all/${idProfesor}/${idCiclo}`);
    }

    get(idProfesor,idCiclo) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_show_info/${idProfesor}/${idCiclo}`);
    }

    getAsignaciones(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}`);
    }

    getOne(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_get_one/${id}`);
    }

    getAlumnos(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_get_alumnos/${id}`);
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

    destroy(data){
        console.log(data);
        let self = this;
        return self.axios.delete(`${self.baseUrl}/${data.id}`);
    }
}

export default AsignacionCursoProfesorService