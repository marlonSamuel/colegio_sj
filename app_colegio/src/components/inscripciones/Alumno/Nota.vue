<template>
  <v-layout justify-center>
    <v-flex xs12 sm12 md12>
      <v-container fluid grid-list-md v-loading="loading">
        <v-layout row wrap>
          <v-flex>
            <v-toolbar flat color="white">
              <v-toolbar-title v-if="ciclo !== null">
                <v-icon color="blue">library_books</v-icon> NOTAS
                CICLO ESCOLAR {{ ciclo.ciclo }}
              </v-toolbar-title>
              <v-divider class="mx-2" inset vertical></v-divider
              ><v-spacer></v-spacer>
              <v-flex>
                <v-select
                  v-model="ciclo_id"
                  item-value="id"
                  item-text="ciclo"
                  append-icon="filter_list"
                  @input="selectedCiclo"
                  :items="ciclos"
                  label="Filtrar por ciclo"
                >
                </v-select>
              </v-flex>
              <v-spacer></v-spacer>
            </v-toolbar>

            <v-data-table
              :items="items"
              class="elevation-1"
              hide-actions
              :headers="headers"
            >
              <template v-slot:items="props">
                <td>
                  {{
                    props.item.curso_grado_nivel.grado_nivel_educativo.grado
                      .nombre
                  }}
                  {{
                    props.item.curso_grado_nivel.grado_nivel_educativo
                      .nivel_educativo.nombre
                  }}
                </td>
                <td>
                  {{ props.item.curso_grado_nivel.curso.nombre }}
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
                          $router.push('asignacion_index/' + props.item.id)
                        "
                      >
                        <v-icon fab dark>add</v-icon> asignaciones</v-btn
                      >
                    </template>
                    <span>asignar tareas o cuestionarios</span>
                  </v-tooltip>
                  <v-tooltip top>
                    <template v-slot:activator="{ on }">
                      <v-btn
                        flat
                        small
                        v-on="on"
                        color="blue"
                        @click="$router.push('view_alumnos/' + props.item.id)"
                      >
                        <v-icon fab dark>person_pin</v-icon> alumnos</v-btn
                      >
                    </template>
                    <span>Lista de alumnos asignados a este curso</span>
                  </v-tooltip>
                  <v-tooltip top>
                    <template v-slot:activator="{ on }">
                      <v-btn
                        flat
                        small
                        v-on="on"
                        color="green"
                        @click="$router.push('notas_index/' + props.item.id)"
                      >
                        <v-icon fab dark>file_copy</v-icon> notas</v-btn
                      >
                    </template>
                    <span>Asignar notas</span>
                  </v-tooltip>
                  <v-tooltip top>
                    <template v-slot:activator="{ on }">
                      <v-btn
                        flat
                        small
                        v-on="on"
                        color="primary"
                        @click="
                          $router.push('materiales_index/' + props.item.id)
                        "
                      >
                        <v-icon fab dark>add</v-icon> materiales</v-btn
                      >
                    </template>
                    <span>Agregar Material de Apoyo</span>
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
  name: "NotaAlumno",
  props: {
    source: String,
  },
  data() {
    return {
      loading: false,
      items: [],
      ciclos: [],
      user: {},
      ciclo: null,
      ciclo_id: null,
      headers: [
        { text: "Grado", value: "", sortable: false },
        { text: "Curso", value: "", sortable: false },
        { text: "Acciones", value: "", sortable: false },
      ],
      headers2: [
        { text: "Tarea", value: "", sortable: false },
        { text: "fecha entrega", value: "", sortable: false },
      ],
    };
  },

  created() {
    let self = this;
    self.user = self.$store.state.usuario;
    self.ciclo = self.$store.state.ciclo;

    if (self.user.user_info !== null && self.user.user_info !== undefined) {
      //self.get(self.user.user_info.id, self.$store.state.ciclo.id);
    }
    self.getCiclos();
  },

  beforeDestroy() {
    let self = this;
  },

  methods: {
    onEventTeach(data) {
      let self = this;
      self.get(data.id);
    },

    //obtener cursos de profesores
    get(id, id_ciclo) {
      let self = this;
      self.loading = true;
      self.$store.state.services.asignacionProfesorService
        .getAll(id, id_ciclo)
        .then((r) => {
          self.loading = false;
          self.items = r.data;
        })
        .catch((r) => {});
    },

    getCiclos() {
      let self = this;
      self.loading = true;
      self.$store.state.services.cicloService
        .getAll()
        .then((r) => {
          self.loading = false;
          if (self.$store.state.global.captureError(r)) {
            return;
          }
          self.ciclos = r.data;
        })
        .catch((e) => {});
    },

    selectedCiclo(id) {
      let self = this;
      self.ciclo = self.ciclos.find((x) => x.id == id);
      self.get(self.user.user_info.id, id);
    },
  },

  computed: {},
};
</script>