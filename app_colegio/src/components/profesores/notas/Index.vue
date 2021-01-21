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
              <v-flex xs12 sm6 md6>
                <v-list dense>
                  <v-list-tile v-for="(item, i) in alumnos" :key="i">
                    <v-list-tile-title v-text="item.nombre"></v-list-tile-title>
                    <v-list-tile-action v-if="alumnos.length >0">
                      <v-text-field
                        prepend-icon="money"
                        v-model="item.nota"
                        color="primary"
                        type="number"
                        data-vv-name="salario"
                        data-vv-as="salario"
                        :error-messages="errors.collect('salario')"
                      ></v-text-field>
                    </v-list-tile-action>                    
                  </v-list-tile>
                </v-list>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="red darken-1"
            flat
            @click="$router.push(`/cursos_index`)"
            >Volver</v-btn
          >
          <v-btn color="blue darken-1" flat @click="update">Guardar</v-btn>
        </v-card-actions>
      </v-card>
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
      notas:[],
      alumnos:[
          {inscripcion_id:null},
          {nombre : ''},
          {nota:''},
      ],
      idPeriodo: null,
      ciclos: [],
      ciclo_id: null,
      curso_id: null,
      curso: null,
      items: [],
      form: {
        id: null,
        asignar_curso_profesor_id: null,
        ciclo_periodo_academico_id:null,
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
        })
        .catch((e) => {});
    },
    getAlumnos(id) {
      let self = this;
      self.loading = true;
      self.$store.state.services.asignacionProfesorService
        .getAlumnos(id)
        .then((r) => {
          self.loading = false;
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          self.alumnos = r.data;
          r.data.forEach(function(item){
              self.alumnos.push({
                  inscripcion_id:item.alumno_id,
                  nombre:item.alumno.codigo+' '+item.alumno.primer_nombre+' '+item.alumno.segundo_nombre+' '+item.alumno.primer_apellido+' '+item.alumno.segundo_apellido,
                  nota:self.validExist(item)
              });
          }); 
          console.log(self.alumnos);   
        })
        .catch((e) => {});
    },
    validExist(item) {
      let self = this;
      let nota = null;
      let a = self.notas.find(x => x.inscripcion_id == item.alumno_id && x.ciclo_periodo_academico_id == self.idBimestre);
      if (a === undefined) {
        nota = null;
      } else {
        nota = a.nota;
      }
      return nota;
    },
    getAll(id) {
      let self = this;
      self.loading = true;
      self.$store.state.services.notaService
        .getAll(self.curso_id,id)
        .then((r) => {
          self.loading = false;
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          self.nota = r.data;
          self.getAlumnos(self.curso_id);
        })
        .catch((e) => {});
    },

    //obtener registro
    get(id) {
      let self = this;
      self.loading = true;
      self.$store.state.services.materialService
        .get(id)
        .then((r) => {
          self.loading = false;
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          self.mapData(r.data);
        })
        .catch((e) => {});
    },

    //funcion para guardar registro
    create() {
      let self = this;
      self.loading = true;
      let data = self.getFormData(self.form);
      self.$store.state.services.materialService
        .create(data)
        .then((r) => {
          self.loading = false;
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          this.$toastr.success("Material agregado con éxito", "éxito");
          self.getAll(self.$route.params.id);
          self.close();
        })
        .catch((r) => {});
    },

    //funcion para actualizar registro
    update() {
      let self = this;
      self.loading = true;
      let id = self.form.id;
      let data = self.getFormData(self.form);

      self.$store.state.services.materialService
        .updateData(id, data)
        .then((r) => {
          self.loading = false;
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          self.getAll(self.$route.params.id);
          this.$toastr.success("Material actualizado con éxito", "éxito");
          self.close();
        })
        .catch((r) => {});
    },

    //funcion para eliminar registro
    destroy(data) {
      let self = this;
      self
        .$confirm("Seguro que desea eliminar material de apoyo?")
        .then((res) => {
          self.loading = true;
          self.$store.state.services.materialService
            .destroy(data)
            .then((r) => {
              self.loading = false;
              if (self.$store.state.global.captureError(r)) {
                return;
              }
              self.getAll(self.$route.params.id);
              this.$toastr.success("Material eliminado con exito", "exito");
              self.clearData();
            })
            .catch((r) => {});
        })
        .catch((cancel) => {});
    },
    prepareDataNotas(data) {
      let self = this;
      self.form2.turno_id = self.idTurno;
      data.forEach(function(element) {
        let a = self.notas.find(x => x.inscripcion_id == element.alumno_id && x.ciclo_periodo_academico_id == self.idBimestre);
        if (a === undefined && element.salario != null) {
          self.form2.cargos.push({
            id: null,
            cargo_id: element.idCargo,
            salario: element.salario
          });
        } else {
          if (element.salario != null) {
            self.form2.cargos.push({
              id: a.id,
              cargo_id: a.cargo_id,
              salario: element.salario
            });
          }
        }
      });
    },
    //funcion, validar si se guarda o actualiza
    createOrEdit() {
      let self = this;
      this.$validator.validateAll().then((result) => {
        if (result) {
          self.form.asignar_curso_profesor_id = self.$route.params.id;
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
      self.form.descripcion = data.descripcion;
      self.form.asignar_curso_profesor_id = data.asignar_curso_profesor_id;
      self.form.adjunto = data.adjunto;
      self.form.link = data.link;
      self.form.url = data.url;
      self.form.file_name = data.adjunto;
      console.log(self.form);
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
        : "Nuevo material de apoyo";
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