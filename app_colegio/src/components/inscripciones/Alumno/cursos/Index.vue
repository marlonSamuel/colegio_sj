<template>
  <v-layout justify-center>
    <v-flex xs12 sm12 md12>
      <v-container fluid grid-list-md v-loading="loading">
        <v-layout row wrap>
          <v-flex>
            <v-toolbar flat color="white">
              <v-toolbar-title v-if="ciclo !== null && alumno != null">
                <v-icon color="blue">library_books</v-icon> CURSOS
                CICLO ESCOLAR {{ ciclo.ciclo }} {{grado | uppercase}} <br />
                <v-icon color="green">person_pin</v-icon>{{alumno.primer_nombre + ' '+alumno.segundo_nombre+' '+alumno.primer_nombre+' '+ alumno.segundo_apellido | uppercase}}
                
              </v-toolbar-title>
              <v-divider class="mx-2" inset vertical></v-divider
              ><v-spacer></v-spacer>
              <v-flex>
                <v-select
                  v-model="ciclo_id"
                  item-value="id"
                  item-text="ciclo.ciclo"
                  append-icon="filter_list"
                  @input="selectedCiclo"
                  :items="inscripciones"
                  label="Filtrar por ciclo"
                >
                </v-select>
              </v-flex>
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
                  {{
                    props.item.curso.nombre
                  }}
                </td>
                <td>
                  <v-tooltip top>
                    <template v-slot:activator="{ on }">
                      <v-btn
                        flat
                        small
                        v-on="on"
                        color="primary"
                        @click="
                          $router.push('/info_cursos_alumnos/'+inscripcion.id+'/curso/'+props.item.id)
                        "
                      >
                        <v-icon fab dark>remove_red_eye</v-icon> información a detalle</v-btn
                      >
                    </template>
                    <span>Ver información, notas, asignaciones y detalle</span>
                  </v-tooltip>
                </td>
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
  name: "CursosAlumno",
  props: {
    source: String,
  },
  data() {
    return {
      loading: false,
      items: [],
      ciclos: [],
      user: {},
      ciclo: null,
      ciclo_id: null,
      alumno: null,
      grado: '',
      inscripcion: null,
      inscripciones: [],
      headers: [
        { text: "Curso", value: "", sortable: false },
        { text: "Acciones", value: "", sortable: false },
      ],
      headers2: [
        { text: "Tarea", value: "", sortable: false },
        { text: "fecha entrega", value: "", sortable: false },
      ],
    };
  },

  created() {
    let self = this;
    self.ciclo = self.$store.state.ciclo
    self.getAlumno(self.$route.params.id)
  },

  beforeDestroy() {
    let self = this;
  },

  methods: {
    onEventTeach(data) {
      let self = this;
      self.get(data.id);
    },

    //obtener cursos de profesores
    get(id, id_ciclo) {
      let self = this;
      self.loading = true;
      self.$store.state.services.asignacionProfesorService
        .getAll(id, id_ciclo)
        .then((r) => {
          self.loading = false;
          self.items = r.data;
        })
        .catch((r) => {});
    },

        //obtener alumno
    getAlumno(id){
        let self = this
        self.loading = true
        let data = self.form
        self.$store.state.services.alumnoService
        .getHistorialAlumno(id)
        .then(r => {
            self.loading = false
            self.alumno = r.data.alumno
            self.inscripciones = r.data.inscripciones
            let inscripcion = self.inscripciones.find(x=>x.ciclo.actual)
            
            if(inscripcion !== undefined){
                self.setGrado(inscripcion.id)
            }
        }).catch(r => {});
    },

    setGrado(inscripcion_id){
        let self = this
        self.inscripcion = self.inscripciones.find(x=>x.id == inscripcion_id)
        self.grado = self.inscripcion.grado_nivel_educativo.grado.nombre+' '+self.inscripcion.grado_nivel_educativo.nivel_educativo.nombre
        self.items = self.inscripcion.grado_nivel_educativo.cursos
    },

    selectedCiclo(id) {
      let self = this
      self.setGrado(id)
    },
  },

  computed: {},
};
</script>