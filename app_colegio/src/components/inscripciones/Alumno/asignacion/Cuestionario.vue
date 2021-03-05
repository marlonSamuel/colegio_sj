<template>
    <v-layout justify-center  v-loading="loading">
        <v-flex xs12 sm12 md12 v-if="asignacion !== null">
            <v-dialog v-model="dialog" max-width="1200px" persistent>
                <v-card>
                    <v-card-title
                        class="headline grey lighten-2"
                        primary-title
                        >
                        RECOMENDACIONES {{asignacion.asignacion.titulo | uppercase}}
                    </v-card-title>
        
                    <v-card-text>
                        <v-container fluid grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm12 md12>
                                    Antes de iniciar examen tome en cuenta lo siguiente <br />

                                    Asegurese de que nadie lo interrumpa <br />

                                    Cuando inicie el examen tiene exactamente {{asignacion.asignacion.tiempo}} minutos para resolver el examen <br />
                                    
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
        
                    <v-card-actions>
                    <v-spacer></v-spacer>
                        <v-btn color="red darken-1" flat @click="$router.push('/info_cursos_alumnos/'+asignacion.inscripcion_id+'/curso/'+asignacion.asignacion.asignar_curso_profesor.curso_grad_niv_edu_id)">Volver</v-btn>
                        <v-btn color="blue darken-1" flat @click="iniciar">Iniciar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            
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
                
                    <v-alert v-if="tiempo !== null && tiempo <= 10"
                        v-model="alert2"
                        dismissible
                        type="info"
                        >
                        ATENCION!!! LE QUEDA POCO TIEMPO PARA RESOLVER LA PRUEBA, EL EXAMEN SE GUARDARA AL AGOTARSE EL TIEMPO
                    </v-alert>
                <v-layout row wrap>
                    <v-flex sm12 md12 xs12 lg12>
                         <v-toolbar flat color="white">
                            <v-toolbar-title v-if="asignacion !== null"> <v-icon color="blue">note</v-icon> 
                                    CUESTIONARIO {{asignacion.asignacion.titulo | uppercase}} 
                            </v-toolbar-title>
                            
                            <v-spacer></v-spacer>
                            <h3 class="red-color" v-if="!asignacion.entregado">
                               {{count_message}} 
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
                        <v-flex v-if="!dialog">
                            <v-container v-if="asignacion !== null" fluid grid-list-md>

                                <v-alert v-if="date_expire"
                                    v-model="alert"
                                    type="error"
                                    >
                                    Prueba ya no se encuentra disponible
                                </v-alert>
                                
                                <v-alert v-if="asignacion.entregado"
                                    v-model="alert"
                                    type="success"
                                    >
                                    Examen respondido
                                </v-alert>

                                <v-alert v-if="time_expire"
                                    v-model="alert"
                                    type="error"
                                    >
                                    Tiempo de examen agotado
                                </v-alert>

                                <v-layout row wrap v-if="!asignacion.entregado && !date_expire && !time_expire">
                                    <v-flex md12 sm12 lg12>
                                         <b>{{asignacion.asignacion.titulo}}<br />
                                           Descripción: {{asignacion.asignacion.descripcion}}<br />
                                           Valor: {{asignacion.asignacion.nota}} pts
                                        </b>
                                        <br />
                                        <hr />
                                        <br />
                                        <br />

                                        
                                <v-stepper v-model="e1">
                                        <v-stepper-header>
                                            <v-layout v-for="(serie,index) in series" :key="serie.id">
                                                <v-stepper-step :complete="e1 > index+1" :step="index+1">
                                                    {{serie.serie.tipo_serie == 'FV' ? 'Falso y Verdadero' : (serie.serie.tipo_serie == 'RM' ? 'Respuesta multiple' : 'Preguntas directas')}}
                                                </v-stepper-step>
                                                <el-divider></el-divider>
                                            </v-layout>
                                            <v-stepper-step :step="series.length + 1">Finalizado</v-stepper-step>
                                        </v-stepper-header>

                                            <v-stepper-items>
                                                    <v-stepper-content 
                                                    :step="(index+1)" 
                                                    v-for="(serie,index) in series" :key="serie.id">
                                                        <v-card
                                                        class="mb-5"
                                                        >
                                                        
                                                        <v-card-text>
                                                            <b>
                                                                {{index+1}}) SERIE (VALOR: {{serie.serie.nota}} pts)<br />
                                                                Instrucciones: {{serie.serie.descripcion}}
                                                                <br />
                                                            </b>

                                                            <v-container fluid grid-list-md>
                                                                <v-layout wrap>
                                                                    <v-flex xs12 md12 lg12 v-for="(pregunta, index_p) in serie.preguntas" :key="pregunta.id">
                                                                        <b>{{index_p+1}}) {{pregunta.pregunta.pregunta}} ({{pregunta.pregunta.nota}} pts)</b>
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
                                                                                <v-radio-group row v-model="pregunta.respuestas[0].response">
                                                                                    <v-radio v-for="(res) in pregunta.respuestas" :key= res.id
                                                                                        :label="res.respuesta_a.respuesta  == 'F' ? 'Falso':'Verdadero'" 
                                                                                        :value="res.respuesta_a.respuesta"></v-radio>
                                                                                </v-radio-group>
                                                                            </div>
                                                                            <div v-if="serie.serie.tipo_serie == 'RM'">
                                                                                <v-layout wrap>
                                                                                    <v-flex xs12 sm3 md3 v-for="res in pregunta.respuestas" :key = res.id>
                                                                                            <v-checkbox 
                                                                                                v-model="res.correcto_alumno"
                                                                                                :label="res.respuesta_a.respuesta"></v-checkbox>
                                                                                    </v-flex>
                                                                                </v-layout>
                                                                            </div>
                                                                            <div v-if="serie.serie.tipo_serie == 'PD'">
                                                                                <v-flex>
                                                                                    <v-textarea
                                                                                        v-model="pregunta.respuestas[0].respuesta"
                                                                                        label="R/"
                                                                                        type="text"
                                                                                        :rows="3"
                                                                                    ></v-textarea>
                                                                                </v-flex>
                                                                            </div>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>

                                                        </v-card-text>

                                                        </v-card>

                                                        <v-btn
                                                            color="primary"
                                                            v-if="validateSection(serie)"
                                                            @click="nextSection((index+2),serie)"
                                                            >
                                                            Siguiente
                                                        </v-btn>
                                                    </v-stepper-content>
                                                    <v-stepper-content :step="series.length+1">
                                                    <v-card
                                                        class="mb-5"
                                                        color="grey lighten-1"
                                                        >
                                                        <v-card-text>
                                                            <v-container>
                                                                Examen finalizado, click en el boton finalizar para marcar examen como respondido
                                                            </v-container>
                                                        </v-card-text>
                                                        </v-card>

                                                        <v-btn
                                                        color="primary"
                                                        @click="terminar"
                                                        >
                                                        Finalizar
                                                        </v-btn>
                                                    </v-stepper-content>
                                            </v-stepper-items>
                                        </v-stepper>

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
        dialog: true,
        alert: true,
        alert2: true,
        loading: false,
        curso_id: null,
        time_expire: false,
        date_expire: false,
        curso: null,
        asignacion_alumno_id: null,
        asignacion: null,
        series: [],
        row: null,
        count_message: '',
        tiempo: null,
        e1: 0,
        form: {
            'id' : null,
            'nota' : null,
            'preguntas': []
        }
    }
  },

  created() {
    let self = this
    self.curso_id = self.$route.params.curso_id
    self.asignacion_alumno_id = self.$route.params.asignacion_alumno_id
    self.getCurso(self.curso_id)
    self.getCuestionario(self.asignacion_alumno_id)
  },

  methods: {
    //validación para bloquear las series
      preVerify(){
          let self = this
          let hora_inicio = self.asignacion.hora_inicio_cuestionario
          if(hora_inicio!== null){


              hora_inicio = moment(hora_inicio).format('YYYY-MM-DD HH:mm')

              let hora_fin = moment(hora_inicio).add(self.asignacion.asignacion.tiempo,'m').format('YYYY-MM-DD HH:mm')

              if(hora_fin < moment().format('YYYY-MM-DD HH:mm')){
                  self.time_expire = true
              } 
          }

          self.series.forEach((s,index)=>{
              if(s.respondida){
                  self.e1 = index+2
              }
          })
          if(moment(self.asignacion.asignacion.fecha_entrega).format('YYYY-MM-DD') < moment().format('YYYY-MM-DD')){
              self.date_expire = true
          }
          
      },
    
    //cuenta regresiva
    setTimeCounter(){
      let self = this
      let hora_inicio = self.asignacion.hora_inicio_cuestionario
      if(hora_inicio !== null){
        var counter = 59
        hora_inicio = moment(hora_inicio)
        let hora_fin = moment(hora_inicio).add(self.asignacion.asignacion.tiempo,'m')
        let now = moment()

        //let now = moment().format('YYYY-MM-DD HH:mm')
        var duration = moment.duration(hora_fin.diff(now))

        self.tiempo = parseInt(duration.asMinutes()) - 1

        if(self.tiempo < 0){
            console.log("tiempo expirado")
            return
        }

        var close = setInterval(function()
            { 
                let seconds = counter < 10 ? '0'+counter : counter
                self.count_message = 'Tiempo (minutos): '+ self.asignacion.asignacion.tiempo + ' / '+self.tiempo +':'+seconds
                counter = counter - 1
                if(counter < 0){
                    counter = 59
                    self.tiempo = self.tiempo - 1
                }
                if(self.tiempo < 0){
                    clearInterval(close)
                    self.terminar()
                    console.log("tiempo terminado")
                }
            }, 1000);
      }
    },

    iniciar(){
        let self = this
        self.loading = true

        let data = {
            id: self.asignacion.id,
            hora: moment().format('YYYY-MM-DD HH:mm')
        }

        self.$store.state.services.asignacionAlumnoService
          .iniciar(data)
            .then(r => {
            self.loading = false
                if (self.$store.state.global.captureError(r)) {
                    return;
                }
                this.$toastr.success('Examen iniciado', 'éxito')
                self.dialog = false
                self.getCuestionario(self.asignacion_alumno_id)
            })
        .catch(r => {});
    },

    terminar(){
        let self = this
        self.loading = true

        let data = {
            id: self.asignacion.id,
            hora: moment().format('YYYY-MM-DD HH:mm')
        }

        self.$store.state.services.asignacionAlumnoService
          .terminar(data)
            .then(r => {
            self.loading = false
                if (self.$store.state.global.captureError(r)) {
                    return;
                }
                this.$toastr.success('Examen entregado con éxito', 'éxito')
                self.getCuestionario(self.asignacion_alumno_id)
            })
        .catch(r => {});
    },

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
      getCuestionario(id){
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

                if(self.asignacion.hora_inicio_cuestionario)
                    self.dialog = false

                if(!self.asignacion.entregado){
                    self.preVerify()

                    if(self.date_expire)
                        self.dialog = false
                        
                    if(!self.time_expire && self.asignacion.hora_inicio_cuestionario !== null){
                        self.setTimeCounter()
                    }
                }

            }).catch(e => {

            })
      },



    //obtener nota
    getNote(data){
        let self = this
        var total = data.reduce(function(a, b) {
            return a + parseFloat(b.nota)
        }, 0)
        return total
    },

        //funcion para actualizar registro
    update(data){
      let self = this
      self.loading = true
      
      self.$store.state.services.alumnoSerieService
        .update(data)
        .then(r => {
          self.loading = false
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          this.$toastr.success('Respuestas registradas', 'éxito')
          self.getCuestionario(self.asignacion_alumno_id)
        })
        .catch(r => {});
    },

      //crear respuestas
      createResponse(data){
        let self = this
        if(data.serie.tipo_serie == 'FV'){
            data.preguntas.forEach(p => {
              let r_correcto = p.respuestas.find(x=>x.respuesta_a.correcta)
              let response = p.respuestas[0].response
              //limpiar data
              p.respuestas.forEach(r=>{
                  r.correcto_alumno = 0
                  r.nota = 0
                  r.correcto = 0
                  
                  //r.respuesta = r.respuesta_a.respuesta
                  
                  if(response == r.respuesta_a.respuesta && r.respuesta_a.correcta){
                        r.correcto = 1
                        r.nota = p.pregunta.nota
                  }

                  if(response == r.respuesta_a.respuesta){
                      r.correcto_alumno = 1
                      r.respuesta = response
                  }
              })

              p.nota = self.getNote(p.respuestas)
            })

            data.nota = self.getNote(data.preguntas)
        }else if(data.serie.tipo_serie == 'RM'){
            data.preguntas.forEach(p=>{

                let r_correcto = p.respuestas.filter(x=>x.respuesta_a.correcta && (x.respuesta_a.correcta == !!+x.correcto_alumno))
                let r_c = p.respuestas.filter(x=>x.respuesta_a.correcta)
                let r_a = p.respuestas.filter(x=>x.correcto_alumno && !x.respuesta_a.correcta)
                
                let nota_prom = (p.pregunta.nota / r_c.length).toFixed(2)

                p.respuestas.forEach(r => {
                    //r.respuesta = r.respuesta_a.respuesta
                    if(r_correcto.find(x=>x.id == r.id)){
                        r.correcto = 1
                        r.nota = nota_prom
                    }
                })

                let total_nota_p = self.getNote(p.respuestas).toFixed()

                if(total_nota_p > 0){
                    total_nota_p = total_nota_p - (r_a.length * nota_prom)
                }

                p.nota = total_nota_p > p.pregunta.nota ? data.pregunta.nota : total_nota_p

            })

            let total_nota = self.getNote(data.preguntas).toFixed(2)
            data.nota = parseFloat(total_nota) > parseFloat(data.serie.nota) ? data.serie.nota : total_nota
        }
        return data   
      },

      nextSection(value,serie){
        let self = this
        self.$validator.validateAll().then((result) => {
            if (result) {
                let data = self.createResponse(serie)
                let confirm_message = 'Por favor, verifiqué sus respuestas antes de pasar a la siguiente serie, ya no será posible volver a una serie anterior'

                if(serie.serie.tipo_serie !== "PD"){
                    self.$confirm(confirm_message).then(res => {
                        self.e1 = value
                        self.update(data)
                    }).catch(cancel =>{})
                }else{
                    self.update(data)
                }
            }
        })
      },

      validateSection(serie){
        let self = this
        return true
        let valid = false
        if(serie.serie.tipo_serie !== 'PD'){
            for (var p of serie.preguntas) {
                valid = p.respuestas.some(x=>x.respuesta !== null)
                if(!valid)
                    break
            }
        }else{
            valid = true
        }
        console.log(serie)
        return valid
      },

    getImage(foto){
        let self = this
        return foto !== null ? self.$store.state.base_url+'documentos/'+foto : self.$store.state.base_url+'img/user_empty.png'
    },
  },

  computed: {
      itemsB(){
        let self = this
        var user = self.$store.state.usuario
        if(!_.isEmpty(user) && self.asignacion !== null){
                return [
                    {text: "GRADO Y CURSOS",disabled: false,href: "#/cursos_alumnos_index/"+user.user_info.id},
                    {text: "ASIGNACIONES",disabled: false, href: "#/info_cursos_alumnos/"+self.asignacion.inscripcion_id+'/curso/'+self.asignacion.asignacion.asignar_curso_profesor.curso_grad_niv_edu_id},
                    {text: "RESPONDER",disabled: true, href: "#/"}
            ]
        }
    }
  },
};
</script>