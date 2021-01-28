<template>
  <v-layout justify-center>
    <v-flex xs12 sm12 md12>
      <v-container fluid grid-list-md v-loading="loading">
        <v-layout row wrap>
          <v-flex>
            <v-toolbar flat color="white">
              <v-toolbar-title v-if="ciclo !== null">
                <v-icon color="blue">library_books</v-icon> NOTAS
                CICLO ESCOLAR {{ ciclo.ciclo }}
              </v-toolbar-title>
              <v-divider class="mx-2" inset vertical></v-divider
              ><v-spacer></v-spacer>
              <v-flex>
                <v-autocomplete
                  v-model="ciclo_id"
                  item-value="id"
                  item-text="ciclo"
                  append-icon="filter_list"
                  :items="ciclos"
                  label="Seleccione ciclo"
                  @input="selectedCiclo"
                >
                </v-autocomplete>
              </v-flex>

               <v-flex>
                <v-autocomplete
                  v-model="grado_nivel_educativo_id"
                  item-value="id"
                  :item-text="text"
                  append-icon="filter_list"
                  :items="grados"
                  label="Seleccione grado"
                  @input="selectedGrado"
                >
                </v-autocomplete>
              </v-flex>
              <v-tooltip top v-if="items.length > 0">
                    <template v-slot:activator="{ on }">
                      <v-btn
                        flat
                        v-on="on"
                        color="success"
                        @click="printBoletas"
                      >
                        <v-icon fab dark>print</v-icon></v-btn
                      >
                    </template>
                    <span>Imprimir boletas de calificaciones</span>
                </v-tooltip>

              <v-spacer></v-spacer>
            </v-toolbar>

            <v-data-table
              :items="items"
              class="elevation-1"
              hide-actions
              :headers="headers"
            >
              <template v-slot:items="props">
                <td>
                    <v-icon @click="props.expanded = !props.expanded">{{!props.expanded ? 'keyboard_arrow_right' : 'keyboard_arrow_down' }} </v-icon>
                    {{getName(props.item,true)}}
                </td>
                <td>{{props.item.codigo}}</td>
                <td>
                  <v-tooltip top>
                    <template v-slot:activator="{ on }">
                      <v-btn
                        flat
                        small
                        v-on="on"
                        color="success"
                        @click="printBoleta(props.item)"
                      >
                        <v-icon fab dark>print</v-icon></v-btn
                      >
                    </template>
                    <span>Imprimir boleta de calificaciones</span>
                  </v-tooltip>
                </td>
              </template>
              <template v-slot:expand="props">
                <v-flex xs12 md10 lg10 offset-sm1>
                    <v-card flat>
                    <v-card-text>
                        <hr /><hr />
                        <v-container fluid grid-list-md>
                            <v-layout wrap>
                                <v-flex >
                                    <h3>Calificaciones 
                                        <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-icon v-on="on" color="success" fab dark @click="printBoleta(props.item)"> print</v-icon>
                                        </template>
                                        <span>Imprimir boleta de calificaciones</span>
                                    </v-tooltip>
                                    </h3>
                                                    <hr />
                                </v-flex>
                                <v-flex xs12 md12 lg12>
                                    <v-data-table
                                        :items="props.item.notas"
                                        class="elevation-1"
                                        hide-actions
                                        :headers="headers_notas"
                                        >
                                        <template v-slot:items="props">
                                            <td>{{props.item.no}}</td>
                                            <td> {{ props.item.curso }}</td>
                                            <td> {{ props.item.primer_bimestre }}</td>
                                            <td> {{ props.item.segundo_bimestre }}</td>
                                            <td> {{ props.item.tercer_bimestre }}</td>
                                            <td> {{ props.item.cuarto_bimestre }}</td>
                                            <td> {{ props.item.promedio }}</td>
                                        </template>
                                        </v-data-table>
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <hr /><hr />
                    </v-card-text>
                    </v-card>
                </v-flex>
          </template>
            </v-data-table>
          </v-flex>
        </v-layout>
      </v-container>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "NotasIndex",
  props: {
    source: String,
  },
  data() {
    return {
      loading: false,
      items: [],
      ciclos: [],
      grados: [],
      user: {},
      ciclo: null,
      grado: null,
      ciclo_id: null,
      grado_nivel_educativo_id: null,
      headers: [
        { text: "Alumno", value: "", sortable: false },
        { text: "Codigo", value: "", sortable: false },
        { text: "Acciones", value: "", sortable: false },
      ],
     headers_notas: [
        { text: 'No', value: 'no', sortable: false },
        { text: 'Curso', value: 'curso', sortable: false },
        { text: 'Primer bimestre', value: "primer_bimestre", sortable: false },
        { text: 'Segundo bimestre', value: "primer_bimestre", sortable: false},
        { text: 'Tercer bimestre', value: "primer_bimestre", sortable: false},
        { text: 'Cuarto bimestre', value: "primer_bimestre", sortable: false},
        { text: 'Promedio', value: "primer_bimestre", sortable: false}
      ],
    };
  },

  created() {
    let self = this
    self.getCiclos()
    self.getGrados()
  },


  methods: {

    //obtener cursos de profesores
    getAll(id_ciclo, grado_nivel_educativo_id) {
      let self = this;
      self.loading = true;
      self.$store.state.services.notaService
        .getAllNotas(id_ciclo, grado_nivel_educativo_id)
        .then((r) => {
          self.loading = false
          self.items = r.data.data
        })
        .catch((r) => {})
    },

    getCiclos() {
      let self = this;
      self.loading = true;
      self.$store.state.services.cicloService
        .getAll()
        .then((r) => {
          self.loading = false;
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          self.ciclos = r.data
          if(r.data.find(x=>x.actual) !== undefined){
              self.ciclo = r.data.find(x=>x.actual)
              self.ciclo_id = self.ciclo.id
          }
        })
        .catch((e) => {});
    },

    
    //obtener texto para grados
    text: item => item.grado.nombre + ' ' + item.nivel_educativo.nombre,

    getGrados() {
      let self = this;
      self.loading = true;
      self.$store.state.services.gradoNivelEducativoService
        .getAll()
        .then((r) => {
          self.loading = false;
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          self.grados = r.data
        })
        .catch((e) => {});
    },

    selectedCiclo(id) {
      let self = this
      self.ciclo = self.ciclos.find(x=>x.id==id)
      if(self.grado_nivel_educativo_id != null){
          self.getAll(id,self.grado_nivel_educativo_id)
      }
    },

    selectedGrado(id) {
      let self = this;
      self.grado = self.grados.find(x=>x.id==id)
      if(self.ciclo_id != null){
          self.getAll(self.ciclo_id,id)
      }
    },

        //imprimir boleta
    printBoleta(item) {
      let self = this
      self.loading = true
      self.$store.state.services.notaService
        .printBoleta(item.inscripcion_id)
        .then(r => {
          self.loading = false
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          const url = window.URL.createObjectURL(new Blob([r.data], { type: 'application/pdf' }));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'boleta_calificaciones_'+item.numero+'_'+self.getName(item,true)); 
          //link.target = '_blank'
          link.click();
        })
        .catch(r => {});
    },

    //imprimir total de boleta
    printBoletas() {
      let self = this
      self.loading = true
      self.$store.state.services.notaService
        .printBoletas(self.ciclo_id, self.grado_nivel_educativo_id)
        .then(r => {
          self.loading = false
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          const url = window.URL.createObjectURL(new Blob([r.data], { type: 'application/pdf' }));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'boletas_de_calificaciones_ciclo'+self.ciclo.ciclo+'_'+self.grado.grado.nombre+'_'+self.grado.nivel_educativo.nombre); 
          //link.target = '_blank'
          link.click();
        })
        .catch(r => {});
    },

    getName(data, tercer_nombre = false){
        let self = this
        return self.$store.state.global.getFullName(data, tercer_nombre)
    },
  },

  computed: {},
};
</script>