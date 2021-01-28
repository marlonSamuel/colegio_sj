<template>
  <v-layout wrap v-loading="loading">
    <v-flex xs12 sm12 md12>
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
        <v-card-title>
          <span class="headline">Asignar Notas</span>
        </v-card-title>
        <v-card-text>
          <v-container fluid grid-list-md>
            <v-layout wrap>
              <v-flex xs12 sm12 md12>
                <h5 v-if="curso !== null">
                  <hr />
                  NIVEL EDUCATIVO:
                  {{
                    curso.curso_grado_nivel.grado_nivel_educativo
                      .nivel_educativo.nombre | uppercase
                  }}
                  <br />
                  GRADO:
                  {{
                    curso.curso_grado_nivel.grado_nivel_educativo.grado.nombre
                      | uppercase
                  }}
                  <br />
                  CURSO: {{ curso.curso_grado_nivel.curso.nombre | uppercase }}
                  <hr />
                  <br />
                </h5>
              </v-flex>
              <v-flex xs12 sm7 md7>
                <v-select
                  v-model="idPeriodo"
                  placeholder="Periodo Academico (Bimestre)"
                  v-validate="'required'"
                  :items="bimestre"
                  label="Periodo Academico"
                  data-vv-name="periodo academico"
                  item-value="id"
                  item-text="periodo_academico.nombre"
                  @change="getAll(idPeriodo)"
                  :error-messages="errors.collect('periodo academico')"
                ></v-select>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
      <v-dialog v-model="dialog" max-width="800px">
        <v-card>
          <v-card-title>
            <span class="headline">{{ setTitle }}</span>
          </v-card-title>

          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12 sm12 md12>
                  <v-text-field
                    v-model="form.nota"
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
      <v-data-table
        :headers="headers"
        :items="notas"
        :search="search"
        class="elevation-1"
        v-if="notas.length > 0"
      >
        <template v-slot:items="props">
          <td class="text-xs-left">{{ props.item.nombre }}</td>
          <td class="text-xs-left">{{ props.item.total_tareas }}</td>
          <td class="text-xs-left">{{ props.item.total_cuestionario }}</td>
          <td class="text-xs-left">{{ props.item.sub_total }}</td>
          <td class="text-xs-left">{{ props.item.nota }}</td>
          <td class="text-xs-left">
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
              <span>Agregar Nota Alumno</span>
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
  name: "PanelProfesor",
  props: {
    source: String,
  },
  data() {
    return {
      loading: false,
      dialog: false,
      search: "",
      bimestre: [],
      periodo_actual: null,
      notas: [],
      alumnos: [{ inscripcion_id: null }, { nombre: "" }, { nota: "" }],
      headers: [
        { text: "Nombre", value: "nombre" },
        { text: "Total Tareas", value: "total_tareas" },
        { text: "Total Cuestionarios", value: "total_cuestionario" },
        { text: "Sub Total", value: "sub_total" },
        { text: "Nota", value: "nota" },
        { text: "Acciones", value: "", sortable: false },
      ],
      idPeriodo: null,
      ciclos: [],
      ciclo_id: null,
      curso_id: null,
      curso: null,
      items: [],
      form: {
        id: null,
        nombre:'',
        asignar_curso_profesor_id: null,
        ciclo_periodo_academico_id: null,
        inscripcion_id: null,
        nota: null,
        sub_total:null,
      },
    };
  },

  created() {
    let self = this;
    self.curso_id = self.$route.params.id;
    //self.getAll(self.$route.params.id);
    self.getPeriodos(this.$store.state.ciclo.id);
    self.getInfo(self.$route.params.id);
    //self.getAlumnos(self.$route.params.id);
  },

  methods: {
    getInfo(id) {
      let self = this;
      self.loading = true;
      self.$store.state.services.asignacionProfesorService
        .getOne(id)
        .then((r) => {
          self.loading = false;
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          self.curso = r.data;
        })
        .catch((e) => {});
    },
    getPeriodos(id) {
      let self = this;
      self.loading = true;
      self.$store.state.services.notaService
        .getPeriodos(id)
        .then((r) => {
          self.loading = false;
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          self.bimestre = r.data;
          self.periodo_actual = r.data.find(x=>x.actual)

                if(self.periodo_actual == null || self.periodo_actual == undefined){
                    let now = moment()
                    self.periodo_actual = r.data.find(x=>moment(now).isBetween(moment(x.inicio), moment(x.fin), undefined,'[]'))
                }
          
        })
        .catch((e) => {});
    },
    getAll(id) {
      let self = this;
      self.loading = true;
      if (id != self.periodo_actual.id) {
        self.loading = false;
        this.$toastr.error("Periodo no esta activo para ingresar notas", "error");
        return false;
      }
      self.$store.state.services.notaService
        .getAll(self.curso_id, id)
        .then((r) => {
          self.loading = false;
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          self.notas = r.data.data;
          if (self.notas.length === 0) {
            this.$toastr.error("No hay alumnos Inscritos", "error");
          }
        })
        .catch((e) => {});
    },
    //funcion para guardar registro
    create() {
      let self = this;
      self.loading = true;
      let data = self.form;
      self.$store.state.services.notaService
        .create(data)
        .then((r) => {
          self.loading = false;
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          this.$toastr.success("Nota agregada con éxito", "éxito");
          self.getAll(self.idPeriodo);
          self.close();
        })
        .catch((r) => {});
    },

    //funcion para actualizar registro
    update() {
      let self = this;
      self.loading = true;
      let id = self.form.id;
      let data = self.form;

      self.$store.state.services.notaService
        .update(id, data)
        .then((r) => {
          self.loading = false;
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          self.getAll(self.idPeriodo);
          this.$toastr.success("Nota actualizada con éxito", "éxito");
          self.close();
        })
        .catch((r) => {});
    },

    //funcion, validar si se guarda o actualiza
    createOrEdit() {
      let self = this;
      this.$validator.validateAll().then((result) => {
        if (result) {
          if (self.form.nota < self.form.sub_total) {
            this.$toastr.error("Nota no puede ser menor a la suma de cuestionario y tareas", "error");
            return false;
          }
          if (self.form.id > 0 && self.form.id !== null) {
            self.update();
          } else {
            self.create();
          }
        }
      });
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
      self.form.id = data.id;
      self.form.asignar_curso_profesor_id = data.asignar_curso_profesor_id;
      self.form.ciclo_periodo_academico_id = data.ciclo_periodo_academico_id;
      self.form.inscripcion_id = data.inscripcion_id;
      self.form.nota = data.nota;
      self.form.sub_total = data.sub_total;
      self.form.nombre = data.nombre;
    },

    //limpiar data de formulario
    clearData() {
      let self = this;
      Object.keys(self.form).forEach(function (key, index) {
        if (typeof self.form[key] === "string") self.form[key] = "";
        else if (typeof self.form[key] === "boolean") self.form[key] = true;
        else if (typeof self.form[key] === "number") self.form[key] = null;
      });

      self.$validator.reset();
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
      return self.form.id !== null
        ? self.form.nombre
        : "Asignar Nota a " +self.form.nombre;
    },

    itemsB() {
      let self = this;
      return [
        { text: "CURSOS", disabled: false, href: "#/cursos_index" },
        { text: "MATERIALES DE APOYO", disabled: true, href: "#" },
      ];
    },
  },
};
</script>