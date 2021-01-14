class AsignacionService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}asignaciones`
    }

    getAll() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }

    get(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}`);
    }

    getSeries(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}/series`);
    }

    getAlumnos(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}/alumnos`);
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

export default AsignacionService