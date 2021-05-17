<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
      <v-toolbar flat color="white">
        <v-toolbar-title>Empleados </v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider><v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="search"
            label="Buscar"
            single-line
            hide-details
          ></v-text-field>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="1000px">
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
                <!--  <v-flex xs12 sm4 md4>
                    <v-text-field v-model="form.codigo" 
                        label="Codigo"
                        v-validate="'required'"
                        type="text"
                        data-vv-name="codigo"
                        readonly
                        :error-messages="errors.collect('codigo')">
                    </v-text-field>
                  </v-flex> -->
                  <v-flex xs12 sm4 md4>
                    <v-text-field v-model="form.cui" 
                        label="Cui"
                        v-validate="'required|numeric|min:13|max:15'"
                        type="text"
                        data-vv-name="cui"
                        :error-messages="errors.collect('cui')">
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 sm4 md4>
                    <v-text-field v-model="form.primer_nombre" 
                        label="Primer nombre"
                        v-validate="'required'"
                        type="text"
                        data-vv-name="primer_nombre"
                        data-vv-as="primer nombre"
                        :error-messages="errors.collect('primer_nombre')">
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 sm4 md4>
                    <v-text-field v-model="form.segundo_nombre" 
                        label="Segundo nombre"
                        type="text">
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 sm4 md4>
                    <v-text-field v-model="form.primer_apellido" 
                        label="Primer apellido"
                        v-validate="'required'"
                        type="text"
                        data-vv-name="primer_apellido"
                        data-vv-as="primer_apellido"
                        :error-messages="errors.collect('primer_apellido')">
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 sm4 md4>
                    <v-text-field v-model="form.segundo_apellido" 
                        label="Segundo apellido"
                        type="text">
                    </v-text-field>
                  </v-flex>

                  <v-flex xs12 sm4 md4>
                    <v-select
                      v-model="form.cargo_id"
                      v-validate="'required'"
                      :items="cargos"
                      :error-messages="errors.collect('cargo')"
                      label="Tipo empleado"
                      item-value="id"
                      item-text="nombre"
                      data-vv-name="cargo"
                      required
                    ></v-select>
                </v-flex>
                  
                  <v-flex xs12 sm4 md4>
                    <v-text-field v-model="form.fecha_nac" 
                        label="Fecha de nacimiento"
                        v-validate="'required'"
                        type="date"
                        data-vv-name="fecha_nac"
                        data-vv-as = "fecha de nacimiento"
                        :error-messages="errors.collect('fecha_nac')">
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 sm4 md4>
                    <v-text-field v-model="form.telefono" 
                        label="Teléfono"
                        v-validate="'required'"
                        type="text"
                        data-vv-name="telefono"
                        :error-messages="errors.collect('telefono')">
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 sm4 md4>
                    <v-text-field v-model="form.email" 
                        label="Correo electronico"
                        v-validate="'email'"
                        type="text"
                        data-vv-name="email"
                        :error-messages="errors.collect('email')">
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 sm12 md12>
                    <v-text-field v-model="form.direccion" 
                        label="Dirección"
                        v-validate="'required'"
                        type="text"
                        data-vv-name="direccion"
                        :error-messages="errors.collect('direccion')">
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
        :pagination.sync="pagination"
        class="elevation-1"
      >
        <template v-slot:items="props">
          <td class="text-xs-left">{{ props.item.primer_nombre }} {{ props.item.segundo_nombre }}
                                   {{ props.item.primer_apellido }} {{ props.item.segundo_apellido }}</td>
          <td class="text-xs-left">{{ props.item.cui }} </td>
          <td class="text-xs-left">{{ props.item.cargo.nombre }} </td>

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
        <template v-slot:no-data>
          <v-btn color="primary" @click="getAll">Reset</v-btn>
        </template>
      </v-data-table>
    </v-flex>
  </v-layout>
</template>

<script>
import moment from 'moment'
export default {
  name: "grado",
  props: {
      source: String
    },
  data() {
    return {
      dialog: false,
      search: '',
      loading: false,
      items: [],
      cargos: [],
      headers: [
        { text: 'Nombre', value: 'primer_nombre' },
        { text: 'Cui', value: 'cui' },
        { text: 'Tipo empleado', value: 'cargo.nombre' },
        { text: 'Acciones', value: '', sortable: false }
      ],
      pagination: {
        sortBy: 'id'
      },

      form: {
        id: null,
        cui: '',
        cardo_id: null,
        codigo: '',
        primer_nombre: '',
        segundo_nombre: '',
        primer_apellido: '',
        segundo_apellido: '',
        fecha_nac:'',
        email:'',
        telefono: '',
        direccion: ''
      },
    };
  },

  created() {
    let self = this
    self.getAll()
    self.getCargos()
  },

  methods: {
     getAll() {
      let self = this
      self.loading = true

      self.$store.state.services.empleadoService
        .getAll()
        .then(r => {
          self.loading = false
          self.items = r.data
          //self.setCodigo()
        })
        .catch(r => {});
    },

    //obtener los cargos
    getCargos() {
      let self = this
      self.loading = true

      self.$store.state.services.cargoService
        .getAll()
        .then(r => {
          self.loading = false
          self.cargos = r.data
        })
        .catch(r => {});
    },

    //funcion para guardar registro
    create(){
      let self = this
      self.loading = true
      let data = self.form

      self.$store.state.services.empleadoService
        .create(data)
        .then(r => {
          self.loading = false
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          self.$alert( 'empleado registrado correctamente, codigo generado '+r.data.codigo, 
            'éxito', {
            confirmButtonText: 'OK'
          });
          self.getAll()
          self.clearData()

        })
        .catch(r => {});
    },

    //funcion para actualizar registro
    update(){
      let self = this
      self.loading = true
      let data = self.form
       
      self.$store.state.services.empleadoService
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
      self.$confirm('Seguro que desea eliminar grado '+ data.codigo+'?').then(res => {
        self.loading = true
            self.$store.state.services.empleadoService
            .destroy(data)
            .then(r => {
                self.loading = false
                if (self.$store.state.global.captureError(r)) {
                  return;
                }
                self.getAll()
                this.$toastr.success('registro eliminado con exito', 'exito')
                self.clearData()
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
        self.form.codigo = data.codigo
        self.form.cargo_id = data.cargo_id
        self.form.cui = data.cui
        self.form.primer_nombre = data.primer_nombre
        self.form.segundo_nombre = data.segundo_nombre
        self.form.primer_apellido = data.primer_apellido
        self.form.segundo_apellido = data.segundo_apellido
        self.form.fecha_nac = data.fecha_nac
        self.form.email = data.email
        self.form.telefono = data.telefono
        self.form.direccion = data.direccion
    },

    //funcion, validar si se guarda o actualiza
    createOrEdit(){
      this.$validator.validateAll().then((result) => {
          if (result) {
              if(self.form.id > 0 && self.form.id !== null){
                self.update()
              }else{
                self.create()
              }
           }
      });

      let self = this
    },
    

    cancelar(){
      let self = this
    },

    close () {
        let self = this
        self.dialog = false
        self.clearData()
        //self.setCodigo()
    },

    setCodigo(){
        let self = this
        return '';
    }
  },

  computed: {
    setTitle(){
      let self = this
      return self.form.id !== null ? self.form.nombre : 'Nuevo Registro'
    }
  },
};
</script>