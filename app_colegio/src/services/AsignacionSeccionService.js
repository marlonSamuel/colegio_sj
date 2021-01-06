class AsignacionSeccionService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}asignacion_secciones`
    }

    getAll(ciclo_id, grado_nivel_educativo_id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_get_all/${ciclo_id}/${grado_nivel_educativo_id}`);
    }

    getAllWithoutSection(ciclo_id, grado_nivel_educativo_id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_get_all_without_section/${ciclo_id}/${grado_nivel_educativo_id}`);
    }

    get(cui) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${cui}`);
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
        let self = this;
        return self.axios.delete(`${self.baseUrl}/${data.id}`);
    }
}

export default AsignacionSeccionService