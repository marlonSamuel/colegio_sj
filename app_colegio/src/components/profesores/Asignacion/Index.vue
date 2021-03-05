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
                         <v-toolbar flat color="white">
                          <v-toolbar-title> <v-icon color="blue">note_add</v-icon> Asignaciones </v-toolbar-title>
                            <v-spacer></v-spacer>
                            
                            <v-spacer></v-spacer>
                            <v-dialog v-model="dialog" max-width="1300px" persistent>
                            <template v-slot:activator="{ on }">
                                <v-btn color="primary" small dark class="mb-2" v-on="on"><v-icon>add</v-icon> Nuevo</v-btn>
                            </template>
                            <v-card>
                                <v-card-title>
                                <span class="headline" v-if="periodo !== null">{{setTitle}} {{periodo.periodo_academico.nombre}}</span>
                                </v-card-title>
                    
                                <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12 sm4 md4>
                                            <v-select
                                                v-model="form.cuestionario"
                                                placeholder="tipo de asignación"
                                                v-validate="'required'"
                                                :items="tipo_asignacion"
                                                label="Tipo asignación"
                                                data-vv-name="tipo_asignacion"
                                                :error-messages="errors.collect('tipo_asignacion')"
                                                ></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm8 md8>
                                            <v-text-field v-model="form.titulo" 
                                                label="Título"
                                                v-validate="'required|max:50|min:5'"
                                                type="text"
                                                data-vv-name="titulo"
                                                :error-messages="errors.collect('titulo')">
                                            </v-text-field>
                                        </v-flex>

                                        <v-flex xs12 sm3 md3>
                                            <v-text-field v-model="form.fecha_habilitacion" 
                                                label="Fecha habilita"
                                                v-validate="'required'"
                                                type="date"
                                                data-vv-name="fecha_habilita"
                                                :error-messages="errors.collect('fecha_habilita')">
                                            </v-text-field>
                                        </v-flex>


                                        <v-flex xs12 sm3 md3>
                                            <v-text-field v-model="form.fecha_entrega" 
                                                label="Fecha entrega"
                                                v-validate="'required'"
                                                type="date"
                                                data-vv-name="fecha_entrega"
                                                :error-messages="errors.collect('fecha_entrega')">
                                            </v-text-field>
                                        </v-flex>

                                        
                                        <v-flex xs12 sm3 md3>
                                            <v-text-field v-model="form.nota" 
                                                label="Nota (pts)"
                                                v-validate="'required|decimal'"
                                                type="text"
                                                data-vv-name="nota"
                                                :error-messages="errors.collect('nota')">
                                            </v-text-field>
                                        </v-flex>

                                        <v-flex xs12 sm3 md3>
                                            <v-switch
                                                v-model="form.entrega_tarde"
                                                :label="`Podrá entregarse tarde: ${form.entrega_tarde === 0 ?'No':'Si'}`"
                                            ></v-switch>
                                        </v-flex>

                                        <v-flex xs12 sm5 md5>
                                            <v-tooltip top>
                                                <template v-slot:activator="{ on }">
                                                    <v-switch
                                                        v-on="on"
                                                        v-model="form.flag_tiempo"
                                                        :label="`Asignar examen con tiempo (minutos): ${form.flag_tiempo === 0 ?'No':'Si'}`"
                                                    ></v-switch>
                                                </template>
                                                <span>Asignar examen con tiempo en minutos, 
                                                    dentro del tiempo designado, los estudiantes deberán resolver el examenes</span>
                                            </v-tooltip>
                                        </v-flex>

                                        <v-flex xs12 sm3 md3>
                                            <v-text-field v-model="form.tiempo" v-if="form.flag_tiempo"
                                                label="Tiempo (minutos)"
                                                v-validate="'required|integer'"
                                                type="text"
                                                data-vv-name="tiempo"
                                                :error-messages="errors.collect('tiempo')">
                                            </v-text-field>
                                        </v-flex>

                                        <v-flex xs12 sm9 md9>
                                            <v-textarea v-model="form.descripcion" 
                                                label="Descripción"
                                                v-validate="'required|min:5|max:500'"
                                                type="text"
                                                data-vv-name="descripcion"
                                                :error-messages="errors.collect('descripcion')">
                                            </v-textarea>
                                        </v-flex>
                                        <v-flex xs12 sm3 md3>
                                            <div id="uploader">
                                                <v-tooltip top>
                                                        <template v-slot:activator="{ on }">
                                                            <v-icon v-on="on" color="error" @click="$refs.file.click()">attach_file</v-icon>
                                                            {{form.file == null  ? 'Seleccionar archivo': form.file.name }}
                                                        </template>
                                                        <span>Adjuntar documento pdf</span>
                                                    </v-tooltip>
                                                <input  v-show="false" @change="selectedDocumento" ref="file" class="input-file hidden" type="file" accept="application/pdf"/>
                                                
                                                </div>
                                        </v-flex>
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
                              <h5 v-if="curso !== null && periodo !== null">
                                 <hr />
                                NIVEL EDUCATIVO: {{curso.curso_grado_nivel.grado_nivel_educativo.nivel_educativo.nombre | uppercase}} <br />
                                GRADO: {{curso.curso_grado_nivel.grado_nivel_educativo.grado.nombre | uppercase}} <br />
                                CURSO: {{curso.curso_grado_nivel.curso.nombre | uppercase}} <br />
                                BIMESTRE / CICLO EN CURSO: {{periodo.periodo_academico.nombre | uppercase}} / {{ciclo}}<br />
                            </h5>
                            
                        </v-flex>
                        <v-data-table
                                :headers="headers"
                                :items="items"
                                :search="search"
                                :expand="false"
                                class="elevation-1"
                                disable-initial-sort
                            >
                                <template v-slot:items="props">
                                    <tr>
                                        <td class="text-xs-left">
                                            <v-icon @click="props.expanded = !props.expanded">{{!props.expanded ? 'keyboard_arrow_right' : 'keyboard_arrow_down' }} </v-icon>
                                            {{props.item.titulo}}
                                        </td>
                                        <td class="text-xs-left">{{props.item.cuestionario ? 'CUESTIONARIO' : 'TAREA'}}</td>
                                        <td class="text-xs-left">{{props.item.fecha_entrega | moment('DD/MM/YYYY')}}</td>
                                        <td class="text-xs-left">{{props.item.nota}} pts</td>
                                        <td class="text-xs-left">
                                            <v-tooltip top v-if="props.item.flag_tiempo">
                                                <template v-slot:activator="{ on }">
                                                    <v-icon color="blue" @click="$router.push(`/view_asignacion/curso/`+curso_id+'/asignacion/'+props.item.id)" v-on="on"> visibility</v-icon>
                                                </template>
                                                <span>Visualizar cuestionario</span>
                                            </v-tooltip>
                                            <v-tooltip top v-if="props.item.flag_tiempo">
                                                <template v-slot:activator="{ on }">
                                                    <v-icon color="blue" @click="$router.push(`/serie/`+curso_id+'/asignacion/'+props.item.id)" v-on="on"> question_answer</v-icon>
                                                </template>
                                                <span>Configurar series, preguntas y respuestas de cuestionario</span>
                                            </v-tooltip>
                                            <v-tooltip top v-if="!props.item.responsable">
                                                <template v-slot:activator="{ on }">
                                                    <v-icon color="green" @click="$router.push(`/asignacion_nota/`+curso_id+'/asignacion/'+props.item.id)" v-on="on"> note_add</v-icon>
                                                </template>
                                                <span>Asignar nota </span>
                                            </v-tooltip>
                                            <v-tooltip top>
                                                <template v-slot:activator="{ on }">
                                                    <v-icon color="warning" @click="edit(props.item)" v-on="on"> edit</v-icon>
                                                </template>
                                                <span>Editar</span>
                                            </v-tooltip>
                                            <v-tooltip top v-if="!props.item.responsable">
                                                <template v-slot:activator="{ on }">
                                                    <v-icon color="error" @click="destroy(props.item)" v-on="on"> remove_circle</v-icon>
                                                </template>
                                                <span>Quitar </span>
                                            </v-tooltip>
                                        </td>
                                    </tr>
                                </template>
                                <template v-slot:expand="props">
                                    <v-card flat>
                                    <v-card-text>
                                        <hr /><hr />
                                        <v-container fluid grid-list-md>
                                            <v-layout wrap>
                                                <v-flex xs12 md12 lg12>
                                                    <h3>Información de la asignación</h3>
                                                                    <hr />
                                                </v-flex>
                                                <v-flex xs12 md12 lg12>
                                                    <b>Tipo asignación: </b> {{props.item.cuestionario ? 'CUESTIONARIO' : 'TAREA'}}<br />
                                                    <b>Título: </b>{{props.item.titulo}} <br />
                                                    <b>Fecha habilita: </b> {{props.item.fecha_habilitacion | moment('DD/MM/YYYY')}} <br />
                                                    <b>Fecha entrega:</b> {{props.item.fecha_entrega | moment('DD/MM/YYYY')}}<br />
                                                    <b>Nota:</b> {{props.item.nota}} pts <br />
                                                    <b>Podrá entregarse tarde: </b> {{props.item.entrega_tarde ? 'SI' : 'NO'}} <br />
                                                    <b>Adjunto: </b> 
                                                        <span>{{getNameFile(props.item.adjunto)}}</span>
                                                        <v-tooltip top v-if="props.item.adjunto !== null && props.item.adjunto !==''">
                                                            
                                                            <template v-slot:activator="{ on }">
                                                                <v-icon color="error" @click="verAdjunto(props.item.adjunto)" v-on="on"> file_download_off</v-icon>
                                                            </template>
                                                            <span>Descargar archivo adjunto</span>
                                                        </v-tooltip>
                                                     <br />
                                                    <b>Descripcion:</b> {{props.item.descripcion}} <br />
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
import moment from 'moment'
export default {
  name: "PanelProfesor",
  props: {
      source: String
    },
  data() {
    return {
        loading: false,
        dialog: false,
        search: '',
        ciclo_id: null,
        curso_id: null,
        curso: null,
        periodo: null,
        items: [],
        headers: [
            {text: 'Titulo',value: '',sortable: false},
            {text: 'Tipo de asignación',value: '',sortable: false},
            {text: 'Fecha entrega', value: '', sortable: false},
            {text: 'Nota', value: '', sortable: false},
            {text: 'Acciones', value: '', sortable: false}
        ],
        tipo_asignacion: [
            {text: 'Tarea',value: 0},
            {text: 'Cuestionario', value: 1}
        ],
        form:{
            id: null,
            asignar_curso_profesor_id: null,
            ciclo_periodo_academico_id: null,
            cuestionario: null,
            nota: null,
            titulo: '',
            descripcion: '',
            fecha_entrega: null,
            fecha_habilitacion: null,
            tiempo: 0,
            flag_tiempo: 0,
            entrega_tarde: 0,
            adjunto: '',
            file: null,
            file_name: ''
        }
    }
  },

  created() {
    let self = this
    self.curso_id = self.$route.params.id
    self.getAll(self.$route.params.id)
    self.get(self.$route.params.id)
    self.getPeriodos()
  },

  methods: {
      getAll(id){
          let self = this
            self.loading = true
            self.$store.state.services.asignacionProfesorService
            .getAsignaciones(id)
            .then(r => {
                self.loading = false
                if (self.$store.state.global.captureError(r)) {
                    return
                }
                self.items = r.data
            }).catch(e => {

            })
      },

     //obtener registro
      get(id){
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

    //obtener bimestre actual
      getPeriodos(id){
          let self = this
            self.loading = true
            self.$store.state.services.cicloService
            .getPeriodos(self.$store.state.ciclo.id)
            .then(r => {
                self.loading = false
                if (self.$store.state.global.captureError(r)) {
                    return
                }
                self.periodo = r.data.find(x=>x.actual)

                if(self.periodo == null || self.periodo == undefined){
                    let now = moment()
                    self.periodo = r.data.find(x=>moment(now).isBetween(moment(x.inicio), moment(x.fin), undefined,'[]'))
                }
            }).catch(e => {

            })
      },

    //funcion para guardar registro
    create(){
      let self = this
      self.loading = true
      let data = self.getFormData(self.form)
      self.$store.state.services.asignacionService
        .create(data)
        .then(r => {
          self.loading = false
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          this.$toastr.success('asignación agregada con éxito', 'éxito')
          self.getAll(self.$route.params.id)
          self.close()

        })
        .catch(r => {});
    },

    getFormData(object) {
        const formData = new FormData()
        Object.keys(object).forEach(key => formData.append(key, object[key]))
        return formData;
    },

    //documento
    selectedDocumento() {
      let self = this
      var input = document.querySelector("#uploader .input-file")
      var files = input.files
      self.form.file = files[0]
      console.log(self.form)
      var oFReader = new FileReader();
      oFReader.readAsDataURL(files[0]);

      /*oFReader.onload = function (oFREvent) {
          self.form.file_name = oFREvent.target.result
          console.log(self.form.file.name)
      }*/
    },

    //funcion para actualizar registro
    update(){
      let self = this
      self.loading = true
      let id = self.form.id
      let data = self.getFormData(self.form)
       
      self.$store.state.services.asignacionService
        .updateData(id,data)
        .then(r => {
          self.loading = false
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          self.getAll(self.$route.params.id)
          this.$toastr.success('asignación actualizada con éxito', 'éxito')
          self.close()
        })
        .catch(r => {});
    },

     //funcion para eliminar registro
    destroy(data){
      let self = this
      self.$confirm('Seguro que desea eliminar asignación, si existen alumnos que ya registraron sus respuestas se eliminarán definitivamente?').then(res => {
        self.loading = true
            self.$store.state.services.asignacionService
            .destroy(data)
            .then(r => {
                self.loading = false
                if (self.$store.state.global.captureError(r)) {
                  return;
                }
                self.getAll(self.$route.params.id)
                this.$toastr.success('asignación eliminado con exito', 'exito')
                self.clearData()
            })
            .catch(r => {});

      }).catch(cancel =>{

      })
    },

      //funcion, validar si se guarda o actualiza
    createOrEdit(){
      let self = this
      this.$validator.validateAll().then((result) => {
          if (result) {
              self.form.asignar_curso_profesor_id = self.$route.params.id
              self.form.entrega_tarde  = self.form.entrega_tarde  ? 1 : 0
              self.form.flag_tiempo = self.form.flag_tiempo ? 1 : 0
              self.form.ciclo_periodo_academico_id = self.periodo.id
              if(!self.form.flag_tiempo){
                  self.form.tiempo = 0
              }

              if(self.form.id > 0 && self.form.id !== null){
                self.update()
              }else{
                self.create()
              }
           }
      })
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
        self.form.titulo = data.titulo
        self.form.descripcion = data.descripcion
        self.form.asignar_curso_profresor_id = data.asignar_curso_profresor_id
        self.form.nota = data.nota
        self.form.entrega_tarde = data.entrega_tarde
        self.form.fecha_habilitacion = data.fecha_habilitacion
        self.form.fecha_entrega = data.fecha_entrega
        self.form.cuestionario = data.cuestionario
        self.form.file_name = data.adjunto
        self.form.flag_tiempo = data.flag_tiempo
        self.form.tiempo = data.tiempo
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

        self.$validator.reset()
    },

    close () {
        let self = this
        self.dialog = false
        self.clearData()
    },

    verAdjunto(adjunto){
      let self = this
      var url = self.$store.state.base_url+'documentos/'+adjunto
      window.open(url, '_blank');
    },

    getNameFile(adjunto){
        let self = this
        if(adjunto !== null && adjunto !== "" && adjunto !== undefined){
            return adjunto.split("/")[1]
        }
    }
  },

  computed: {
    setTitle(){
      let self = this
      return self.form.id !== null ? self.form.nombre : 'Nueva asignación'
    },

    ciclo(){
        let self = this
        return self.$store.state.ciclo.ciclo
    },

    itemsB(){
        let self = this
        return [
        { text: "CURSOS",disabled: false, href: "#/cursos_index"},
        { text: "ASIGNACIONES",disabled: true, href: "#"}
      ]
    }
  },
};
</script>