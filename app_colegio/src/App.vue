<template>
    <v-app id="app" :style="getFondo" style="background-color: #ecf0f5;">
       <!---->
        <!-- =============================================== -->
        <navegationmenu v-if="isLogin"></navegationmenu>
        <v-content>
        <menu-option v-if="!isAdmin && isLogin"></menu-option>
          <v-container fluid fill-height>
            <v-slide-y-transition mode="out-in">
                <router-view></router-view>
            </v-slide-y-transition>
          </v-container>
        </v-content>

    <v-footer color="green darken-3" class="white--text" v-if="isLogin" app>
      <v-layout align-center justify-center>
        <span class="px-3">colegio {{getName}} &copy; {{ new Date().getFullYear() }}</span>
      </v-layout>
    </v-footer>
      
    </v-app>
</template>

<script>
import navegationmenu from "@/components/shared/NavegationMenu";
import MenuOption from "@/components/dashboard/MenuOption"

export default {
  components: {
    navegationmenu,
    MenuOption
  },
  data: ()=>({
    is_login: false
  }),

  created(){
    let self = this
    //self.$store.dispatch('autoLogin')
  },
  props: {
    source: String
  },

  methods:{
  },

  computed: {
    
    isLogin(){
      let self = this
      return self.$store.state.is_login
    },

    getName(){
      let self = this
      var name = self.$store.state.institucion.nombre
      return name !== undefined ? name.toLowerCase() : ''
    },

    isAdmin(){
      let self = this
      var user = self.$store.state.usuario
      if(!_.isEmpty(user) && user.rol.rol === 'admin'){
        return true
      }
      return false
    },

    getFondo(){
        let self = this
        var  fondo = self.$store.state.base_url+'img/fondo.jpg'
        if(!self.$store.state.is_login){
          return {
            "background-image": 'url('+fondo+')'
          } 
        }
      },


  }
};
</script>