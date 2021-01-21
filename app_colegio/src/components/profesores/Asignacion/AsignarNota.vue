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
                    <v-flex>
                        
                    <v-toolbar flat color="white" v-if="asignacion !== null">
                            <v-toolbar-title > <v-icon color="blue">note</v-icon> 
                                    ASIGNAR NOTAS {{asignacion.titulo | uppercase}}
                            </v-toolbar-title>
                            <v-dialog v-model="dialog" max-width="800px" persistent>
                                <v-card>
                                    <v-card-title>
                                    <span class="headline">Calificar</span>
                                    </v-card-title>
                        
                                    <v-card-text>
                                    <v-container grid-list-md>
                                        <v-layout wrap>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field v-model="form.nota" 
                                                label="Nota"
                                                v-validate="'required|min_value:0|max_value:'+asignacion.nota"
                                                type="text"
                                                data-vv-name="nota"
                                                :error-messages="errors.collect('nota')">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm12 md12>
                                            <v-textarea v-model="form.observaciones" 
                                                label="Observaciones"
                                                type="text">
                                            </v-textarea>
                                        </v-flex>
                                        </v-layout>
                                    </v-container>
                                    </v-card-text>
                        
                                    <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="red darken-1" flat @click="close">Volver</v-btn>
                                    <v-btn color="blue darken-1" flat @click="beforeEdit">Guardar</v-btn>
                                    </v-card-actions>
                                </v-card>
                                </v-dialog>
                        </v-toolbar>
                        <v-flex v-if="asignacion !== null">
                            <h4 v-if="curso !== null">
                                <hr />
                                <br />
                                NIVEL EDUCATIVO: {{curso.curso_grado_nivel.grado_nivel_educativo.nivel_educativo.nombre | uppercase}} <br />
                                GRADO: {{curso.curso_grado_nivel.grado_nivel_educativo.grado.nombre | uppercase}} <br />
                                CURSO: {{curso.curso_grado_nivel.curso.nombre | uppercase}} <br />
                                FECHA ENTREGA: {{asignacion.fecha_entrega | moment('DD/MM/YYYY')}}<br />
                                PUEDE ENTREGARSE TARDE: {{asignacion.entrega_tarde ? 'SI':'NO'}}
                                <br />
                                <br />
                                <hr />
                            </h4>
                     </v-flex>
                      <v-data-table
                        :headers="headers"
                        :items="items"
                        :search="search"
                        :pagination.sync="pagination"
                        class="elevation-1"
                    >
                        <template v-slot:items="props">
                            <td class="text-xs-left">
                                <v-icon @click="props.expanded = !props.expanded">{{!props.expanded ? 'keyboard_arrow_right' : 'keyboard_arrow_down' }} </v-icon>
                                {{props.item.inscripcion.alumno.codigo}}
                            </td>
                            <td class="text-xs-left">{{props.item.inscripcion.alumno.primer_nombre}}
                                                     {{props.item.inscripcion.alumno.segundo_nombre}}
                                                     {{props.item.inscripcion.alumno.primer_apellido}}
                                                     {{props.item.inscripcion.alumno.segundo_apellido}}
                            </td>
                            <td class="text-xs-left">
                                <span v-if="!props.item.entregado" class="red--text"> sin entregar </span>
                                <span v-else class="yellow--text"> Entregado </span>
                                <span v-if="props.item.calificado" class="green--text"> Calificado </span>
                            </td>
                            <td class="text-xs-left">{{props.item.nota}} pts</td>
                            <td class="text-xs-left">
                                <v-tooltip top v-if="props.item.adjunto !== null">
                                    <template v-slot:activator="{ on }">
                                        <v-icon color="red" @click="" v-on="on"> file_download_off</v-icon>
                                    </template>
                                    <span>Descargar respuesta</span>
                                </v-tooltip>
                                <v-tooltip top v-if="asignacion !== null && asignacion.flag_tiempo">
                                        <template v-slot:activator="{ on }">
                                            <v-icon color="blue" @click="$router.push('/view_cuestionario/curso/'+curso_id+'/asignacion_alumno/'+props.item.id)" v-on="on"> visibility</v-icon>
                                        </template>
                                        <span>Ver resultados y calificar</span>
                                </v-tooltip>
                                <span v-if="!props.item.entregado" class="red--text"> sin entregar </span>
                            </td>
                            <td class="text-xs-left">
                                <v-tooltip top v-if="asignacion !== null && !asignacion.flag_tiempo">
                                        <template v-slot:activator="{ on }">
                                            <v-icon color="green" @click="edit(props.item)" v-on="on"> note_add</v-icon>
                                        </template>
                                        <span>Calificar</span>
                                </v-tooltip>
                            </td>
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
                                                    <b>Nota:</b> {{props.item.nota}} pts <br />
                                                    <b>Entregado:</b> {{props.item.entregado ? 'SI': 'NO'}} <br />
                                                    <b>Calificado:</b> {{props.item.calificado ? 'SI': 'NO'}} <br />
                                                    <b>Observaciones: </b> 
                                                        <span>{{props.item.observaciones}}</span>
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
  name: "ListaAlumnos",
  props: {
      source: String
    },
  data() {
    return {
        loading: false,
        dialog: false,
        search: '',
        curso_id: null,
        curso: null,
        asignacion_id: null,
        asignacion: null,
        items: [],
        headers: [
            { text: 'Codigo alumno', value: 'inscripcion.alumno.codigo' },
            { text: 'Alumno', value: 'alumno' },
            { text: 'Estado', value: '' },
            { text: 'Nota', value: '' },
            { text: 'Respuesta', value: '' },
            { text: 'Acciones', value: '', sortable: false }
        ],

      pagination: {
        sortBy: 'id'
      },

      form: {
          id: null,
          nota: null,
          observaciones: ''
      }
    }
  },

  created() {
    let self = this
    self.curso_id = self.$route.params.curso_id
    self.asignacion_id = self.$route.params.id

    self.getCurso(self.curso_id)
    self.getAsignacion(self.asignacion_id)
    self.getAll(self.asignacion_id)
  },
  
  methods: {
       //obtener registro
      getAll(id){
          let self = this
            self.loading = true
            self.$store.state.services.asignacionService
            .getAlumnos(id)
            .then(r => {
                self.loading = false
                if (self.$store.state.global.captureError(r)) {
                    return
                }
                self.items = r.data
                console.log(seslf.items)
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
          self.getAll(self.asignacion_id)
          this.$toastr.success('registro actualizado con éxito', 'éxito')
          self.close()
        })
        .catch(r => {});
    },

      beforeEdit(){
        let self = this
            this.$validator.validateAll().then((result) => {
            if (result) {
                self.update()
            }
        })
      },

      edit(data){
          let self = this
          self.dialog = true
          self.form.id = data.id
          self.form.nota = data.nota
          self.form.observaciones = data.observaciones

      },

      close(){
          let self = this
          self.dialog = false
      }
  },

  computed: {
      itemsB(){
        let self = this
        return [
            { text: "CURSOS",disabled: false, href: "#/cursos_index"},
            { text: "ASIGNACIONES",disabled: false, href: "#/asignacion_index/"+self.curso_id},
            {text: "ASIGNACION NOTAS",disabled: true,href: "#"}
      ]
    }
  }
};
</script>