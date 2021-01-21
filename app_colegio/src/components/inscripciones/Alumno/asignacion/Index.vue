<template>
  <v-layout justify-center v-loading="loading">
    <v-flex xs12 sm12 md12>
      <v-container>
        <v-layout row wrap>
          <v-flex sm12 md12 xs12 lg12>
            <v-data-table
              :items="asignaciones"
              class="elevation-1"
              hide-actions
              :headers="headers"
            >
              <template v-slot:items="props">
                <td>
                  {{
                    props.item.asignacion.asignar_curso_profesor
                      .curso_grado_nivel.curso.nombre
                  }}
                </td>
                <td>
                  {{ props.item.asignacion.titulo }}
                </td>
                <td>
                  {{ props.item.asignacion.fecha_entrega }}
                </td>
                <td>
                  <v-tooltip top>
                    <template v-slot:activator="{ on }">
                      <v-btn
                        flat
                        small
                        v-on="on"
                        color="primary"
                        @click="
                          $router.push(
                            !props.item.asignacion.flag_tiempo
                              ? 'entrega_asignacion/' + props.item.id
                              : ''
                          )
                        "
                      >
                        <v-icon fab dark>edit</v-icon> responder</v-btn
                      >
                    </template>
                    <span>Responder</span>
                  </v-tooltip>
                </td>
              </template>
            </v-data-table>
          </v-flex>
        </v-layout>
      </v-container>
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
      ciclos: [],
      ciclo_id: null,
      curso_id: null,
      items: [],
      headers: [
        { text: "Curso", value: "", sortable: false },
        { text: "Tarea", value: "", sortable: false },
        { text: "fecha entrega", value: "", sortable: false },
        { text: "", value: "" },
      ],
      asignaciones: [],
      form: {
        id: null,
        asignar_curso_profesor_id: null,
        cuestionario: null,
        nota: null,
        titulo: "",
        descripcion: "",
        fecha_entrega: null,
        fecha_habilitacion: null,
        tiempo: 0,
        flag_tiempo: 0,
        entrega_tarde: 0,
        adjunto: "",
        file: null,
        file_name: "",
      },
    };
  },

  created() {
    let self = this;
    self.getAsignaciones(
      this.$store.state.usuario.user_info.id,
      this.$store.state.ciclo.id
    );
    self.getCursos(
      this.$store.state.usuario.user_info.id,
      this.$store.state.ciclo.id
    );
  },

  methods: {
    getAsignaciones(idAlumno, idCiclo) {
      let self = this;
      self.loading = true;
      self.$store.state.services.asignacionAlumnoService
        .getAsignacion(idAlumno, idCiclo)
        .then((r) => {
          self.loading = false;
          self.asignaciones = r.data;
        })
        .catch((r) => {});
    },
    getCursos(idAlumno, idCiclo) {
      let self = this;
      self.loading = true;
      self.$store.state.services.asignacionAlumnoService
        .getCurso(idAlumno, idCiclo)
        .then((r) => {
          self.loading = false;
          self.cursos = r.data[0];
        })
        .catch((r) => {});
    },
    edit(data) {
      let self = this;
      self.dialog = true;
      self.mapData(data);
    },
    //funcion, validar si se guarda o actualiza
    createOrEdit() {
      let self = this;
      this.$validator.validateAll().then((result) => {
        if (result) {
          self.form.asignar_curso_profesor_id = self.$route.params.id;
          self.form.entrega_tarde ? (self.form.entrega_tarde = 1) : 0;
          self.form.flag_tiempo ? (self.form.flag_tiempo = 1) : 0;

          if (!self.form.flag_tiempo) {
            self.form.tiempo = 0;
          }

          if (self.form.id > 0 && self.form.id !== null) {
            //self.update();
          } else {
            //self.create();
          }
        }
      });
    },
    //funcion para guardar registro
    create() {
      let self = this;
      self.loading = true;
      let data = self.getFormData(self.form);
      self.$store.state.services.asignacionService
        .create(data)
        .then((r) => {
          self.loading = false;
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          this.$toastr.success("asignación agregada con éxito", "éxito");
          self.getAll(self.$route.params.id);
          self.close();
        })
        .catch((r) => {});
    },
    //documento
    selectedDocumento() {
      let self = this;
      var input = document.querySelector("#uploader .input-file");
      var files = input.files;
      self.form.file = files[0];
      console.log(self.form);
      var oFReader = new FileReader();
      oFReader.readAsDataURL(files[0]);

      /*oFReader.onload = function (oFREvent) {
          self.form.file_name = oFREvent.target.result
          console.log(self.form.file.name)
      }*/
    },
    mapData(data) {
      let self = this;
      self.form.id = data.id;
      self.form.titulo = data.asignacion.titulo;
      self.form.descripcion = data.asignacion.descripcion;
      self.form.nota = data.asignacion.nota;
      self.form.fecha_entrega = data.asignacion.fecha_entrega;
      self.form.adjunto = data.asignacion.adjunto;
      console.log(self.form);
    },
    close() {
      let self = this;
      self.dialog = false;
      self.clearData();
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
    descargarAdjunto(documento) {
      console.log(documento);
      let self = this;
      var url = self.$store.state.base_url + "documentos/" + documento;
      window.open(url, "_blank");
    },
  },

  computed: {},
};
</script>