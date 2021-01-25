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
                        <v-flex>
                              <h5 v-if="curso !== null">
                                 <hr />
                                NIVEL EDUCATIVO: {{curso.curso_grado_nivel.grado_nivel_educativo.nivel_educativo.nombre | uppercase}} <br />
                                GRADO: {{curso.curso_grado_nivel.grado_nivel_educativo.grado.nombre | uppercase}} <br />
                                CURSO: {{curso.curso_grado_nivel.curso.nombre | uppercase}}
                                <hr />
                                <br />
                            </h5>
                            
                        </v-flex>
                         <v-toolbar flat color="white">
                          <v-toolbar-title> <v-icon color="blue">note_add</v-icon> Materiales de Apoyo </v-toolbar-title>
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
                                                v-model="form.link"
                                                placeholder="tipo de material"
                                                v-validate="'required'"
                                                :items="tipo_material"
                                                label="Tipo Material"
                                                data-vv-name="tipo_material"
                                                :error-messages="errors.collect('tipo_material')"
                                                ></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm8 md8>
                                            <v-text-field v-model="form.descripcion" 
                                                label="Descripcion"
                                                v-validate="'required|max:500|min:5'"
                                                type="text"
                                                data-vv-name="descripcion"
                                                :error-messages="errors.collect('descripcion')">
                                            </v-text-field>
                                        </v-flex>
                                                                                                                 
                                        <v-flex xs12 sm8 md8 v-if="!form.link">
                                            <div id="uploader">
                                                <v-tooltip top>
                                                        <template v-slot:activator="{ on }">
                                                            <v-icon v-on="on" color="error" @click="$refs.file.click()">attach_file</v-icon>
                                                            {{form.file == null  ? 'Seleccionar archivo': form.file.name }}
                                                        </template>
                                                        <span>Adjuntar documento pdf</span>
                                                    </v-tooltip>
                                                <input  v-show="false" @change="selectedDocumento" ref="file" class="input-file hidden" type="file" />
                                                
                                                </div>
                                        </v-flex>
                                        <v-flex xs12 sm8 md8 v-if="form.link">
                                            <v-text-field v-model="form.url" 
                                                label="Url de Referencial"
                                                v-validate="'required|max:150|min:5'"
                                                type="text"
                                                data-vv-name="url"
                                                :error-messages="errors.collect('url')">
                                            </v-text-field>
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
                                        <td class="text-xs-left">{{props.item.descripcion}}</td>
                                        <td class="text-xs-left">{{!props.item.link ? 'ARCHIVO' : 'URL'}}</td>
                                        <td class="text-xs-left">
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
  name: "PanelProfesor",
  props: {
      source: String
    },
  data() {
    return {
        loading: false,
        dialog: false,
        search: '',
        ciclos: [],
        ciclo_id: null,
        curso_id: null,
        curso: null,
        items: [],
        headers: [
            {text: 'Descripcion',value: '',sortable: false},
            {text: 'Tipo de Material',value: '',sortable: false},
            {text: 'Acciones', value: '', sortable: false}
        ],
        tipo_material: [
            {text: 'Adjunto',value: 0},
            {text: 'Url de Referencia', value: 1}
        ],
        form:{
            id: null,
            asignar_curso_profesor_id: null,
            ciclo_periodo_academico_id: null,
            descripcion: '',
            link: 0,
            url: '',
            adjunto: '',
            file: null,
            file_name: ''
        },
        periodo: null,
    }
  },

  created() {
    let self = this
    self.curso_id = self.$route.params.id
    self.getAll(self.$route.params.id)
    self.getInfo(self.$route.params.id)
    self.getPeriodos()
  },

  methods: {
      getInfo(id){
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
      getAll(id){
          let self = this
            self.loading = true
            self.$store.state.services.materialService
            .getAll(id)
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
            self.$store.state.services.materialService
            .get(id)
            .then(r => {
                self.loading = false
                if (self.$store.state.global.captureError(r)) {
                    return
                }
                self.mapData(r.data);
            }).catch(e => {

            })
      },

    //funcion para guardar registro
    create(){
      let self = this
      self.loading = true
      let data = self.getFormData(self.form)
      self.$store.state.services.materialService
        .create(data)
        .then(r => {
          self.loading = false
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          this.$toastr.success('Material agregado con éxito', 'éxito')
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
       
      self.$store.state.services.materialService
        .updateData(id,data)
        .then(r => {
          self.loading = false
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          self.getAll(self.$route.params.id)
          this.$toastr.success('Material actualizado con éxito', 'éxito')
          self.close()
        })
        .catch(r => {});
    },

     //funcion para eliminar registro
    destroy(data){
      let self = this
      self.$confirm('Seguro que desea eliminar material de apoyo?').then(res => {
        self.loading = true
            self.$store.state.services.materialService
            .destroy(data)
            .then(r => {
                self.loading = false
                if (self.$store.state.global.captureError(r)) {
                  return;
                }
                self.getAll(self.$route.params.id)
                this.$toastr.success('Material eliminado con exito', 'exito')
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
              self.form.ciclo_periodo_academico_id = self.periodo.id
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
        self.form.descripcion = data.descripcion
        self.form.asignar_curso_profesor_id = data.asignar_curso_profesor_id
        self.form.adjunto = data.adjunto
        self.form.link = data.link
        self.form.url = data.url
        self.form.file_name = data.adjunto
        console.log(self.form)
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
      return self.form.id !== null ? self.form.nombre : 'Nuevo material de apoyo'
    },

    itemsB(){
        let self = this
        return [
        { text: "CURSOS",disabled: false, href: "#/cursos_index"},
        { text: "MATERIALES DE APOYO",disabled: true, href: "#"}
      ]
    }
  },
};
</script>