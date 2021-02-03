<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
      <v-toolbar flat color="white">
        <v-toolbar-title>Ciclos</v-toolbar-title>
        <v-divider class="mx-2" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Buscar"
          single-line
          hide-details
        ></v-text-field>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="800px">
          <template v-slot:activator="{ on }">
            <v-btn color="primary" dark small class="mb-2" v-on="on">
              <v-icon>add</v-icon>Nuevo
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{ setTitle }} Fechas de Bimestres</span>
            </v-card-title>

            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs12 sm4 md4>
                    <v-text-field
                      v-model="form.ciclo"
                      label="Ciclo"
                      v-validate="'required'"
                      type="number"
                      data-vv-name="ciclo"
                      :error-messages="errors.collect('ciclo')"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm4 md4>
                    <v-text-field
                      v-model="form.inicio"
                      label="Fecha Inicio"
                      v-validate="'required'"
                      type="date"
                      data-vv-name="inicio"
                      :error-messages="errors.collect('inicio')"
                    >
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 sm4 md4>
                    <v-text-field
                      v-model="form.fin"
                      label="Fecha fin"
                      v-validate="'required'"
                      type="date"
                      data-vv-name="fin"
                      :error-messages="errors.collect('fin')"
                    >
                    </v-text-field>
                  </v-flex>
                  <!--  <v-switch v-if="form.id !== null"
                      v-model="form.actual"
                      :label="`Actual: ${form.actual.toString() === 'false' ?'No':'Si'}`"
                    ></v-switch> -->
                </v-layout>

                <v-layout
                  v-for="f in form.periodos_academicos"
                  :key="f.id"
                  wrap
                >
                  <v-flex xs12 sm3 md3>
                    <v-text-field
                      v-model="f.nombre"
                      label="Nombre"
                      filled
                      readonly
                      v-validate="'required'"
                      type="text"
                      data-vv-name="nombre"
                      :error-messages="errors.collect('nombre')"
                    >
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 sm3 md3>
                    <v-text-field
                      v-model="f.inicio"
                      label="Fecha Inicio"
                      v-validate="'required'"
                      type="date"
                      data-vv-name="inicio_bimestre"
                      :error-messages="errors.collect('inicio_bimestre')"
                    >
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 sm3 md3>
                    <v-text-field
                      v-model="f.fin"
                      label="Fecha fin"
                      v-validate="'required'"
                      type="date"
                      data-vv-name="fin_bimestre"
                      :error-messages="errors.collect('fin_bimestre')"
                    >
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 sm3 md3  v-if="f.nota ===1000">
                    <v-text-field
                      v-model="f.nota"
                      label="Nota"
                      v-validate="'required'"
                      type="number"
                      data-vv-name="nota"
                      :error-messages="errors.collect('nota')"
                    >
                    </v-text-field>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="red darken-1" flat @click="close">Volver</v-btn>
              <v-btn color="blue darken-1" flat @click="createOrEdit"
                >Guardar</v-btn
              >
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
          <td class="text-xs-left">
            {{ props.item.ciclo
            }}<v-chip
              v-if="props.item.actual"
              small
              color="blue"
              text-color="white"
              >Actual</v-chip
            >
          </td>
          <td class="text-xs-left">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-icon
                  v-on="on"
                  color="success"
                  fab
                  dark
                  @click="$router.push(`/configurar_cuota/` + props.item.id)"
                  >money</v-icon
                >
              </template>
              <span>Configurar cuotas</span>
            </v-tooltip>
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-icon
                  v-on="on"
                  color="warning"
                  fab
                  dark
                  @click="edit(props.item)"
                  >edit</v-icon
                >
              </template>
              <span>Editar</span>
            </v-tooltip>
            <v-tooltip top v-if="items.length > 1">
              <template v-slot:activator="{ on }">
                <v-icon
                  v-on="on"
                  color="error"
                  fab
                  dark
                  @click="destroy(props.item)"
                  >delete</v-icon
                >
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
import moment from "moment";
import auth from "../../auth/index";
export default {
  name: "Ciclo",
  props: {
    source: String,
  },
  data() {
    return {
      dialog: false,
      search: "",
      menu: null,
      menu2: null,
      loading: false,
      items: [],
      headers: [
        { text: "Ciclo", value: "ciclo" },
        { text: "Acciones", value: "", sortable: false },
      ],
      pagination: {
        sortBy: "id",
      },
      periodos: [],
      form: {
        id: null,
        ciclo: null,
        actual: true,
        inicio: "",
        fin: "",
        periodos_academicos: [],
      },
    };
  },

  created() {
    let self = this;
    self.getAll();
    self.getPeriodos();
  },

  methods: {
    getAll() {
      let self = this;
      self.loading = true;

      self.$store.state.services.cicloService
        .getAll()
        .then((r) => {
          self.loading = false;
          self.items = r.data;

          self.$nextTick(() => {
            events.$emit("update_ciclo", r.data);
          });
        })
        .catch((r) => {});
    },
    getPeriodos() {
      let self = this;
      self.loading = true;

      self.$store.state.services.periodoAcademicoService
        .getAll()
        .then((r) => {
          self.loading = false;
          self.periodos = r.data;
          self.mapPeriodos(self.periodos);
        })
        .catch((r) => {});
    },

    //funcion para guardar registro
    create() {
      let self = this;
      self.loading = true;
      let data = self.form;
      if (self.items.some((x) => x.ciclo == data.ciclo)) {
        this.$toastr.error("ciclo ya fue agregado", "error");
        return;
      }

      let estado = data.actual === false ? 0 : 1;
      data.actual = estado;
      self.$store.state.services.cicloService
        .create(data)
        .then((r) => {
          self.loading = false;
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          this.$toastr.success("registro agregado con éxito", "éxito");
          self.getAll();
          self.$store.dispatch("setCiclo", r.data);
          self.close();
        })
        .catch((r) => {});
    },
    validSumNotas(bimestres) {
      var sum = 0;
      var valid = false;
      for (var i = 0; i < bimestres.length; i++) {
        sum += parseFloat(bimestres[i].nota);
      }

      if (sum < 100) {
        valid = true;
      }if (sum > 100) {
       valid = true; 
      }
      return valid;
    },
    //funcion para actualizar registro
    update() {
      let self = this;
      let data = self.form;

      var items_filter = self.items.filter((x) => x.id !== data.id);
      if (items_filter.some((x) => x.ciclo == data.ciclo)) {
        this.$toastr.error("ciclo ya fue agregado", "error");
        return;
      }

      if (!data.actual && items_filter.filter((x) => x.actual).length == 0) {
        this.$toastr.error("debe haber al menos un ciclo activo", "error");
        return;
      }
      if (self.validSumNotas(self.form.periodos_academicos)) {
        this.$toastr.error("Configure correctamente las notas por bimestres no suma 100 puntos", "error");
        return;
      }

      let estado = data.actual === false ? 0 : 1;
      data.actual = estado;
      self.loading = true;
      self.$store.state.services.cicloService
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
        .$confirm("Seguro que desea eliminar ciclo " + data.ciclo + "?")
        .then((res) => {
          self.loading = true;
          self.$store.state.services.cicloService
            .destroy(data)
            .then((r) => {
              self.loading = false;
              if (self.$store.state.global.captureError(r)) {
                return;
              }
              self.getAll();
              this.$toastr.success("registro eliminado con exito", "exito");

              if (data.actual) {
                auth.getCicloActual();
              }
              self.close();
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
      self.mapPeriodos(self.periodos);
      self.$validator.reset();
    },

    //editar registro
    edit(data) {
      let self = this;
      this.dialog = true;
      self.mapData(data);
    },

    //mapear datos a formulario
    mapData(data) {
      let self = this;
console.log(data);
      self.form.id = data.id;

      self.form.ciclo = data.ciclo;
      self.form.actual = data.actual === 1 || null ? true : false;
      self.form.inicio = data.inicio;
      self.form.fin = data.fin;
      this.mapPeriodos(
        data.periodos_academicos.length > 0
          ? data.periodos_academicos
          : self.periodos
      );
      console.log(self.form);
    },

    mapPeriodos(data) {
      console.log(data);
      let self = this;
      self.form.periodos_academicos = [];
      data.forEach((element) => {
        self.form.periodos_academicos.push({
          id: element.periodo_academico_id === undefined ? null : element.id,
          ciclo_id: self.form.id != null ? self.form.id : null,
          periodo_academico_id:
            element.periodo_academico_id === undefined
              ? element.id
              : element.periodo_academico_id,
          nombre:
            element.periodo_academico === undefined
              ? element.nombre
              : element.periodo_academico.nombre,
          inicio: element.inicio,
          fin: element.fin,
          nota: element.nota,
          actual: true,
        });
      });
    },

    //funcion, validar si se guarda o actualiza
    createOrEdit() {
      let self = this;
      this.$validator.validateAll().then((result) => {
        if (result) {
          if (self.form.id > 0 && self.form.id !== null) {
            self.update();
          } else {
            self.create();
          }
        }
      });
    },

    cancelar() {
      let self = this;
    },

    close() {
      let self = this;
      self.dialog = false;
      self.clearData();
    },
  },

  computed: {
    setTitle() {
      let self = this;
      return self.form.id !== null ? self.form.ciclo : "Nuevo Registro";
    },
  },
};
</script>