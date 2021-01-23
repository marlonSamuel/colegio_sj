class PreguntaService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}preguntas`
    }

    getAll() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }

    get(cui) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${cui}`);
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

export default PreguntaService