<template>
  <v-layout justify-center>
    <v-flex xs12 sm12 md12>
      <v-container fluid grid-list-md>
        <v-layout row wrap>
          <v-flex sm6 md6 xs12 lg6>
            <h2>Cursos actuales</h2>
            <v-data-table
              :items="cursos"
              class="elevation-1"
              hide-actions
              :headers="headers"
            >
              <template v-slot:items="props">
                <td>
                  {{
                    props.item.ciclo
                  }}
                </td>
                <td>
                  {{ props.item.nombre }}
                </td>
              </template>
            </v-data-table>
          </v-flex>
          <v-flex sm6 md6 xs12 lg6>
            <h2>Tareas asignadas</h2>
            <v-data-table
              :items="asignaciones"
              class="elevation-1"
              hide-actions
              :headers="headers2"
            >
              <template v-slot:items="props">
                <td>
                  {{
                    props.item.asignacion.asignar_curso_profesor
                      .curso_grado_nivel.curso.nombre
                  }}
                </td>
                <td>
                  {{ props.item.asignacion.titulo }}
                </td>
                <td>
                  {{ props.item.asignacion.fecha_entrega }}
                </td>
                <td>
                  <v-tooltip top v-if="!props.item.calificado">
                    <template v-slot:activator="{ on }">
                      <v-btn
                        flat
                        small
                        v-on="on"
                        color="primary"
                        @click="navigateTo(props.item)"
                      >
                        <v-icon fab dark>edit</v-icon> {{props.item.entregado && props.item.asignacion.flag_tiempo ? 'Ver' : (props.item.entregado ? 'Reenviar' : 'Responder')}}</v-btn>
                    </template>
                    <span>{{props.item.entregado && props.item.asignacion.flag_tiempo ? 'Ver' : (props.item.entregado ? 'Reenviar' : 'Responder')}}</span>
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
import moment from "moment";
export default {
  name: "PanelAlumno",
  props: {
    source: String,
  },
  data() {
    return {
      loading: false,
      items: [],
      headers: [
        { text: "Periodo", value: "", sortable: false },
        { text: "clase", value: "", sortable: false },
      ],
      headers2: [
        { text: "Curso", value: "", sortable: false },
        { text: "Tarea", value: "", sortable: false },
        { text: "fecha entrega", value: "", sortable: false },
        { text: "", value: "" },
      ],
      asignaciones: [],
      cursos: [],
      idCiclo: null,
      idAlumno:null
    };
  },

  created() {
    let self = this;
    self.idAlumno = this.$store.state.usuario.user_info.id
    self.getCicloActual();   
  },

  methods: {
    validDate(){

    },
    getAsignaciones(idAlumno, idCiclo) {
      let self = this;
      self.loading = true;
      self.$store.state.services.asignacionAlumnoService
        .getAsignacion(idAlumno, idCiclo)
        .then((r) => {
          self.loading = false;
          r.data.forEach(element => {
            if (moment(element.asignacion.fecha_habilitacion).format('YYYY-MM-DD') <= moment().format('YYYY-MM-DD')) {
              if (element.asignacion.entrega_tarde) {
                  self.asignaciones.push(element);
              }else{
                if (moment(element.asignacion.fecha_entrega).format('YYYY-MM-DD') >= moment().format('YYYY-MM-DD')) {
                    self.asignaciones.push(element);
                }
              }
            }
          });
        })
        .catch((r) => {});
    },
    getCicloActual() {
      let self = this;
      self.loading = true;
      self.$store.state.services.cicloService
        .actual()
        .then((r) => {
          self.loading = false;
          self.idCiclo = r.data.id;
          self.getCursos(self.idAlumno,self.idCiclo)
          self.getAsignaciones(self.idAlumno,self.idCiclo)
          
        })
        .catch((r) => {});
    },
    getCursos(idAlumno, idCiclo) {
      let self = this;
      self.loading = true;
      self.$store.state.services.asignacionAlumnoService
        .getCurso(idAlumno, idCiclo)
        .then((r) => {
          self.loading = false;
          self.cursos = r.data[0];
        })
        .catch((r) => {});
    },

    navigateTo(data){
      let self = this
      if(!data.asignacion.flag_tiempo){
        self.$router.push('entrega_asignacion/'+data.id)
      }else{
        if(!data.entregado){
          self.$router.push('cuestionario/curso/'+data.asignacion.asignar_curso_profesor_id+'/asignacion_alumno/'+data.id)
        }else{
          self.$router.push('view_cuestionario/curso/'+data.asignacion.asignar_curso_profesor_id+'/asignacion_alumno/'+data.id)
        }
      }
    }
  },

  computed: {

  },
};
</script>