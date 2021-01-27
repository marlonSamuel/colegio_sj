<template>
    <div class="text-xs-left">
        <v-menu
        v-model="menu"
        :close-on-content-click="false"
        :nudge-width="200"
        offset-x
        >
        <template v-slot:activator="{ on }">
            <v-btn
                color="blue-grey"
                dark
                v-on="on"
                >
                <v-icon right dark>menu</v-icon>
                Opciones
            </v-btn>
        </template>

        <v-card>
            <v-list>
                <v-list-tile avatar>
                    <v-list-tile-avatar>
                        <img :src="getAvatar(user.user_info)" alt="John">
                    </v-list-tile-avatar>

                    <v-list-tile-content v-if="user !== false">
                        <v-list-tile-title>{{user.user_info.primer_nombre}}  
                                            {{user.user_info.primer_apellido}}
                        </v-list-tile-title>
                        <v-list-tile-sub-title>{{user.rol.rol}}</v-list-tile-sub-title>
                    </v-list-tile-content>

                    <v-list-tile-action>
                    <v-btn
                        :class="fav ? 'red--text' : ''"
                        icon
                        @click="fav = !fav"
                    >
                        <v-icon>favorite</v-icon>
                    </v-btn>
                    </v-list-tile-action>
                </v-list-tile>
            </v-list>

            <v-divider></v-divider>

            <v-list>
                <v-list v-for="item in items" :key="item.title">
                    <v-list-tile
                        @click="navigateTo(item.path)"
                    >
                    <v-icon>{{item.icon}}</v-icon>
                        <v-list-tile-title>{{item.title}}</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-list>
        </v-card>
        </v-menu>
    </div>
</template>

<script>
export default {
  name: "ExampleIndex",
  props: {
      source: String
    },
  data() {
    return {
        fav: true,
        menu: false,
        message: false,
        hints: true,
        items: [],
        itemsAlumno: [
            { title: 'Inicio',icon:"home",path:"/" },
            { title: 'Mis grados y cursos',icon:"check_circle_outline",path:"/cursos_alumnos_index/" },
            { title: 'Historial de pagos',icon:"attach_money", path:"/historial_pagos/" },
            { title: 'Historial academico',icon:"file_copy", path:"/historial_academico/" }
        ],

         itemsProfesor: [
            { title: 'Inicio',icon:"home", path: '/' },
            { title: 'Mis cursos',icon:"file_copy", path: '/cursos_index' }
        ],

         itemsApoderado: [
            { title: 'Alumnos representados',icon:"account_circle", path: '/' }
        ]
    }
  },

  created() {
    let self = this
  },

  methods: {
      navigateTo(path){
          let self = this
          self.menu = false
          var user = self.$store.state.usuario
          if(user.rol.rol == 'alumno' & path !== '/'){  
            self.$router.push(path+user.user_info.id)
            return
          }
          self.$router.push(path)

      },

       getAvatar(user){
            let self = this
            if(!_.isEmpty(self.$store.state.usuario)){
                return (user.foto !== null && user.foto !== undefined) ? self.$store.state.base_url+user.foto : self.$store.state.base_url+'img/user_empty.png'
            }
        },
  },

  computed: {
        user(){
            let self = this
            var user = self.$store.state.usuario
            if(!_.isEmpty(user)){
                if(user.rol.rol == 'alumno'){
                    self.items = self.itemsAlumno
                }else if(user.rol.rol == 'profesor'){
                    self.items = self.itemsProfesor
                }else if((user.rol.rol == 'apoderado')){
                    self.items = self.itemsApoderado
                }
                return user
            }
            return !_.isEmpty(user)
        }
    },
};
</script>