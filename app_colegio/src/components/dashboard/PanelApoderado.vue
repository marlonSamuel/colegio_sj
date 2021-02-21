<template>
        <v-layout justify-center>
            <v-flex xs12 sm12 md12 lg12>
            <v-toolbar color="blue-grey" dark>
                <v-toolbar-title>ALUMNOS REPRESENTADOS</v-toolbar-title>
            </v-toolbar>

            <v-card>
                <v-container
                fluid
                grid-list-md
                >
                <v-layout row wrap>
                    <v-flex xs12 md3 lg3 v-for="card in getAlumnos" :key="card.id">
                        <v-card color="blue lighten-5" class="black--text">
                        <v-layout>
                            <v-flex xs5>
                            <v-img
                                :src="card.src"
                                height="150px"
                                contain
                            ></v-img>
                            </v-flex>
                            <v-flex xs7>
                            <v-card-title primary-title>
                                <div>
                                    <div class="headline">{{card.title}}</div>
                                </div>
                            </v-card-title>
                            </v-flex>
                        </v-layout>
                        <v-divider light></v-divider>
                        <v-card-actions class="pa-2">
                          <!--  <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <v-btn small flat color="blue" v-on="on" @click="$router.push(`/historial_pagos/`+card.id)">
                                        <v-icon>file_copy</v-icon> Notas
                                    </v-btn>
                                </template>
                                <span>Ir notas</span>
                            </v-tooltip>-->
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <v-btn small flat color="blue" v-on="on" @click="$router.push(`/historial_pagos/`+card.id)">
                                        <v-icon>money</v-icon> Pagos
                                    </v-btn>
                                </template>
                                <span>Ir historial de pagos</span>
                            </v-tooltip>
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <v-btn small flat color="blue" v-on="on" @click="$router.push(`/historial_academico/`+card.id)">
                                        <v-icon>file_copy</v-icon> Historial Academico
                                    </v-btn>
                                </template>
                                <span>Ir historial academico, visualizar historial de inscripciones y notas de alumnos</span>
                            </v-tooltip>
                        </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
                </v-container>
            </v-card>
            </v-flex>
        </v-layout>
</template>

<script>
export default {
  name: "ExampleIndex",
  props: {
      source: String
    },
  data() {
    return {
        loading: false,
        items: []
    }
  },

  created() {
    let self = this
  },

  methods: {
    getAvatar(foto){
        let self = this
        return foto !== null && foto != "" ? self.$store.state.base_url+foto : self.$store.state.base_url+'img/user_empty.png'
    }
  },

  computed: {
      getAlumnos(){
          let self = this
          let alumnos = self.$store.state.alumnos;
          let items = []
          alumnos.forEach((a,i)=>{
              items.push(
                  {id: a.id, title: self.$store.state.global.getFullName(a), src: self.getAvatar(a.foto), flex: 3}
                )
          })

          return items
      }
  },
};
</script>