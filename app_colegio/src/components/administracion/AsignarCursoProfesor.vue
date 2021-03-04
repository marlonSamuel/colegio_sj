<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
      <v-toolbar flat color="white">
        <v-toolbar-title>Profesores </v-toolbar-title>
        <v-divider class="mx-2" inset vertical></v-divider><v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Buscar"
          single-line
          hide-details
        ></v-text-field>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="1000px">
          <v-card>
            <v-card-title>
              <span class="headline">{{ setTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs12 sm8 md8>
                    <v-autocomplete
                      v-model="form.curso_grad_niv_edu_id"
                      v-validate="'required'"
                      label="Nivel Educativo/Grado/Curso"
                      placeholder="Nivel Educativo / Grado / Curso"
                      :items="info"
                      item-text="nombre"
                      item-value="id"
                      return-object
                      @input="getSeccion"
                      data-vv-name="curso-grado"
                      :error-messages="errors.collect('curso-grado')"
                    >
                    </v-autocomplete>
                  </v-flex>
                  <v-flex xs12 sm4 md4>
                    <v-select
                      placeholder="Jornada"
                      v-model="form.jornada"
                      v-validate="'required'"
                      :items="jornadas"
                      :error-messages="errors.collect('jornada')"
                      label="Jornada"
                      item-value="value"
                      item-text="text"
                      data-vv-name="jornada"
                      required
                    ></v-select>
                  </v-flex>
                  <v-flex xs12 md6 v-if="secciones.length > 0">
                    <span class="headline">Asignar Secciones</span>
                    <v-layout row wrap>
                      <v-flex
                        v-for="(seccion, index) in secciones"
                        :key="seccion[index]"
                        xs2
                      >
                        <v-checkbox
                          light
                          :label="seccion.seccion.seccion"
                          :value="seccion.seccion_id"
                          v-model="form.secciones"
                        >
                        </v-checkbox>
                      </v-flex>
                    </v-layout>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="red darken-1" flat @click="close">Volver</v-btn>
              <v-btn flat @click="createOrEdit" color="blue darken-1"
                >Guardar</v-btn
              >
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialog2" max-width="1000px">
          <v-card>
            <v-card-title>
              <span class="headline"
                >Nivel Educativo/Grado/Curso asignados a
              </span>
            </v-card-title>

            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs12 md12 sm12>
                    <template>
                      <v-list dense>
                        <v-subheader>Asignaciones</v-subheader>
                        <v-list-tile v-for="(item, i) in asignaciones" :key="i">
                          <v-list-tile-action>
                            <v-icon
                              color="error"
                              fab
                              dark
                              @click="destroy(item)"
                              >delete</v-icon
                            >
                          </v-list-tile-action>
                          <v-list-tile-title
                            v-text="item.nombre"
                          ></v-list-tile-title>
                        </v-list-tile>
                      </v-list>
                    </template>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="red darken-1" flat @click="close">Volver</v-btn>
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
          <td class="text-xs-left">
            {{ props.item.primer_nombre }} {{ props.item.segundo_nombre }}
            {{ props.item.primer_apellido }} {{ props.item.segundo_apellido }}
          </td>
          <td class="text-xs-left">{{ props.item.cui }}</td>
          <td class="text-xs-left">{{ props.item.cargo.nombre }}</td>

          <td class="text-xs-left">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-icon
                  v-on="on"
                  color="primary"
                  fab
                  dark
                  @click="config(props.item)"
                >
                  settings</v-icon
                >
              </template>
              <span>Configurar Nivel Educativo-Grado-Cursos</span>
            </v-tooltip>
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-icon
                  v-on="on"
                  color="warning"
                  fab
                  dark
                  @click="edit(props.item)"
                >
                  edit</v-icon
                >
              </template>
              <span>Editar Nivel Educativo-Grado-Cursos asignados</span>
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
import moment from "moment";
export default {
  name: "grado",
  props: {
    source: String,
  },
  data() {
    return {
      dialog: false,
      dialog2: false,
      search: "",
      loading: false,
      items: [],
      info: [],
      niv_grad_curso: [],
      asignaciones: [],
      secciones: [],
      headers: [
        { text: "Nombre", value: "primer_nombre" },
        { text: "Cui", value: "cui" },
        { text: "Tipo empleado", value: "cargo.nombre" },
        { text: "Acciones", value: "", sortable: false },
      ],
      pagination: {
        sortBy: "id",
      },

      form: {
        id: null,
        empleado_id: null,
        ciclo_id: this.$store.state.ciclo.id,
        curso_grad_niv_edu_id: null,
        jornada:null,
        secciones: [],
      },
      profesor_id: null,
      jornadas: [
        { text: "Matutina", value: "M" },
        { text: "Vespertina", value: "V" },
        { text: "Ambas", value: "A" },
      ],
    };
  },

  created() {
    let self = this;
    self.ciclo_id = this.$store.state.ciclo.id;
    self.getProfesores();
    //self.getSeccion();
  },

  methods: {
    getAll(idProfesor, idCiclo) {
      let self = this;
      self.loading = true;
      self.$store.state.services.asignacionProfesorService
        .getAll(idProfesor, idCiclo)
        .then((r) => {
          self.loading = false;
          self.niv_grad_curso = r.data;
          self.getInfo();
        })
        .catch((r) => {});
    },
    get(idProfesor, idCiclo) {
      let self = this;
      self.loading = true;
      self.$store.state.services.asignacionProfesorService
        .get(idProfesor, idCiclo)
        .then((r) => {
          self.loading = false;
          self.asignaciones = r.data;
          
        })
        .catch((r) => {});
    },
    RemoveAsignados(arr) {
      let self = this;
      if (self.niv_grad_curso.length < 1) {
        self.info = arr;
      } else {
        self.niv_grad_curso.forEach(function (element) {
          arr.forEach(function (item) {
            if (element.curso_grad_niv_edu_id === item.id) {
              arr.splice(arr.indexOf(item), 1);
            }
          });
        });
        self.info = arr;
      }
      
    },
    getProfesores() {
      let self = this;
      self.loading = true;
      self.$store.state.services.empleadoService
        .getAll()
        .then((r) => {
          self.loading = false;
          self.items = [];
          r.data.forEach(function (element) {
            if (element.cargo.nombre.toLowerCase() === "profesor") {
              self.items.push(element);
            }
          });
        })
        .catch((r) => {});
    },

    //obtener los niveles-grados-cursos
    getInfo() {
      let self = this;
      self.loading = true;

      self.$store.state.services.asignacionProfesorService
        .getInfo()
        .then((r) => {
          self.loading = false;
          self.info = r.data;
          console.log(self.info);
          self.RemoveAsignados(self.info);
        })
        .catch((r) => {});
    },
    getSeccion(id) {
      let idgrado_nivel = id.id;
      let self = this;
      let gradoNivel = self.info.find(x => x.grado_nivel_educativo_id === id.grado_nivel_educativo_id);
      self.loading = true;
      self.$store.state.services.gradoNivelEducativoService
        .getSeccionesById(gradoNivel.grado_nivel_educativo_id)
        .then((r) => {
          self.loading = false;
          self.secciones = r.data;          
        })
        .catch((r) => {});
    },

    //funcion para guardar registro
    create() {
      let self = this;
      self.loading = true;
      let data = self.form;
      data.curso_grad_niv_edu_id = self.form.curso_grad_niv_edu_id.id;
      data.empleado_id = self.profesor_id;
      data.ciclo_id = self.ciclo_id;
      if (data.secciones.length === 0) {
        self.loading = false;
        this.$toastr.error("Debe Seleccionar al menos una sección", "error");
        return;
      }
      self.$store.state.services.asignacionProfesorService
        .create(data)
        .then((r) => {
          self.loading = false;
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          this.$toastr.success("registro agregado con éxito", "éxito");
          self.getAll(self.profesor_id, this.$store.state.ciclo.id);
          self.clearData();
        })
        .catch((r) => {});
    },

    //funcion para eliminar registro
    destroy(data) {
      let self = this;
      self
        .$confirm("Seguro que desea eliminar " + data.nombre + "?")
        .then((res) => {
          self.loading = true;
          self.$store.state.services.asignacionProfesorService
            .destroy(data)
            .then((r) => {
              self.loading = false;
              if (self.$store.state.global.captureError(r)) {
                return;
              }
              self.get(self.profesor_id, this.$store.state.ciclo.id);
              this.$toastr.success("registro eliminado con exito", "exito");
              self.clearData();
            })
            .catch((r) => {});
        })
        .catch((cancel) => {});
    },

    //limpiar data de formulario
    clearData() {
      let self = this;

      Object.keys(self.form).forEach(function (key, index) {
        if (typeof self.form[key] === "string") self.form[key] = "";
        else if (typeof self.form[key] === "boolean") self.form[key] = true;
        else if (typeof self.form[key] === "number") self.form[key] = null;
      });
      self.form.secciones = [];
      self.secciones = [];
      //self.niv_grad_curso = [];
      self.$validator.reset();
    },

    //asignar cursos
    config(data) {
      let self = this;
      this.dialog = true;
      self.getAll(data.id, this.$store.state.ciclo.id);

      self.profesor_id = data.id;
      self.form.empleado_id = data.id;
      self.form.ciclo_id = self.ciclo_id;
      //self.mapData(data);
    },
    //editar
    edit(data) {
      let self = this;
      this.dialog2 = true;
      self.get(data.id, this.$store.state.ciclo.id);
      self.profesor_id = data.id;
      self.form.empleado_id = data.id;
      self.form.ciclo_id = self.ciclo_id;
    },
    //mapear datos a formulario
    mapData(data) {
      let self = this;
      self.form.id = null;
      self.form.empleado_id = data.id;
      self.form.ciclo_id = self.ciclo_id;
    },

    //funcion, validar si se guarda o actualiza
    createOrEdit() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          if (self.form.id > 0 && self.form.id !== null) {
            
          } else {
            self.create();
          }
        }
      });

      let self = this;
    },

    cancelar() {
      let self = this;
    },

    close() {
      let self = this;
      self.dialog = false;
      self.dialog2 = false;
      self.clearData();
    },
  },

  computed: {
    setTitle() {
      let self = this;
      return self.form.id !== null ? self.form.nombre : "Nuevo Registro";
    },
  },
};
</script>