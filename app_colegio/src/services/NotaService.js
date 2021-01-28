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

    getOneNota(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_get_one_nota/${id}`);
    }

    getAllNotas(ciclo_id,grado_nivel_educativo_id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_get_all_notas/${ciclo_id}/${grado_nivel_educativo_id}`);
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

    //peticion a funcion create
    printBoleta(id) {
        let self = this
        return self.axios.get(`${self.baseUrl}_boleta/${id}`, { responseType: 'blob' });
    }

    printBoletas(ciclo_id,grado_nivel_educativo_id) {
        let self = this
        return self.axios.get(`${self.baseUrl}_boletas/${ciclo_id}/${grado_nivel_educativo_id}`, { responseType: 'blob' });
    }
}

export default NotaService