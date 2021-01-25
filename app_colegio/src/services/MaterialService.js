class MaterialService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}materiales`
    }

    getAll(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_get_all/${id}`);
    }

    get(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}`);
    }

    getByCicloCurso(ciclo_id, curso_grado_nivel_id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_get_by_curso_ciclo/${ciclo_id}/${curso_grado_nivel_id}`);
    }

    create(data) {
        let self = this;
        return self.axios.post(`${self.baseUrl}`, data,
            { headers: 
                {'Content-Type': 'multipart/form-data' }
            }
        );
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

    destroy(data){
        let self = this;
        return self.axios.delete(`${self.baseUrl}/${data.id}`);
    }
}

export default MaterialService