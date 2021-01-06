<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
      <v-toolbar flat color="white">
        <v-toolbar-title>Alumnos y secciones </v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider><v-spacer></v-spacer>
      </v-toolbar>


       <v-dialog v-model="dialog" max-width="500px">
          <v-card>
            <v-card-title>
              <span class="headline">{{(form.id == null  ? 'Cambiar sección ' : 'Asignar sección ') + alumno}}</span>
            </v-card-title>

            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs12 sm12 md12>
                    <v-select
                      v-model="form.seccion_id"
                      placeholder="seleccione sección"
                      v-validate="'required'"
                      :items="secciones"
                      :error-messages="errors.collect('seccion')"
                      label="Sección"
                      item-value="seccion_id"
                      item-text="seccion.seccion"
                      data-vv-name="seccion"
                    ></v-select>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="red darken-1" flat @click="close">Volver</v-btn>
              <v-btn color="blue darken-1" flat @click="validate">Guardar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

      <v-card>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs12 sm6 md6>
                    <v-select
                      v-model="nivel_educativo"
                      placeholder="seleccion nivel educativo"
                      v-validate="'required'"
                      :items="nivel_educativos"
                      :error-messages="errors.collect('nivel_educativo')"
                      label="Nivel educativo"
                      item-value="id"
                      item-text="nombre"
                      data-vv-name="nivel_educativo"
                      @input="setGrados"
                    ></v-select>
                  </v-flex>
                  <v-flex xs12 sm6 md6>
                    <v-select
                      v-model="grado_nivel_educativo_id"
                      placeholder="seleccione grado"
                      v-validate="'required'"
                      :items="grados"
                      :error-messages="errors.collect('grado')"
                      label="Grado"
                      item-value="id"
                      item-text="grado.nombre"
                      data-vv-name="grado"
                    ></v-select>
                  </v-flex>
                  <v-card-actions v-if="grado_nivel_educativo_id != null">
                    <v-spacer></v-spacer>
                    <v-btn small color="green darken-1" flat @click="getAll()"><v-icon>search</v-icon> buscar</v-btn>
                  </v-card-actions>
                </v-layout>
              </v-container>

              
      <v-layout wrap>
        
        <v-flex xs12 sm6 lg6 md6 style="padding-left: 5px;" v-for="item in items" :key="item.seccion_id">
          <h3>SECCIÓN {{item.seccion}}</h3>

          <v-data-table
            :headers="headers"
            :items="item.inscripciones"
            :search="search"
            class="elevation-1"
          >
            <template v-slot:items="props">
              <td class="text-xs-left">{{ props.item.inscripcion.alumno.codigo }}</td>
              <td class="text-xs-left">{{ props.item.inscripcion.alumno.primer_nombre }}
                                       {{ props.item.inscripcion.alumno.segundo_nombre }}
                                       {{ props.item.inscripcion.alumno.primer_apellido }}
                                       {{ props.item.inscripcion.alumno.segundo_apellido }}
              </td>
              <td class="text-xs-left">
                  <v-tooltip top>
                    <template v-slot:activator="{ on }">
                        <v-icon v-on="on"  color="primary" @click="edit(props.item)" fab dark> edit</v-icon>
                    </template>
                    <span>Cambiar de sección</span>
                </v-tooltip>
              </td>
            </template>
            <template v-slot:no-data>
              <v-btn color="primary" @click="getAll">Reset</v-btn>
            </template>
          </v-data-table>
        </v-flex>
      </v-layout>

      <el-divider></el-divider>
      <v-layout>
        
        <v-flex xs12 sm6 lg6 md6 style="padding-left: 5px;" v-if="items2.length > 0">
          <h3>ALUMNOS SIN ASIGNAR</h3>

            <v-data-table
              :headers="headers"
              :items="items2"
              class="elevation-1"
            >
              <template v-slot:items="props">
                <td class="text-xs-left">{{ props.item.alumno.codigo }}</td>
                <td class="text-xs-left">{{ props.item.alumno.primer_nombre }}
                                        {{ props.item.alumno.segundo_nombre }}
                                        {{ props.item.alumno.primer_apellido }}
                                        {{ props.item.alumno.segundo_apellido }}
                </td>
                <td class="text-xs-left">
                    <v-tooltip top>
                      <template v-slot:activator="{ on }">
                          <v-icon v-on="on"  color="primary" @click="mapCreate(props.item)" fab dark> edit</v-icon>
                      </template>
                      <span>Cambiar de sección</span>
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
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "AsingnacionIndex",
  props: {
      source: String
    },
  data() {
    return {
      dialog: false,
      search: '',
      loading: false,
      grado_nivel_educativo_id: null,
      items: [],
      items2: [],
      nivel_educativos: [],
      grados: [],
      secciones: [],
      nivel_educativo: {},
      alumno: '',
      headers: [
        { text: 'Codigo', value: '', sortable: false },
        { text: 'Alumno', value: '', sortable: false },
        { text: 'Opción', value: '', sortable: false }
      ],

      form: {
        id: null,
        seccion_id: null,
        inscripcion_id: null
      },
    };
  },

  created() {
    let self = this
    self.getNivelesEducativos()
  },

  methods: {
     getAll() {
      let self = this
      let ciclo_id = self.$store.state.ciclo.id
      self.loading = true
      self.$store.state.services.asignacionSeccionService
        .getAll(ciclo_id, self.grado_nivel_educativo_id)
        .then(r => {
          self.loading = false

          var items = _(r.data)
            .groupBy('seccion_id')
            .map(function(items, seccion_id) {
            var inscripcion = items.find(x=>x.seccion_id === parseInt(seccion_id))
            return {
                seccion_id: parseInt(seccion_id),
                seccion: inscripcion.seccion.seccion,
                inscripciones: items.filter(x=>x.seccion_id == parseInt(seccion_id)),
            };
          }).value();

          self.items = items

          //llamar secciones
          self.getSecciones()
          self.getAllWithoutSection()
        })
        .catch(r => {});
    },

    //obtener alumnos sin sección asignada
    getAllWithoutSection() {
      let self = this
      let ciclo_id = self.$store.state.ciclo.id
      self.loading = true
      self.$store.state.services.asignacionSeccionService
        .getAllWithoutSection(ciclo_id, self.grado_nivel_educativo_id)
        .then(r => {
          self.loading = false
          self.items2 = r.data
        })
        .catch(r => {});
    },

    getNivelesEducativos(id) {
      let self = this
      self.loading = true
      self.$store.state.services.nivelEducativoService
        .getAll(id)
        .then(r => {
          self.loading = false
          self.nivel_educativos = r.data
        })
        .catch(r => {});
    },

    getSecciones() {
      let self = this
      self.loading = true
      self.$store.state.services.gradoNivelEducativoService
        .getSeccionesById(self.grado_nivel_educativo_id)
        .then(r => {
          self.loading = false
          self.secciones = r.data
        })
        .catch(r => {});
    },

       //funcion para guardar registro
    create(){
      let self = this
      self.loading = true
      let data = self.form

      self.$store.state.services.asignacionSeccionService
        .create(data)
        .then(r => {
          self.loading = false
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          this.$toastr.success('alumno asignado a sección con éxito', 'éxito')
          self.getAll()
          self.getAllWithoutSection()
          self.close()
        })
        .catch(r => {});
    },

    //editar
    update(){
        let self = this
        self.loading = true
        let data = self.form
        
        self.$store.state.services.asignacionSeccionService
          .update(data)
          .then(r => {
            self.loading = false
            if (self.$store.state.global.captureError(r)) {
              return;
            }
            self.getAll()
            this.$toastr.success('alumno ah sido cambiado de sección', 'éxito')
            self.close()
          })
          .catch(r => {});

    },

    //funcion, validar si se guarda o actualiza
    validate(){
      let self = this
      this.$validator.validateAll().then((result) => {
          if (result) {
              if(self.form.id > 0 && self.form.id !== null){
                self.update()
              }else{
                self.create()
              }
           }
      })
    },

    //editar
    edit(data){
      let self = this
      self.dialog = true
      self.alumno = self.$store.state.global.getFullName(data.inscripcion.alumno)
      self.form.id = data.id
      self.form.seccion_id = data.seccion_id
    },

    //crear alumno sin sección
    mapCreate(data){
      let self = this
      self.alumno = self.$store.state.global.getFullName(data.alumno)
      self.dialog = true
      self.form.id = null
      self.form.inscripcion_id = data.id
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


    close(){
      let self = this
      self.dialog = false
      self.clearData()
    },

    //setear grados
    setGrados(nivel){
      let self = this
      var nivel_d = self.nivel_educativos.find(n=>n.id == nivel)
      self.grados = nivel_d.grados
    },

  },

  computed: {
  },
};
</script>