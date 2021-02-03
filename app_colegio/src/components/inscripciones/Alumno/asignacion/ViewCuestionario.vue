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
                        <v-flex v-if="asignacion !== null">
                            <h4 v-if="curso !== null">
                                <hr />
                                <br />
                                NIVEL EDUCATIVO: {{curso.curso_grado_nivel.grado_nivel_educativo.nivel_educativo.nombre | uppercase}} <br />
                                GRADO: {{curso.curso_grado_nivel.grado_nivel_educativo.grado.nombre | uppercase}} <br />
                                CURSO: {{curso.curso_grado_nivel.curso.nombre | uppercase}}<br />
                                ALUMNO: {{asignacion.inscripcion.alumno.primer_nombre + 
                                        ' '+asignacion.inscripcion.alumno.segundo_nombre + 
                                        ' '+asignacion.inscripcion.alumno.primer_apellido + 
                                        ' '+asignacion.inscripcion.alumno.segundo_apellido | uppercase}}
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
                                                         <v-flex xs12 sm6 v-if="pregunta.pregunta.adjunto !== null" offset-sm3>
                                                                <enlargeable-image :src="getImage(pregunta.pregunta.adjunto)" :src_large="getImage(pregunta.pregunta.adjunto)">
                                                                    <v-avatar
                                                                        :tile="true"
                                                                        size="300"
                                                                        >
                                                                        <img :src="getImage(pregunta.pregunta.adjunto)" />
                                                                    </v-avatar>
                                                                </enlargeable-image><br />
                                                            </v-flex>
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
                                                                    <v-flex xs6 sm1 md1 lg1 v-if="isTeach">
                                                                            <v-text-field v-model="pregunta.respuestas[0].nota" 
                                                                                label="Nota"
                                                                                v-validate="'required|decimal|min_value:0|max_value:'+pregunta.pregunta.nota"
                                                                                type="text"
                                                                                :data-vv-name="'nota_pregunta_'+pregunta.id"
                                                                                :error-messages="errors.collect('nota_pregunta_'+pregunta.id)">
                                                                            </v-text-field>
                                                                        </v-flex>
                                                                </v-flex>
                                                            </div>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-list>
                                        
                                        <v-flex v-if="isTeach">
                                            <v-btn small color="success" @click="validate"><v-icon>note_add</v-icon> Asignar nota</v-btn>
                                        </v-flex>

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
import EnlargeableImage from '@diracleo/vue-enlargeable-image'
export default {
  name: "ViewCuestionario",
  props: {
      source: String
    },
    components:{
      EnlargeableImage
    },
  data() {
    return {
        loading: false,
        curso_id: null,
        curso: null,
        asignacion_id: null,
        asignacion: null,
        series: [],
        row: null,

        form: {
            id: null,
            nota: 0,
            serie: null
        }
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

          //funcion para actualizar registro
    update(){
      let self = this
      self.loading = true
      let data = self.form
       
      self.$store.state.services.asignacionAlumnoService
        .asignarNota(data)
        .then(r => {
          self.loading = false
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          self.getAsignacion(self.asignacion_id)
          this.$toastr.success('nota agregada con éxito', 'éxito')
          self.close()
        })
        .catch(r => {});
    },

    //obtener serie
    getNoteValue(data){
        let self = this
        console.log(data)
        data.preguntas.forEach(p=>{
            p.nota = p.respuestas[0].nota
        })
        data.nota = self.getNote(data.preguntas)
        return data
    },

    validate(){
        let self = this
        self.$validator.validateAll().then((result) => {
          if (result) {
              let serie_pd = self.series.find(x=>x.serie.tipo_serie == "PD")
              self.form.id = self.asignacion_id
              self.form.nota = self.nota()
              if(serie_pd !== undefined){
                self.form.serie = self.getNoteValue(serie_pd)
              }
              
              self.update()
           }
      })
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
    },

    //obtener nota
    getNote(data){
        let self = this
        var total = data.reduce(function(a, b) {
            return a + parseFloat(b.nota)
        }, 0)
        return total.toFixed(2)
    },
    getImage(foto){
        let self = this
        return foto !== null ? self.$store.state.base_url+'documentos/'+foto : self.$store.state.base_url+'img/user_empty.png'
    },
  },

  computed: {
      itemsB(){
        let self = this
        let user = self.$store.state.usuario
        if(!_.isEmpty(user)  & self.asignacion !== null){
            if(user.rol.rol == "profesor"){
                return [
                        { text: "CURSOS",disabled: false, href: "#/cursos_index"},
                        { text: "ASIGNACIONES",disabled: false, href: "#/asignacion_index/"+self.curso_id},
                        { text: "ASIGNACION NOTAS",disabled: false,href: "#/asignacion_nota/"+self.curso_id+"/asignacion/"+self.asignacion.asignacion_id},
                        {text: "RESULTADO",disabled: true,href: "#"}
                ]
            }
            return [
                {text: "GRADO Y CURSOS",disabled: false,href: "#/cursos_alumnos_index/"+user.user_info.id},
                {text: "ASIGNACIONES",disabled: false, href: "#/info_cursos_alumnos/"+self.asignacion.inscripcion_id+'/curso/'+self.asignacion.asignacion.asignar_curso_profesor.curso_grad_niv_edu_id},
                {text: "ENTREGA ASIGNACION",disabled: true, href: "#/"}
            ]
        }
    },

    isTeach(){
        let self = this
        let rol = self.$store.state.usuario.rol
        
        if(rol !== undefined && rol.rol == 'profesor'){
            return true
        }
        return false
    }
  },
};
</script>