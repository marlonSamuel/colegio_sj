<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
      <v-toolbar flat color="white">
        <v-toolbar-title>Usuarios </v-toolbar-title>
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
        <v-dialog v-model="dialog" max-width="800px" persistent>
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
                    <v-select
                      v-model="form.empleado_id"
                      placeholder="seleccione empleado"
                      v-validate="'required'"
                      :items="empleados"
                      item-value="id"
                      :error-messages="errors.collect('empleado')"
                      label="Empleado"
                      item-text=""
                      data-vv-name="empleado"
                      clearable
                    >
                    <template slot="selection" slot-scope="data">
                        {{ data.item.primer_nombre }} {{ data.item.segundo_nombre }}
                        {{ data.item.primer_apellido }} {{ data.item.segundo_apellido }}
                      </template>
                      <template slot="item" slot-scope="data">
                        {{ data.item.primer_nombre }} {{ data.item.segundo_nombre }}
                        {{ data.item.primer_apellido }} {{ data.item.segundo_apellido }}
                      </template>
                    </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6>
                      <v-select
                        v-model="form.rol_id"
                        placeholder="seleccion rol usuario"
                        v-validate="'required'"
                        :items="roles"
                        :error-messages="errors.collect('rol')"
                        label="Rol usuario"
                        item-value="id"
                        item-text="rol"
                        data-vv-name="rol"
                        clearable
                      ></v-select>
                    </v-flex>
                    <v-flex v-if="form.id == null" xs12 sm6 md6>
                    <v-text-field v-model="form.password" 
                        ref="password"
                        label="Contraseña"
                        v-validate="'required|min:6'"
                        type="password"
                        data-vv-name="contraseña"
                        :error-messages="errors.collect('contraseña')">
                    </v-text-field>
                    </v-flex>
                    <v-flex v-if="form.id == null" xs12 sm6 md6>
                    <v-text-field v-model="form.password_confirmation" 
                        label="Confirmar Contraseña"
                        v-validate="'required|confirmed:password'" data-vv-as="password"
                        type="password"
                        data-vv-name="confirmar_contraseña"
                        :error-messages="errors.collect('confirmar_contraseña')">
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
        <v-dialog v-model="dialog2" max-width="800px" persistent>
          <v-card>
            <v-card-title>
              <span class="headline">Reestablecer contraseña a {{setTitle}}</span>
            </v-card-title>
  
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                    <v-flex xs12 sm8 md8>
                    <v-text-field v-model="form.password" 
                        ref="password"
                        label="Reestablecer Contraseña"
                        v-validate="'required|min:6'"
                        type="password"
                        data-vv-name="contraseña"
                        :error-messages="errors.collect('contraseña')">
                    </v-text-field>
                    </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>
  
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="red darken-1" flat @click="close">Volver</v-btn>
              <v-btn color="blue darken-1" flat @click="reset">Guardar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
      <v-data-table
        :headers="headers"
        :items="items"
        :search="search"
        class="elevation-1"
      >
        <template v-slot:items="props">
          <td class="text-xs-left">{{ props.item.codigo }}</td>
          <td class="text-xs-left" v-if="props.item.empleado !== null">
            {{ props.item.empleado.empleado.primer_nombre }} {{ props.item.empleado.empleado.segundo_nombre }}
            {{ props.item.empleado.empleado.primer_apellido }} {{ props.item.empleado.empleado.segundo_apellido }}
          </td>
          <td class="text-xs-left" v-if="props.item.alumno !== null">
            {{ props.item.alumno.alumno.primer_nombre }} {{ props.item.alumno.alumno.segundo_nombre }}
            {{ props.item.alumno.alumno.primer_apellido }} {{ props.item.alumno.alumno.segundo_apellido }}
          </td>
          <td class="text-xs-left" v-if="props.item.representante !== null">
             {{ props.item.representante.apoderado.primer_nombre }} {{ props.item.representante.apoderado.segundo_nombre }}
            {{ props.item.representante.apoderado.primer_apellido }} {{ props.item.representante.apoderado.segundo_apellido }}
          </td>
          <td class="text-xs-left">{{ props.item.email }}</td>
          <td class="text-xs-left">{{ props.item.rol.rol }}</td>
          <td class="text-xs-left">
             <v-tooltip top>
              <template v-slot:activator="{ on }">
                  <v-icon v-on="on" color="success" fab dark @click="restore(props.item)">lock_open</v-icon>
              </template>
              <span>Reestablecer Clave</span>
            </v-tooltip>
            <v-tooltip top v-if="props.item.empleado !== null">
              <template v-slot:activator="{ on }">
                  <v-icon v-on="on" color="warning" fab dark @click="edit(props.item)">edit</v-icon>
              </template>
              <span>Editar</span>
            </v-tooltip>
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                  <v-icon v-on="on" color="error" fab dark @click="destroy(props.item)">delete</v-icon>
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
export default {
  name: "usuario",
  props: {
      source: String
    },
  data() {
    return {
      dialog: false,
      dialog2 :false,
      search: '',
      loading: false,
      items: [],
      roles: [],
      empleados: [],
      headers: [
        { text: 'Codigo', value: 'codigo' },
        { text: 'Nombre', value: 'name' },
        { text: 'Correo electronico', value: 'email' },
        { text: 'Rol', value: 'rol.rol' },
        { text: 'Acciones', value: '', sortable: false }
      ],

      form: {
        id: null,
        rol_id: null,
        empleado_id: '',
        password: '',
        password_confirmation: ''
      },
    };
  },

  created() {
    let self = this
    self.getAll()
    self.getRoles()
    self.getEmpleados()
  },

  methods: {
     getAll() {
      let self = this
      self.loading = true

      self.$store.state.services.usuarioService
        .getAll()
        .then(r => {
          self.loading = false
          self.items = r.data
        })
        .catch(r => {});
    },

     getEmpleados() {
      let self = this
      self.loading = true

      self.$store.state.services.empleadoService
        .getAll()
        .then(r => {
          self.loading = false
          self.empleados = r.data
        })
        .catch(r => {});
    },

    getRoles() {
      let self = this
      self.loading = true

      self.$store.state.services.rolService
        .getAll()
        .then(r => {
          self.loading = false
          self.roles = r.data
        })
        .catch(r => {});
    },

    //funcion para guardar registro
    create(){
      let self = this
      let data = self.form

      let exists = self.items.some(x=>x.empleado.empleado_id == data.empleado_id)

      if(exists){
          self.$toastr.error('empleado ya tiene usuario creado','error');
          return
      }
      
      self.loading = true

      self.$store.state.services.usuarioService
        .create(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('registro agregado con éxito', 'éxito')
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
       
      self.$store.state.services.usuarioService
        .update(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          self.getAll()
          this.$toastr.success('registro actualizado con éxito', 'éxito')
          self.close()
        })
        .catch(r => {});
    },
        //funcion para actualizar registro
    reset(){
      let self = this
      self.loading = true
      let data = self.form
      if (data.password === '' || data.password === null) {
        self.loading = false;
        self.$toastr.error('Ingrese una contraseña por favor','error');
        
        return
      }
      if (data.password.length < 6) {
        self.loading = false;
        self.$toastr.error('Contraseña debe contener 6 caracteres','error');
        return
      }
      self.$store.state.services.usuarioService
        .resetPassword(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          self.getAll()
          this.$toastr.success('Contraseña restaurada con éxito', 'éxito')
          self.close()
        })
        .catch(r => {});
    },

    //funcion para eliminar registro
    destroy(data){
      let self = this
      self.$confirm('Seguro que desea eliminar curso'+ data.nombre+'?').then(res => {
        self.loading = true
            self.$store.state.services.usuarioService
            .destroy(data)
            .then(r => {
                self.loading = false
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
    //editar registro
    restore(data){
        let self = this
        this.dialog2 = true
        self.mapUser(data)   
    },
    //mapear datos a formulario
    mapData(data){
        let self = this
        self.form.id = data.id
        self.form.rol_id =data.rol_id
        self.form.empleado_id = data.empleado.empleado_id
    },
    mapUser(data){
        let self = this
        self.form.id = data.id
        self.form.rol_id =data.rol_id
        self.form.nombre = data.email;
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
        self.dialog2 = false
        self.clearData()
    },
  },

  computed: {
    setTitle(){
      let self = this
      return self.form.id !== null ? self.form.nombre : 'Nuevo Registro'
    }
  },
};
</script>