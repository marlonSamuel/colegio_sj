<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
      <v-data-table
        :headers="headers"
        :items="items"
        class="elevation-1"
        hide-actions
        :expand="expand"
        :rows-per-page-items="[10,25]"
      >
        <template v-slot:items="props">
          <tr>
            <td @click="expanded(props.item, props.expanded)">
              <v-icon @click="props.expanded = !props.expanded">{{!props.expanded ? 'keyboard_arrow_right' : 'keyboard_arrow_down' }} </v-icon>
              {{props.item.numero }}
            </td>
            <td @click="expanded(props.item)">{{ props.item.ciclo.ciclo }}</td>
            <td @click="expanded(props.item)">{{ props.item.grado_nivel_educativo.grado.nombre }} {{ props.item.grado_nivel_educativo.nivel_educativo.nombre }}</td>
            <td @click="expanded(props.item)">
              <v-tooltip top>
                  <template v-slot:activator="{ on }">
                      <v-icon v-on="on" color="primary" fab dark @click="printContrato(props.item)"> print</v-icon>
                  </template>
                  <span>Imprimir contrato</span>
              </v-tooltip>
               <v-tooltip top>
                  <template v-slot:activator="{ on }">
                      <v-icon v-on="on" color="success" fab dark @click="printBoleta(props.item)"> print</v-icon>
                  </template>
                  <span>Imprimir boleta de calificaciones</span>
              </v-tooltip>
            </td>
          </tr>
        </template>
         <template v-slot:expand="props">
           <v-flex xs12 md10 lg10 offset-sm1>
            <v-card flat>
              <v-card-text>
                  <hr /><hr />
                  <v-container fluid grid-list-md v-loading="loading_nota">
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
                                  :items="notas.notas"
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
        <template slot="no-data">
            <h3>No se encontraron inscripciones.</h3>
        </template>
      </v-data-table>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "inscripciones_alumno_historial",
  props: {
      source: String
    },
  data() {
    return {
      dialog: false,
      expand: false,
      search: '',
      loading: false,
      loading_nota: true,
      menu: false,
      date: null,
      items: [],
      ciclo: null,
      grados: [],
      grado_id: 0,
      notas: [],
      alumno: null,
      headers: [
        { text: 'Numero de inscripción', value: 'numero' },
        { text: 'Ciclo', value: 'ciclo.ciclo' },
        { text: 'Grado', value: "grado_nivel_educativo.grado.nombre" },
        { text: 'Acciones', value: '', sortable: false }
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
      inicio: '',
      fin: ''
    };
  },

  created() {
    let self = this
    //self.getAll()
    events.$on('historial_academico_inscripciones',self.onEventAcademico)
  },

  beforeDestroy(){
      let self = this
      events.$off('historial_academico_inscripciones',self.onEventAcademico)
  },

  methods: {
    onEventAcademico(inscripciones,alumno) {
      let self = this
      self.alumno = alumno
      inscripciones = inscripciones.map(x => ({...x, expanded: false}))
      self.items = inscripciones
    },


    getNotas(id){
      let self = this
      self.notas = []
      self.loading = true
      self.$store.state.services.notaService
      .getOneNota(id)
      .then(r=>{
        self.loading = false
        if (self.$store.state.global.captureError(r)) {
          return;
        }
        r.data.data.notas.push(
              {no: '', curso:'PROMEDIO',
               primer_bimestre: r.data.data.primer_bimestre,
               segundo_bimestre: r.data.data.segundo_bimestre,
               tercer_bimestre: r.data.data.tercer_bimestre,
               cuarto_bimestre: r.data.data.cuarto_bimestre,
               promedio:''})
        self.notas = r.data.data
      }).catch(e=>{})
    },

    printContrato (item) {
      let self = this
      self.loading = true
      self.$store.state.services.inscripcionService
        .getContrato(item.id)
        .then(r => {
          self.loading = false
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          const url = window.URL.createObjectURL(new Blob([r.data], { type: 'application/pdf' }));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'inscripcion_'+item.numero); 
          //link.target = '_blank'
          link.click();
        })
        .catch(r => {});
    },

    //imprimir boleta
    printBoleta(item) {
      let self = this
      self.loading = true
      self.$store.state.services.notaService
        .printBoleta(item.id)
        .then(r => {
          self.loading = false
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          const url = window.URL.createObjectURL(new Blob([r.data], { type: 'application/pdf' }));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'boleta_calificaciones_'+item.numero+'_'+self.getName(self.alumno,true)); 
          //link.target = '_blank'
          link.click();
        })
        .catch(r => {});
    },

    printReporte(){
      let self = this
    },

    getName(data, tercer_nombre = false){
        let self = this
        return self.$store.state.global.getFullName(data, tercer_nombre)
    },

    //exapende
    expanded(data,prop){
      let self = this
      if(data.expanded == true && prop == true){
        return
      }
      data.expanded = prop
      if(data.expanded){
        self.getNotas(data.id)
      }
    }
    
  },

  computed: {
    },
};
</script>