<template>
  <v-layout justify-center v-loading="loading">
    <v-flex md12 lg12 sm12>
      <v-container
        fluid
        grid-list-md
      >
        
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
            <v-flex lg12 sm12 md12>
              
          <v-toolbar flat color="white">
            <v-toolbar-title v-if="asignacion !== null">
              <v-icon color="blue">settings</v-icon> Configurar series {{asignacion.titulo | lowercase}} {{asignacion.nota}} (pts)</v-toolbar-title>
           
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" max-width="800px">
              <template v-slot:activator="{ on }">
                <v-btn color="primary" small dark class="mb-2" v-on="on"><v-icon>add</v-icon> Nuevo</v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <span class="headline">{{setTitle}}</span>
                </v-card-title>
      
                <v-card-text>
                  <v-container grid-list-md>
                    <v-layout wrap>
                      <v-flex xs12 sm6 md6>
                        <v-select v-model="form.tipo_serie" 
                            label="Tipo serie"
                            v-validate="'required'"
                            :items="tipo_series"
                            data-vv-name="tipo_serie"
                            :error-messages="errors.collect('tipo_serie')">
                        </v-select>
                      </v-flex>
                      <v-flex xs12 sm6 md6>
                        <v-text-field v-model="form.nota" 
                            label="Nota"
                            v-validate="'required|decimal|min_value:0.5|max_value:'+totalPoints"
                            type="text"
                            data-vv-name="nota"
                            :error-messages="errors.collect('nota')">
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md12>
                        <v-textarea v-model="form.descripcion" 
                            label="Instrucciones"
                            v-validate="'required'"
                            type="text"
                            data-vv-name="descripcion"
                            :error-messages="errors.collect('descripcion')">
                        </v-textarea>
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
              <td class="text-xs-left">{{props.item.tipo_serie == 'FV' ? 'FALSO / VERDADERO' : (props.item.tipo_serie == 'RM' ? 'RESPUESTA MULTIPLE' : 'PREGUNTAS DIRECTAS')}}</td>
              <td class="text-xs-left">{{ props.item.nota }}</td>
              <td class="text-xs-left">{{ props.item.descripcion }}</td>
              <td class="text-xs-left">
                  <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-icon color="blue" @click="$router.push(`/pregunta/`+curso_id+'/asignacion/'+asignacion_id+'/serie/'+props.item.id)" v-on="on"> question_answer</v-icon>
                        </template>
                        <span>Agregar preguntas y respuestas</span>
                    </v-tooltip>
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
export default {
  name: "grado",
  props: {
      source: String
    },
  data() {
    return {
      dialog: false,
      search: '',
      curso_id: null,
      curso: null,
      asignacion_id: null,
      asignacion: null,
      loading: false,
      items: [],
      headers: [
        { text: 'Tipo serie', value: 'tipo_serie' },
        { text: 'Nota', value: 'nota' },
        { text: 'Instrucciones', value: 'descripcion' },
        { text: 'Acciones', value: '', sortable: false }
      ],

      pagination: {
        sortBy: 'id'
      },

      tipo_series: [
          {text:'Falso y verdadero',value: 'FV'},
          {text:'Respuesta multiple',value: 'RM'},
          {text:'Preguntas directas',value: 'PD'}
      ],

      form: {
        id: null,
        asignacion_id: '',
        tipo_serie: '',
        nota: null,
        descripcion: ''
      },
    };
  },

  created() {
    let self = this
    self.curso_id = self.$route.params.curso_id
    self.asignacion_id = self.$route.params.id
    self.getAll()
    self.getCurso(self.curso_id)
    self.getAsignacion(self.asignacion_id)
  },

  methods: {
     getAll() {
      let self = this
      self.loading = true

      self.$store.state.services.asignacionService
        .getSeries(self.asignacion_id)
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

    //funcion para guardar registro
    create(){
      let self = this
      self.loading = true
      let data = self.form

      self.$store.state.services.serieService
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
      let data = self.form
       
      self.$store.state.services.serieService
        .update(data)
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
      self.$confirm('Seguro que desea eliminar serie?').then(res => {
        self.loading = true
            self.$store.state.services.serieService
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
        self.form.descripcion = data.descripcion
        self.form.nota = data.nota
        self.form.tipo_serie = data.tipo_serie
    },

    //funcion, validar si se guarda o actualiza
    createOrEdit(){
      let self = this
      this.$validator.validateAll().then((result) => {
          if (result) {
              self.form.asignacion_id = self.asignacion_id
              if(self.form.id > 0 && self.form.id !== null){
                self.update()
              }else{
                self.create()
              }
           }
      })

    },
    

    cancelar(){
      let self = this
    },

    close () {
        let self = this
        self.dialog = false
        self.clearData()
    },
  },

  computed: {
    setTitle(){
      let self = this
      return self.form.id !== null ? 'Editar serie' : 'Nuevo Registro'
    },

    itemsB(){
        let self = this
        return [
          { text: "CURSOS",disabled: false, href: "#/cursos_index"},
          { text: "ASIGNACIONES",disabled: false, href: "#/asignacion_index/"+self.curso_id},
          {text: "CUESTIONARIO",disabled: true,href: "#"}
      ]
    },

    totalPoints(){
      let self = this
      let nota = self.asignacion !== null ? self.asignacion.nota : 0
      var total = self.items.reduce(function(a, b) {
          return a + parseFloat(b.nota)
      }, 0)
      
      if(self.form.id !== null){
        let nota_e = self.items.find(x=>x.id == self.form.id).nota
        total = total - nota_e
      }
      return parseFloat(nota - total.toFixed(2));
    },
  },
};
</script>