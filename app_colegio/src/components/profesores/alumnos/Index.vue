<template>
    <v-layout justify-center  v-loading="loading">
        <v-flex xs12 sm12 md12>
            <v-container
            fluid
            grid-list-md>
            <v-card>
                
                <v-layout row wrap>
                    <v-flex sm12 md12 xs12 lg12>
                         <v-toolbar flat color="white">
                          <v-toolbar-title> <v-icon color="blue">list</v-icon>LISTA DE ALUMNOS </v-toolbar-title>
                            <v-spacer></v-spacer>
                            
                            <v-spacer></v-spacer>
                        </v-toolbar>
                        <v-flex>
                              <h5 v-if="curso !== null">
                                 <hr />
                                NIVEL EDUCATIVO: {{curso.curso_grado_nivel.grado_nivel_educativo.nivel_educativo.nombre | uppercase}} <br />
                                GRADO: {{curso.curso_grado_nivel.grado_nivel_educativo.grado.nombre | uppercase}} <br />
                                CURSO: {{curso.curso_grado_nivel.curso.nombre | uppercase}}
                                <hr />
                                <br />
                            </h5>
                            
                        </v-flex>

                        <v-flex>
                            <v-card>
                                <v-container fluid grid-list-md>
                                    
                                    <v-layout
                                    justify-space-between
                                    pa-3
                                    wrap
                                    >
                                    <v-flex xs12 xs5 md5 lg5>
                                        <v-treeview
                                        :active.sync="active"
                                        :items="items"
                                        :open.sync="open"
                                        activatable
                                        active-class="primary--text"
                                        class="grey lighten-5"
                                        transition
                                        open-on-click
                                        >
                                        <template v-slot:prepend="{ item, active }">
                                            <v-icon
                                            v-if="!item.children"
                                            :color="active ? 'primary' : ''"
                                            >
                                            person_pin
                                            </v-icon> {{item.alumno.primer_nombre}} {{item.alumno.primer_apellido}}
                                        </template>
                                        </v-treeview>
                                    </v-flex>
                                    <v-flex
                                        md7 xs12 lg7
                                        d-flex
                                        text-xs-center
                                    >
                                        <v-scroll-y-transition mode="out-in">
                                        <div
                                            v-if="!selected"
                                            class="title grey--text text--lighten-1 font-weight-light"
                                            style="align-self: center;"
                                        >
                                            Seleccione un alumno
                                        </div>
                                        <v-card
                                            v-else
                                            :key="selected.id"
                                            class="pt-4 mx-auto"
                                            flat
                                            max-width="auto"
                                        >
                                            <v-card-text>
                                            <v-avatar
                                                v-if="avatar"
                                                size="88"
                                            >
                                                <v-img
                                                :src="getAvatar(selected.alumno.foto)"
                                                class="mb-4"
                                                ></v-img>
                                            </v-avatar>
                                            <h4 class="headline mb-2">
                                                {{ selected.alumno.primer_nombre }}
                                                {{ selected.alumno.segundo_nombre }}
                                                {{ selected.alumno.primer_apellido }}
                                                {{ selected.alumno.segundo_apellido }}
                                            </h4>
                                            <div class="blue--text mb-2">{{ selected.alumno.email }}</div>
                                            <div class="blue--text subheading font-weight-bold">{{ selected.alumno.codigo }}</div>
                                            </v-card-text>
                                            <v-divider></v-divider>
                                            <v-layout
                                            tag="v-card-text"
                                            text-xs-left
                                            wrap
                                            >
                                            <v-flex tag="strong" xs5 text-xs-left mr-3 mb-2>Edad: {{ setEdad(selected.alumno.fecha_nac) }} años</v-flex>
                                            <v-flex tag="strong" xs5 text-xs-left mr-3 mb-2>Telefono: {{ selected.alumno.telefono }}</v-flex>
                                            <v-flex tag="strong" xs12 sm12 lg12 text-xs-left mr-3 mb-2>Dirección: {{ selected.alumno.direccion }}</v-flex>
                                            <v-flex tag="strong" xs12 sm12 lg12 text-xs-left mr-3 mb-2>
                                                Alergias: {{ selected.alumno.alergias !== null ? selected.alumno.alergias:'ninguna' }}
                                            </v-flex>
                                            <v-flex tag="strong" xs12 sm12 lg12 text-xs-left mr-3 mb-2>
                                                Enfermedades: {{ selected.alumno.enfermedades !== null ? selected.alumno.enfermedades:'ninguna' }}
                                            </v-flex>
                                            <v-flex> </v-flex>
                                            </v-layout>
                                        </v-card>
                                        </v-scroll-y-transition>
                                    </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card>
                        </v-flex>
                    </v-flex>
                </v-layout>
            </v-card>
            </v-container>
        </v-flex>
    </v-layout>
</template>

<script>
import moment from 'moment'

const avatars = [
    '?accessoriesType=Blank&avatarStyle=Circle&clotheColor=PastelGreen&clotheType=ShirtScoopNeck&eyeType=Wink&eyebrowType=UnibrowNatural&facialHairColor=Black&facialHairType=MoustacheMagnum&hairColor=Platinum&mouthType=Concerned&skinColor=Tanned&topType=Turban',
    '?accessoriesType=Sunglasses&avatarStyle=Circle&clotheColor=Gray02&clotheType=ShirtScoopNeck&eyeType=EyeRoll&eyebrowType=RaisedExcited&facialHairColor=Red&facialHairType=BeardMagestic&hairColor=Red&hatColor=White&mouthType=Twinkle&skinColor=DarkBrown&topType=LongHairBun',
    '?accessoriesType=Prescription02&avatarStyle=Circle&clotheColor=Black&clotheType=ShirtVNeck&eyeType=Surprised&eyebrowType=Angry&facialHairColor=Blonde&facialHairType=Blank&hairColor=Blonde&hatColor=PastelOrange&mouthType=Smile&skinColor=Black&topType=LongHairNotTooLong',
    '?accessoriesType=Round&avatarStyle=Circle&clotheColor=PastelOrange&clotheType=Overall&eyeType=Close&eyebrowType=AngryNatural&facialHairColor=Blonde&facialHairType=Blank&graphicType=Pizza&hairColor=Black&hatColor=PastelBlue&mouthType=Serious&skinColor=Light&topType=LongHairBigHair',
    '?accessoriesType=Kurt&avatarStyle=Circle&clotheColor=Gray01&clotheType=BlazerShirt&eyeType=Surprised&eyebrowType=Default&facialHairColor=Red&facialHairType=Blank&graphicType=Selena&hairColor=Red&hatColor=Blue02&mouthType=Twinkle&skinColor=Pale&topType=LongHairCurly'
  ]

const pause = ms => new Promise(resolve => setTimeout(resolve, ms))

export default {
  name: "ListaAlumnos",
  props: {
      source: String
    },
  data() {
    return {
        loading: false,
        dialog: false,
        search: '',
        curso_id: null,
        curso: null,
        active: [],
        avatar: null,
        open: [],
        users: [],
        items: []
    }
  },

  created() {
    let self = this
    self.curso_id = self.$route.params.id
    self.get(self.curso_id)
    self.getAlumnos(self.curso_id)
  },
  
    watch: {
        selected: 'randomAvatar'
    },

  methods: {
       //obtener registro
      get(id){
          let self = this
            self.loading = true
            self.$store.state.services.asignacionProfesorService
            .getOne(id)
            .then(r => {
                self.loading = false
                if (self.$store.state.global.captureError(r)) {
                    return
                }
                self.curso = r.data
            }).catch(e => {

            })
      },

       getAlumnos(id){
          let self = this
            self.loading = true
            self.$store.state.services.asignacionProfesorService
            .getAlumnos(id)
            .then(r => {
                self.loading = false
                if (self.$store.state.global.captureError(r)) {
                    return
                }
                self.items = r.data
            }).catch(e => {

            })
      },

        setEdad(date){
            return moment().diff(date, 'years');
        },

        getAvatar(foto){
            let self = this
            return foto !== null ? self.$store.state.base_url+foto : self.$store.state.base_url+'img/user_empty.png'
        },

        async fetchUsers (item) {
            // Remove in 6 months and say
            // you've made optimizations! :)
            await pause(1500)

            return fetch('https://jsonplaceholder.typicode.com/users')
            .then(res => res.json())
            .then(json => (item.children.push(...json)))
            .catch(err => console.warn(err))
        },

        randomAvatar () {
            console.log("paso")
            this.avatar = avatars[Math.floor(Math.random() * avatars.length)]
        }
  },

  computed: {
      selected () {
        if (!this.active.length) return undefined

        const id = this.active[0]

        return this.items.find(alumno => alumno.id === id)
      }
  },
};
</script>