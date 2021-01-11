<template>
    <v-layout justify-center>
        <v-flex xs12 sm12 md12>
            <v-container
            fluid
            grid-list-md>
                <v-layout row wrap>
                        <v-flex sm6 md6 xs12 lg6>
                            <h2>Cursos actuales</h2>
                            <v-data-table
                                    :items="items"
                                    class="elevation-1"
                                    hide-actions
                                    :headers="headers"
                                >
                                    <template v-slot:items="props">
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
  name: "PanelAlumno",
  props: {
      source: String
    },
  data() {
    return {
        loading: false,
        items: [],
        headers: [
            {text: 'Periodo',value: '',sortable: false},
            {text: 'clase', value: '', sortable: false}
        ],
        headers2: [
            {text: 'Tarea',value: '',sortable: false},
            {text: 'fecha entrega', value: '', sortable: false}
        ]
    }
  },

  created() {
    let self = this
  },

  methods: {
  },

  computed: {
    },
};
</script>