<template>
  <v-layout align-startv-loading="loading">
      <v-flex>
          <v-card>
                <v-card-title>
                    MATERIAL DE APOYO
                </v-card-title>
                <v-card-text>
                    <v-data-table
                            :headers="headers"
                            :items="items"
                            :expand="false"
                            class="elevation-1"
                            disable-initial-sort
                            hide-actions
                        >
                            <template v-slot:items="props">
                                <tr>
                                    <td class="text-xs-left">{{props.item.descripcion}}</td>
                                    <td class="text-xs-left">
                                        <a :href="props.item.url" v-if="props.item.link" target="_blank">IR A URL</a>
                                        <v-icon v-if="!props.item.link" color="error" @click="verAdjunto(props.item.adjunto)"> file_download_off</v-icon>
                                    </td>
                                </tr>
                            </template>
                            <template v-slot:no-data>
                                <v-btn color="primary" @click="getAll">Reset</v-btn>
                            </template>
                        </v-data-table>
                </v-card-text>
            </v-card>
      </v-flex>
  </v-layout>
</template>

<script>
import moment from 'moment'

export default {
  name: "AlumnoCursos",
  components: {

  },
  props: {
      source: String
    },
  data() {
    return {
      loading: false,
      items: [],

     headers: [
        { text: 'Titulo', value: 'asignacion.titulo' },
        { text: '', value: '' }
      ],
    }
  },

  created() {
    let self = this
    self.inscripcion_id = self.$route.params.inscripcion_id
    self.curso_grado_nivel_id = self.$route.params.curso_grado_nivel_id
    events.$on('material_apoyo_alumno',self.onEventAsignacion)

  },

  beforeDestroy(){
      let self = this
      events.$off('material_apoyo_alumno',self.onEventAsignacion)
  },

  methods: {
    onEventAsignacion(data){
        let self = this 
        self.items = data
    },



    verAdjunto(adjunto){
      let self = this
      var url = self.$store.state.base_url+'documentos/'+adjunto
      window.open(url, '_blank');
    }
  },

  computed: {
    },
};
</script>