<template>
    <v-layout justify-center  v-loading="loading">
        <v-flex xs12 sm12 md12>
            <v-container
            fluid
            grid-list-md>
            <v-card>
                <v-layout row wrap justify-end>
                    <div>
                    <v-breadcrumbs :items="itemsB">
                        <template v-slot:divider>
                        <v-icon>forward</v-icon>
                        </template>
                    </v-breadcrumbs>
                    </div>
                </v-layout>
                
                <v-layout row wrap>
                    <v-flex sm12 md12 xs12 lg12>
                         <v-toolbar flat color="white" v-if="asignacion !== null">
                            <v-toolbar-title> <v-icon color="blue">note</v-icon> 
                                   RESULTADOS CUESTIONARIO {{asignacion.asignacion.titulo | uppercase}} 
                                   <span v-if="asignacion.calificado" class="green--text"> (CALIFICADO) </span>
                                   <span v-else class="blue--text">(PENDIENTE DE CALIFICAR) </span>
                            </v-toolbar-title>
                            <v-spacer></v-spacer>

                            <h3>
                               Nota: {{nota()}} <br />
                               Tiempo empleado: {{tiempo()}} minutos
                            </h3>
                        </v-toolbar>
                        <v-flex>
                            <h4 v-if="curso !== null">
                                <hr />
                                <br />
                                NIVEL EDUCATIVO: {{curso.curso_grado_nivel.grado_nivel_educativo.nivel_educativo.nombre | uppercase}} <br />
                                GRADO: {{curso.curso_grado_nivel.grado_nivel_educativo.grado.nombre | uppercase}} <br />
                                CURSO: {{curso.curso_grado_nivel.curso.nombre | uppercase}}
                                <br />
                                <br />
                                <hr />
                            </h4>
                        </v-flex>
                        <v-flex>
                            <v-container v-if="asignacion !== null" fluid grid-list-md>
                                <v-layout row wrap>
                                    <v-flex md12 sm12 lg12>
                                        <b>{{asignacion.asignacion.titulo}}<br />
                                           Descripción: {{asignacion.asignacion.descripcion}}<br />
                                           Valor: {{asignacion.asignacion.nota}} pts
                                        </b>
                                        <br />
                                        <hr />
                                        <br />
                                        <br />
                                        

                                        <v-list two-line subheader v-for="(serie, index) in series" :key="serie.id">
                                            <b>
                                                {{index+1}}) SERIE ({{serie.nota  + ' / '+serie.serie.nota}})<br />
                                                Instrucciones: {{serie.serie.descripcion}}
                                                <br />
                                            </b>

                                            <v-container fluid grid-list-md>
                                                <v-layout wrap>
                                                    <v-flex xs12 md12 lg12 v-for="(pregunta, index_p) in serie.preguntas" :key="pregunta.id">
                                                        <b>{{index_p+1}}) {{pregunta.pregunta.pregunta}} ({{pregunta.nota + ' / '+pregunta.pregunta.nota}} pts)</b>
                                                            <div v-if="serie.serie.tipo_serie == 'FV'">
                                                                <v-radio-group row readonly 
                                                                :value="pregunta.respuestas[0].correcto_alumno ? 'F' : 'V'"
                                                                >

                                                                    <v-radio label="Falso" value="F"></v-radio>
                                                                    <v-radio label="Verdadero" value="V"></v-radio>
                                                                </v-radio-group>
                                                            </div>
                                                            <div v-if="serie.serie.tipo_serie == 'RM'">
                                                                <v-layout wrap>
                                                                    <v-flex xs12 sm3 md3 v-for="res in pregunta.respuestas" :key = res.id>
                                                                            <v-checkbox 
                                                                                v-model="res.correcto_alumno" 
                                                                                :label="res.respuesta_a.respuesta" readonly></v-checkbox>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </div>
                                                            <div v-if="serie.serie.tipo_serie == 'PD'">
                                                                <v-flex>
                                                                    <span>R/ {{pregunta.respuestas[0].respuesta }}</span>
                                                                </v-flex>
                                                            </div>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>

                                        </v-list>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-flex>
                    </v-flex>
                </v-layout>
            </v-card>
            </v-container>
        </v-flex>
    </v-layout>
</template>

<script>
import moment from 'moment'
export default {
  name: "ViewCuestionario",
  props: {
      source: String
    },
  data() {
    return {
        loading: false,
        curso_id: null,
        curso: null,
        asignacion_id: null,
        asignacion: null,
        series: [],
        row: null
    }
  },

  created() {
    let self = this
    self.curso_id = self.$route.params.curso_id
    self.asignacion_id = self.$route.params.id
    self.getCurso(self.curso_id)
    self.getAsignacion(self.asignacion_id)
  },

  methods: {
      //obtener registro curso
      getCurso(id){
          let self = this
            self.loading = true
            self.$store.state.services.asignacionProfesorService
            .getOne(id)
            .then(r => {
                self.loading = false
                if (self.$store.state.global.captureError(r)) {
                    return
                }
                self.curso = r.data
            }).catch(e => {

            })
      },

       //obtener registro curso
      getAsignacion(id){
          let self = this
            self.loading = true
            self.$store.state.services.asignacionAlumnoService
            .getCuestionario(id)
            .then(r => {
                self.loading = false
                if (self.$store.state.global.captureError(r)) {
                    return
                }
                self.asignacion = r.data
                self.series = r.data.series
            }).catch(e => {

            })
      },

      //respuestas
    FVResponse(data){
        let self = this
        self.row = data.find(x=>x.correcta).respuesta
    },

    nota(){
        let self = this
        var total = self.series.reduce(function(a, b) {
            return a + parseFloat(b.nota)
        }, 0)
        return total.toFixed(2)
    },

    tiempo(){
        let self = this
        var duration = moment.duration(moment(self.asignacion.fecha_entrega).diff(moment(self.asignacion.hora_inicio_cuestionario)))
        return parseInt(duration.asMinutes())
    }
  },

  computed: {
      itemsB(){
        let self = this
        return [
            { text: "CURSOS",disabled: false, href: "#/"},
            { text: "ASIGNACIONES",disabled: false, href: "#/"},
            {text: "RESULTADO",disabled: true,href: "#"}
      ]
    }
  },
};
</script>