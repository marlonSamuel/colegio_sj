<template>
    <v-layout align-start>
    <v-flex>
        <v-layout row wrap>
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
                    <v-flex
                    v-for="card in getAlumnos"
                    :key="card.title"
                    v-bind="{ [`xs${card.flex}`]: true }"
                    >
                    <v-card>
                        <v-img
                        :src="card.src"
                        height="300px"
                        >
                        <v-container
                            fill-height
                            fluid
                            pa-2
                        >
                            <v-layout fill-height>
                            <v-flex xs12 align-end flexbox>
                                <h2 class="black--text darken-4" v-text="card.title"></h2>
                            </v-flex>
                            </v-layout>
                        </v-container>
                        </v-img>

                        <v-card-actions>
                        <v-btn small flat color="blue">
                            <v-icon>file_copy</v-icon> Notas
                        </v-btn>
                        <v-btn small flat color="blue"  @click="$router.push(`/historial_pagos/`+card.id)">
                            <v-icon>money</v-icon> Pagos
                        </v-btn>
                        <v-btn small flat color="blue" @click="$router.push(`/historial_academico/`+card.id)">
                            <v-icon>file_copy</v-icon> Historial academico
                        </v-btn>
                        </v-card-actions>
                    </v-card>
                    </v-flex>
                </v-layout>
                </v-container>
            </v-card>
            </v-flex>
        </v-layout>
        </v-layout>
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
        items: [],
        cards: [
            { title: 'Favorite road trips', src: 'https://cdn.vuetifyjs.com/images/cards/road.jpg', flex: 3 },
            { title: 'Best airlines', src: 'https://cdn.vuetifyjs.com/images/cards/plane.jpg', flex: 3 }
        ]
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