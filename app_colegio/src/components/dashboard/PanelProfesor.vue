<template>
    <v-layout justify-center>
        <v-flex xs12 sm12 md12 v-if="event">
            <v-container
            fluid
            grid-list-md v-loading="loading">
                <v-layout row wrap>
                        <v-flex sm6 md6 xs12 lg6>
                            <h2>Grados Cursos actuales</h2>
                            <v-data-table
                                    :items="items"
                                    class="elevation-1"
                                    hide-actions
                                    :headers="headers"
                                >
                                    <template v-slot:items="props">
                                       <td>
                                            {{ props.item.curso_grado_nivel.grado_nivel_educativo.grado.nombre }}  
                                            {{ props.item.curso_grado_nivel.grado_nivel_educativo.nivel_educativo.nombre }}
                                       </td>
                                       <td>
                                            {{ props.item.curso_grado_nivel.curso.nombre }}  
                                       </td>
                                       <td>
                                           <v-tooltip top>
                                             <template v-slot:activator="{ on }">
                                                 <v-btn flat small v-on="on" color="primary" 
                                                    @click="$router.push('asignacion_index/'+props.item.id)">
                                                    <v-icon
                                                    fab
                                                    dark
                                                    >add</v-icon
                                                    > asignaciones</v-btn>
                                                </template>
                                                <span>asignar tareas o cuestionarios</span>
                                            </v-tooltip>
                                       </td>
                                    </template>
                            </v-data-table>
                        </v-flex>
                        <v-flex sm6 md6 xs12 lg6>
                            <h2>Tareas asignadas</h2>
                            <v-data-table
                                    :items="items"
                                    class="elevation-1"
                                    hide-actions
                                    :headers="headers2"
                                >
                                    <template v-slot:items="props">
                                         
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
      source: String
    },
  data() {
    return {
        loading: false,
        items: [],
        headers: [
            {text: 'Grado',value: '',sortable: false},
            {text: 'clase', value: '', sortable: false},
            {text: '', value: '', sortable: false}
        ],
        headers2: [
            {text: 'Tarea',value: '',sortable: false},
            {text: 'fecha entrega', value: '', sortable: false}
        ]
    }
  },

  created() {
    let self = this
    events.$on("teacher_event", self.onEventTeach)
  },

  beforeDestroy() {
    let self = this;
    events.$off("teacher_event", self.onEventTeach)
  },

  methods: {
    onEventTeach(data) {
      let self = this
      self.get(data.id)
    },

    //obtener cursos de profesores
    get(id) {
      let self = this;
      self.loading = true;
      self.$store.state.services.asignacionProfesorService
        .getAll(id,this.$store.state.ciclo.id)
        .then(r => {
          self.loading = false
          self.items = r.data
        })
        .catch(r => {});
    }


  },

    computed: {
        event(){
            let self = this
            let user = self.$store.state.usuario
            if(user.user_info !== null && user.user_info !== undefined){
                self.get(user.user_info.id)
            }
            return true
        }
    },
};
</script>