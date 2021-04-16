<template>
  <v-layout wrap v-loading="loading"> 
    <v-flex xs12 sm12 md12 v-if="asignacion !== null">
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
          <span class="headline">Entrega Asignacion {{ asignacion.asignacion.titulo }}</span>
        </v-card-title>
        

        <v-card-text>

          <v-container fluid grid-list-md>
            <v-layout wrap>
              <v-flex xs12 sm10 md10>
                <v-text-field
                  v-model="asignacion.asignacion.titulo"
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
                  v-model="asignacion.asignacion.descripcion"
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
                  v-model="asignacion.asignacion.fecha_entrega"
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
                  v-model="asignacion.asignacion.nota"
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
                      @click="descargarAdjunto(asignacion.asignacion.adjunto)"
                      >file_download_off
                    </v-icon>
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
                    v-validate="'required'"
                  />
                </div>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" flat @click="update">Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-flex>
  </v-layout>
</template>
<script>
import moment from "moment";
export default {
  name: "Entrega",
  props: {
    source: String,
  },
  data() {
    return {
      loading: false,
      asignacion: null,
      form: {
        id: null,
        asignacion_id: null,
        inscripcion_id: null,
        nota: null,
        fecha_entrega: null,
        entrega_tarde: null,
        adjunto: "",
        entregado: 0,
        calificado: 0,
        file: null,
        file_name: "",
      },
    };
  },
  created() {
    let self = this;
    self.getAsignacionAlumno(self.$route.params.id);
  },
  methods: {
    getAsignacionAlumno(id) {
      let self = this;
      self.loading = true;
      self.$store.state.services.asignacionAlumnoService
        .get(id)
        .then((r) => {
          self.loading = false;
          self.mapData(r.data);
          self.asignacion = r.data
          //self.getAsignacion(r.data.asignacion_id);
        })
        .catch((r) => {});
    },
    getAsignacion(idAsignacion) {
      let self = this;
      self.loading = true;
      self.$store.state.services.asignacionService
        .get(idAsignacion)
        .then((r) => {
          self.loading = false;
          self.asignacion = r.data;
        })
        .catch((r) => {});
    },
    update() {
      let self = this;
      self.loading = true;
      self.prepareData();
      let id = self.form.id;
      let data = self.getFormData(self.form);
      if (self.form.file == null) {
        self.loading = false;
        this.$toastr.error("Debe adjuntar su tarea","error");
        return;
      }
      self.$store.state.services.asignacionAlumnoService
        .updateData(id, data)
        .then((r) => {
          self.loading = false;
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          this.$toastr.success("Tarea entregada con éxito", "éxito");
          self.$router.push('/info_cursos_alumnos/'+self.asignacion.inscripcion_id+'/curso/'+self.asignacion.asignacion.asignar_curso_profesor.curso_grad_niv_edu_id)
        })
        .catch((r) => {});
    },
    getFormData(object) {
      const formData = new FormData();
      Object.keys(object).forEach((key) => formData.append(key, object[key]));
      return formData;
    },
    prepareData() {
      let self = this;
      const format = "YYYY-MM-DD hh:mm";
      self.form.file_name = self.form.adjunto;
      self.form.fecha_entrega = moment().format(format);
      self.form.entregado = 1;
      self.form.entrega_tarde = moment(self.form.fecha_entrega).isAfter(self.asignacion.asignacion.fecha_entrega)? 1: 0;
    },

    mapData(data) {
      let self = this;
      self.form.id = data.id;
      self.form.asignacion_id = data.asignacion_id;
      self.form.inscripcion_id = data.inscripcion_id;
      self.form.nota = data.nota;
      self.form.fecha_entrega = data.fecha_entrega;
      self.form.entrega_tarde = data.entrega_tarde;
      self.form.adjunto = data.adjunto;
      self.form.entregado = data.entregado;
      self.form.calificado = data.calificado;
      self.form.file_name = data.adjunto;
    },
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
    descargarAdjunto(documento) {
      if (documento != null) {
        let self = this;
        var url = self.$store.state.base_url + "documentos/" + documento;
        window.open(url, "_blank");
      }
    },
  },

  computed: {
    
      itemsB(){
        let self = this
        var user = self.$store.state.usuario
        if(!_.isEmpty(user) && self.asignacion !== null){
          return [
              {text: "GRADO Y CURSOS",disabled: false,href: "#/cursos_alumnos_index/"+user.user_info.id},
              {text: "ASIGNACIONES",disabled: false, href: "#/info_cursos_alumnos/"+self.asignacion.inscripcion_id+'/curso/'+self.asignacion.asignacion.asignar_curso_profesor.curso_grad_niv_edu_id},
              {text: "ENTREGA ASIGNACION",disabled: true, href: "#/"}
            ]
        }
      }
  }
};
</script>