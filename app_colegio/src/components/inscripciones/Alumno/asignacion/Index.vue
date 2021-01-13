<template>
  <v-layout justify-center v-loading="loading">
    <v-flex xs12 sm12 md12>
      <v-container>
        <v-layout row wrap>
          <v-flex sm12 md12 xs12 lg12>
            <v-toolbar flat color="white">
              <v-toolbar-title>Tareas Asignadas </v-toolbar-title>
              <v-divider class="mx-2" inset vertical></v-divider
              ><v-spacer></v-spacer>
              <v-spacer></v-spacer>
              <v-dialog v-model="dialog" max-width="1300px" persistent>
                <v-card>
                  <v-card-title>
                    <span class="headline"></span>
                  </v-card-title>

                  <v-card-text>
                    <v-container grid-list-md>
                      <v-layout wrap>
                        <v-flex xs12 sm10 md10>
                          <v-text-field
                            v-model="form.titulo"
                            label="Título"
                            readonly
                            v-validate="'required|max:50|min:5'"
                            type="text"
                            data-vv-name="titulo"
                            :error-messages="errors.collect('titulo')"
                          >
                          </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm10 md10>
                          <v-textarea
                            v-model="form.descripcion"
                            label="Descripción"
                            readonly
                            v-validate="'required|min:5|max:500'"
                            type="text"
                            data-vv-name="descripcion"
                            :error-messages="errors.collect('descripcion')"
                          >
                          </v-textarea>
                        </v-flex>
                        <v-flex xs12 sm4 md4>
                          <v-text-field
                            v-model="form.fecha_entrega"
                            label="Fecha entrega"
                            readonly
                            v-validate="'required'"
                            type="date"
                            data-vv-name="fecha_entrega"
                            :error-messages="errors.collect('fecha_entrega')"
                          >
                          </v-text-field>
                        </v-flex>

                        <v-flex xs12 sm4 md4>
                          <v-text-field
                            v-model="form.nota"
                            label="Valor de Tarea (pts)"
                            readonly
                            v-validate="'required|decimal'"
                            type="text"
                            data-vv-name="nota"
                            :error-messages="errors.collect('nota')"
                          >
                          </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm4 md4>
                          <v-tooltip top>
                            <template v-slot:activator="{ on }">
                              <v-icon
                                color="error"
                                v-on="on"
                                @click="descargarAdjunto(form.adjunto)"
                              >file_download_off
                                </v-icon
                              >
                            </template>
                            <span>Descargar Instrucciones</span>
                          </v-tooltip>
                        </v-flex>
                        <v-flex xs12 sm4 md4>
                          <div id="uploader">
                            <v-tooltip top>
                              <template v-slot:activator="{ on }">
                                <v-icon
                                  v-on="on"
                                  color="error"
                                  @click="$refs.file.click()"
                                  >attach_file</v-icon
                                >
                                {{
                                  form.file == null
                                    ? "Seleccionar archivo"
                                    : form.file.name
                                }}
                              </template>
                              <span>Adjuntar documento pdf</span>
                            </v-tooltip>
                            <input
                              v-show="false"
                              @change="selectedDocumento"
                              ref="file"
                              class="input-file hidden"
                              type="file"
                              accept="application/pdf"
                            />
                          </div>
                        </v-flex>
                      </v-layout>
                    </v-container>
                  </v-card-text>

                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-1" flat @click="close"
                      >Volver</v-btn
                    >
                    <v-btn color="blue darken-1" flat @click="createOrEdit"
                      >Guardar</v-btn
                    >
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-toolbar>
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
                        @click="edit(props.item)"
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
      self.form.file_name = data.asignacion.adjunto;
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
    descargarAdjunto(documento){
        console.log(documento);
      let self = this
      var url = self.$store.state.base_url+'documentos/'+documento
      window.open(url, '_blank');
    }
  },

  computed: {},
};
</script>