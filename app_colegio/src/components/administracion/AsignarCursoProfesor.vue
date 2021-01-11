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
                      data-vv-name="curso-grado"
                      :error-messages="errors.collect('curso-grado')"
                    >
                    </v-autocomplete>
                  </v-flex>
                  <v-flex xs12 md6>
                    <span class="headline">Asignar Secciones</span>
                    <v-layout row wrap>
                      <v-flex
                        v-for="(seccion, index) in secciones"
                        :key="seccion[index]"
                        xs2
                      >
                        <v-checkbox
                          light
                          :label="seccion.seccion"
                          :value="seccion.id"
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
                <v-layout wrap> </v-layout>
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
      dialog2:false,
      search: "",
      loading: false,
      items: [],
      info: [],
      niv_grad_curso: [],
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
        secciones: [],
      },
    };
  },

  created() {
    let self = this;
    self.getProfesores();
    self.getSeccion();
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
        })
        .catch((r) => {});
    },
    RemoveAsignados(arr) {
      let self = this;
      console.log(self.niv_grad_curso.length);
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
          self.RemoveAsignados(r.data.data);
        })
        .catch((r) => {});
    },
    getSeccion() {
      let self = this;
      self.loading = true;
      self.$store.state.services.seccionService
        .getAll()
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
          self.getAll(self.form.empleado_id, this.$store.state.ciclo.id);
          self.getInfo();
          self.clearData();
        })
        .catch((r) => {});
    },

    //funcion para actualizar registro
    update() {
      let self = this;
      self.loading = true;
      let data = self.form;

      self.$store.state.services.empleadoService
        .update(data)
        .then((r) => {
          self.loading = false;
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          self.getAll();
          this.$toastr.success("registro actualizado con éxito", "éxito");
          self.close();
        })
        .catch((r) => {});
    },

    //funcion para eliminar registro
    destroy(data) {
      let self = this;
      self
        .$confirm("Seguro que desea eliminar grado " + data.codigo + "?")
        .then((res) => {
          self.loading = true;
          self.$store.state.services.empleadoService
            .destroy(data)
            .then((r) => {
              self.loading = false;
              if (self.$store.state.global.captureError(r)) {
                return;
              }
              self.getAll();
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
      //self.niv_grad_curso = [];
      self.$validator.reset();
    },

    //asignar cursos
    config(data) {
      let self = this;
      this.dialog = true;
      self.getAll(data.id, this.$store.state.ciclo.id);
      self.getInfo();
      self.form.empleado_id = data.id;
      self.form.ciclo_id = this.$store.state.ciclo.id;
      //self.mapData(data);
    },
    //editar
    edit(data) {
      let self = this;
      this.dialog2 = true;
      //self.mapData(data);
    },
    //mapear datos a formulario
    mapData(data) {
      let self = this;
      self.form.id = null;
      self.form.empleado_id = data.id;
      self.form.ciclo_id = this.$store.state.ciclo.id;
    },

    //funcion, validar si se guarda o actualiza
    createOrEdit() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          if (self.form.id > 0 && self.form.id !== null) {
            self.update();
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
      self.setCodigo();
    },

    setCodigo() {
      let self = this;
      let codigo = "";
      let sort_items = self.items.sort(
        (a, b) => parseFloat(a.id) - parseFloat(b.id)
      );
      if (sort_items.length > 0) {
        codigo =
          "2-" +
          moment().year() +
          "-" +
          (sort_items[sort_items.length - 1].id + 1);
      }
      self.form.codigo = codigo;
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