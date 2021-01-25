class NotaService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}notas`
    }

    getAll(idAsign_Curso_Prof,idPeriodo_Academico) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_get_all/${idAsign_Curso_Prof}/${idPeriodo_Academico}`);
    }

    get(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}`);
    }
    getPeriodos(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_periodos/${id}`);
    }
    create(data) {
        let self = this;
        return self.axios.post(`${self.baseUrl}`, data);
    }

    update(id,data) {
        let self = this;
        return self.axios.put(`${self.baseUrl}/${id}`, data);
    }

    destroy(data) {
        let self = this;
        return self.axios.delete(`${self.baseUrl}/${data.id}`);
    }
}

export default NotaService