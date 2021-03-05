<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
        <v-container
            fluid
            grid-list-md
        >
            <v-card>
                <v-layout row wrap>
                    <v-flex sm12 md12 lg12>
                         <v-layout row wrap justify-end>
                                <div>
                                <v-breadcrumbs :items="itemsB">
                                    <template v-slot:divider>
                                    <v-icon>forward</v-icon>
                                    </template>
                                </v-breadcrumbs>
                                </div>
                            </v-layout>
                            <v-toolbar flat color="white" v-if="serie !== null">
                                <v-toolbar-title v-if="asignacion !== null"> <v-icon color="blue">settings</v-icon> Configurar preguntas 
                                    {{asignacion.titulo | lowercase}} serie 
                                    
                                    <span v-if="serie.tipo_serie =='FV'">falso/verdadero</span>
                                    <span v-if="serie.tipo_serie =='RM'">respuesta multiple</span>
                                    <span v-if="serie.tipo_serie =='PD'">rreguntas directas</span> {{serie.nota}} (pts)
                                </v-toolbar-title>
                                
                                <v-spacer></v-spacer>
                                <v-dialog v-model="dialog" max-width="1200px" persistent>
                                <template v-slot:activator="{ on }">
                                    <v-btn color="primary" small dark class="mb-2" v-on="on" ><v-icon>add</v-icon> Nuevo</v-btn>
                                </template>
                                <v-card>
                                    <v-card-title>
                                    <span class="headline">{{setTitle}}</span>
                                    </v-card-title>
                        
                                    <v-card-text>
                                    <v-container grid-list-md>
                                        <v-layout wrap>
                                        <v-flex xs12 sm9 md9>
                                            <v-textarea v-model="form.pregunta" 
                                                label="Pregunta"
                                                v-validate="'required|min:10|max:250'"
                                                type="text"
                                                data-vv-name="pregunta"
                                                :error-messages="errors.collect('pregunta')">
                                            </v-textarea>
                                        </v-flex>
                                        <v-flex xs12 sm2 md2>
                                            <v-text-field v-model="form.nota" 
                                                label="Nota (pts)"
                                                v-validate="'required|decimal|min_value:0.5|max_value:'+totalPoints()"
                                                type="text"
                                                data-vv-name="nota"
                                                :error-messages="errors.collect('nota')">
                                            </v-text-field>
                                        </v-flex>

                                         <v-flex xs12 sm12 md12>
                                            <div id="uploader">
                                                <v-tooltip top>
                                                        <template v-slot:activator="{ on }">
                                                            <v-icon v-on="on" color="error" @click="$refs.file.click()">attach_file</v-icon>
                                                            {{form.file == null  ? 'Seleccionar imagen': form.file.name }}
                                                        </template>
                                                        <span>Adjuntar imagen</span>
                                                    </v-tooltip>
                                                <input  v-show="false" @change="selectedDocumento" ref="file" class="input-file hidden" type="file" accept="image/x-png,image/gif,image/jpeg"/>
                                                
                                                </div>
                                        </v-flex>

                                        <div v-if="serie.tipo_serie == 'FV'">
                                            <h4>Respuesta</h4>
                                            <v-flex>
                                                <v-radio-group v-model="valueFV" row>
                                                    <v-radio label="Falso" value="F"></v-radio>
                                                    <v-radio label="Verdadero" value="V"></v-radio>
                                                </v-radio-group>
                                            </v-flex>
                                        </div>
                                        <v-layout v-if="serie.tipo_serie == 'RM'" wrap>
                                            <v-flex xs12 sm8 md8>
                                                    <v-text-field v-model="valueRM" 
                                                        label="Posible respuesta"
                                                        type="text">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm4 md4>
                                                    <v-btn small color="success" @click="addResponse()"> <v-icon>add</v-icon> Agregar
                                                    </v-btn>
                                                </v-flex>

                                                <v-flex xs12 sm3 md3 v-for="res in form.respuestas_show" :key = res.respuesta>
                                                        <v-checkbox 
                                                            v-model="res.correcta" 
                                                            :label="res.respuesta"></v-checkbox>
                                                        <v-icon color="red" @click="removeResponse(res)">reremove_circleove</v-icon>
                                                </v-flex>
                                        </v-layout>

                                        <v-layout v-if="serie.tipo_serie == 'PD'">
                                            <v-textarea v-model="form.valuePD" 
                                                label="Respuesta"
                                                v-validate="'required|min:10|max:500'"
                                                type="text"
                                                data-vv-name="respuesta"
                                                :error-messages="errors.collect('respuesta')">
                                            </v-textarea>
                                        </v-layout>
                                        </v-layout>
                                    </v-container>
                                    </v-card-text>
                        
                                    <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="red darken-1" flat @click="close">Volver</v-btn>
                                    <v-btn color="blue darken-1" flat @click="createOrEdit">Guardar</v-btn>
                                    </v-card-actions>
                                </v-card>
                                </v-dialog>
                            </v-toolbar>
                            <v-flex>
                                    <h5 v-if="curso !== null">
                                        <hr />
                                    NIVEL EDUCATIVO: {{curso.curso_grado_nivel.grado_nivel_educativo.nivel_educativo.nombre | uppercase}} <br />
                                    GRADO: {{curso.curso_grado_nivel.grado_nivel_educativo.grado.nombre | uppercase}} <br />
                                    CURSO: {{curso.curso_grado_nivel.curso.nombre | uppercase}}
                                </h5>
                                
                            </v-flex>
                            <v-data-table
                                :headers="headers"
                                :items="items"
                                :search="search"
                                :pagination.sync="pagination"
                                class="elevation-1"
                            >
                                <template v-slot:items="props">
                                <td class="text-xs-left"><v-icon @click="props.expanded = !props.expanded">{{!props.expanded ? 'keyboard_arrow_right' : 'keyboard_arrow_down' }} </v-icon>
                                    {{ props.item.pregunta }}
                                </td>
                                <td class="text-xs-left">
                                    {{ props.item.nota }} (pts)
                                </td>
                                <td class="text-xs-left">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-icon v-on="on"  color="warning" fab dark @click="edit(props.item)"> edit</v-icon>
                                        </template>
                                        <span>Editar</span>
                                    </v-tooltip>
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-icon v-on="on" color="error" fab dark @click="destroy(props.item)"> delete</v-icon>
                                        </template>
                                        <span>Eliminar</span>
                                    </v-tooltip>
                                </td>
                                </template>
                                <template v-slot:expand="props">
                                    <v-card flat>
                                        <v-card-text>
                                            <hr /><hr />
                                            <v-container>
                                                <v-layout wrap>
                                                    <v-flex xs12 md12 lg12>
                                                        <b>{{ props.index + 1 }}. {{props.item.pregunta}}</b><br /><br />
                                                        <v-flex xs12 sm6 v-if="props.item.adjunto !== null" offset-sm3>
                                                            <enlargeable-image :src="getImage(props.item.adjunto)" :src_large="getImage(props.item.adjunto)">
                                                                  <v-avatar
                                                                      :tile="true"
                                                                      size="300"
                                                                      >
                                                                      <img :src="getImage(props.item.adjunto)" />
                                                                  </v-avatar>
                                                              </enlargeable-image><br />
                                                          </v-flex>
                                                        <div v-if="serie.tipo_serie == 'FV'">
                                                            <v-radio-group v-model="row" row readonly :value="FVResponse(props.item.respuestas)">
                                                                <v-radio label="Falso" value="F"></v-radio>
                                                                <v-radio label="Verdadero" value="V"></v-radio>
                                                            </v-radio-group>
                                                        </div>
                                                        <div v-if="serie.tipo_serie == 'RM'">
                                                            <v-layout wrap>
                                                                <v-flex xs12 sm3 md3 v-for="res in props.item.respuestas" :key = res.respuesta>
                                                                        <v-checkbox 
                                                                            v-model="res.correcta" 
                                                                            :label="res.respuesta" readonly></v-checkbox>
                                                                </v-flex>
                                                            </v-layout>
                                                        </div>
                                                        <div v-if="serie.tipo_serie == 'PD'">
                                                            <span>R/ {{props.item.respuestas[0].respuesta }}</span>
                                                        </div>
                                                        
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                            <hr /><hr />
                                        </v-card-text>
                                        </v-card>
                                    </template>
                                <template v-slot:no-data>
                                <v-btn color="primary" @click="getAll">Reset</v-btn>
                                </template>
                            </v-data-table>
                    </v-flex>
                </v-layout>
            </v-card>
        </v-container>
       
    </v-flex>
  </v-layout>
</template>
<script>

import EnlargeableImage from '@diracleo/vue-enlargeable-image'
export default {
  name: "Pregunta",
  props: {
      source: String
    },
    components:{
      EnlargeableImage
    },
  data() {
    return {
      dialog: false,
      row: "null",
      search: '',
      curso_id: null,
      curso: null,
      serie_id: null,
      serie: null,
      asignacion_id: null,
      asignacion: null,
      loading: false,
      items: [],
      valueFV: 'V',
      valueRM: '',
      checkbox1: true,
      checkbox2: false,
      headers: [
        { text: 'Pregunta', value: 'pregunta' },
        { text: 'Nota', value: 'nota' },
        { text: 'Acciones', value: '' }
      ],

      pagination: {
        sortBy: 'id'
      },

      form: {
        id: null,
        pregunta_id: '',
        nota: null,
        respuestas: [],
        respuestas_show: [],
        valuePD: '',
        adjunto: '',
        file: null,
        file_name: ''
      },
    };
  },

  created() {
    let self = this
    self.curso_id = self.$route.params.curso_id
    self.asignacion_id = self.$route.params.asignacion_id
    self.serie_id = self.$route.params.id
    self.getAll()
    self.getSerie()
    self.getCurso(self.curso_id)
    self.getAsignacion(self.asignacion_id)
  },

  methods: {
    getAll() {
      let self = this
      self.loading = true

      self.$store.state.services.serieService
        .getPreguntas(self.serie_id)
        .then(r => {
          self.loading = false
          self.items = r.data
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
            self.$store.state.services.asignacionService
            .get(id)
            .then(r => {
                self.loading = false
                if (self.$store.state.global.captureError(r)) {
                    return
                }
                self.asignacion = r.data
            }).catch(e => {

            })
      },

    getSerie() {
      let self = this
      self.loading = true

      self.$store.state.services.serieService
        .get(self.serie_id)
        .then(r => {
          self.loading = false
          self.serie = r.data
        })
        .catch(r => {});
    },
    
    getFormData(object) {
        const formData = new FormData()
        Object.keys(object).forEach(key => formData.append(key, object[key]))
        return formData;
    },

    //funcion para guardar registro
    create(){
      let self = this
      self.loading = true
      self.form.respuestas = JSON.stringify(self.form.respuestas_show)

      let data = self.getFormData(self.form)
      self.$store.state.services.preguntaService
        .create(data)
        .then(r => {
          self.loading = false
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          this.$toastr.success('registro agregado con éxito', 'éxito')
          self.getAll()
          self.close()

        })
        .catch(r => {});
    },

    //funcion para actualizar registro
    update(){
      let self = this
      self.loading = true
      self.form.respuestas = JSON.stringify(self.form.respuestas_show)
      let data = self.getFormData(self.form)
       
      self.$store.state.services.preguntaService
        .updateData(self.form.id,data)
        .then(r => {
          self.loading = false
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          self.getAll()
          this.$toastr.success('registro actualizado con éxito', 'éxito')
          self.close()
        })
        .catch(r => {});
    },

    //funcion para eliminar registro
    destroy(data){
      let self = this
      self.$confirm('Seguro que desea eliminar pregunta?').then(res => {
        self.loading = true
            self.$store.state.services.preguntaService
            .destroy(data)
            .then(r => {
                self.loading = false
                if (self.$store.state.global.captureError(r)) {
                  return;
                }
                self.getAll()
                this.$toastr.success('registro eliminado con exito', 'exito')
                self.close()
            })
            .catch(r => {});

      }).catch(cancel =>{

      })
    },

    //limpiar data de formulario
    clearData(){
        let self = this

        Object.keys(self.form).forEach(function(key,index) {
          if(typeof self.form[key] === "string") 
            self.form[key] = ''
          else if (typeof self.form[key] === "boolean") 
            self.form[key] = true
          else if (typeof self.form[key] === "number") 
            self.form[key] = null
        });

        self.form.respuestas = []

        self.$validator.reset()
    },

    //editar registro
    edit(data){
        let self = this
        this.dialog = true
        self.mapData(data)   
    },

    //mapear datos a formulario
    mapData(data){
        let self = this
        self.form.id = data.id
        self.form.serie_id = data.serie_id
        self.form.nota = data.nota
        self.form.pregunta = data.pregunta
        self.form.file_name = data.adjunto
        
        if(self.serie.tipo_serie == "FV"){
            self.valueFV = data.respuestas.find(x=>x.correcta).respuesta
        }
        else if(self.serie.tipo_serie == "RM"){
            self.form.respuestas = data.respuestas
        }else{
            self.form.valuePD = self.form.respuesta[0].respuesta
        }
    },

    //funcion, validar si se guarda o actualiza
    createOrEdit(){
      let self = this
      this.$validator.validateAll().then((result) => {
          if (result) {
              self.form.serie_id = self.serie_id

              if(self.serie.tipo_serie !== 'RM'){
                self.form.respuestas_show = self.getResponses(self.serie.tipo_serie)
              }else{
                if(!self.form.respuestas_show.some(x=>x.correcta)){
                    self.$toastr.error("respuesta multiple debe llevar al menos una respuesta correcta","error");
                    return
                }
              }

              if(self.form.id > 0 && self.form.id !== null){
                self.update()
              }else{
                self.create()
              }
           }
      })

    },

    //obtener respuestas en array
    getResponses(type){
        let self = this
        let responses = []
        if(type == "FV"){
            responses.push({respuesta:'F', correcta: self.valueFV == 'F' ? 1 : 0})
            responses.push({respuesta:'V', correcta: self.valueFV == 'V' ? 1 : 0})
        }else{
            responses.push({respuesta: self.form.valuePD, correcta: 1})
        }
        return responses
    },

    //documento
    selectedDocumento() {
      let self = this
      var input = document.querySelector("#uploader .input-file")
      var files = input.files
      self.form.file = files[0]
      var oFReader = new FileReader();
      oFReader.readAsDataURL(files[0]);

      /*oFReader.onload = function (oFREvent) {
          self.form.file_name = oFREvent.target.result
          console.log(self.form.file.name)
      }*/
    },
    

    cancelar(){
      let self = this
    },

    close () {
        let self = this
        self.dialog = false
        self.clearData()
    },

    //respuestas
    FVResponse(data){
        let self = this
        self.row = data.find(x=>x.correcta).respuesta
    },

    //obtener faltate de puntos para conmpletar los puntos en la serie
    getFaltante(){
      let self = this
      if(self.items.length > 0){
        let f = self.totalPoints()
        return parseFloat(self.serie.nota) - f
      }else if(self.serie !== null){
        return self.serie.nota   
      }
    },

    totalPoints(){
        let self = this
        let nota = self.serie !== null ? self.serie.nota : 0
        var total = self.items.reduce(function(a, b) {
            return a + parseFloat(b.nota)
        }, 0)

        if(self.form.id !== null){
          let nota_e = self.items.find(x=>x.id == self.form.id).nota
          total = total - nota_e
        }
        return nota - total.toFixed(2);
    },

    //agregar respuestas multiples
    addResponse(){
        let self = this
        if(self.valueRM == ""){
            self.$toastr.error('el campo respuesta no puede esta vacio','error')
            return
        }

        if(self.valueRM.length > 25){
            self.$toastr.error('el campo respuesta no puede contener mas de 25 caracteres','error')
            return
        }

        self.form.respuestas_show.push({respuesta: self.valueRM, correcta: false})
        self.valueRM = ""
    },

    //remover respuestas multiples
    removeResponse(response){
        let self = this
        self.form.respuestas_show.splice(self.form.respuestas_show.indexOf(response),1)
    },

    getImage(foto){
      let self = this
      console.log(foto)
      return foto !== null ? self.$store.state.base_url+'documentos/'+foto : self.$store.state.base_url+'img/user_empty.png'
    }
  },

  computed: {
    setTitle(){
      let self = this
      return self.form.id !== null ? 'Editar pregunta' : 'Nuevo Registro'
    },

    itemsB(){
        let self = this
        return [
          { text: "CURSOS",disabled: false, href: "#/cursos_index"},
          { text: "ASIGNACIONES",disabled: false, href: "#/asignacion_index/"+self.curso_id},
          {text: "CUESTIONARIO",disabled: false, href: "#/serie/"+self.curso_id+"/asignacion/"+self.asignacion_id},
          {text: "PREGUNTAS",disabled: true, href: "#"}
      ]
    }
  },
};
</script>