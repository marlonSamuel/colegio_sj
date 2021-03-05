<template>
  <v-layout align-startv-loading="loading">
      <v-flex>
          <v-card>
               <v-card-title>
                    <b>TAREAS</b>
                </v-card-title>
                <v-card-text>
        <v-data-table
                :items="tareas"
                class="elevation-1"
                hide-actions
                :headers="headers"
                >
                <template v-slot:items="props">
                    <tr>
                        <td class="text-xs-left">
                            <v-icon @click="props.expanded = !props.expanded">{{!props.expanded ? 'keyboard_arrow_right' : 'keyboard_arrow_down' }} </v-icon>
                            {{props.item.asignacion.titulo}}
                        </td>
                        <td class="text-xs-left">{{props.item.asignacion.fecha_entrega | moment('DD/MM/YYYY')}}</td>
                        <td class="text-xs-left">{{props.item.asignacion.nota}} pts</td>
                        <td class="text-xs-left">{{props.item.nota}} pts</td>
                        <td class="text-xs-left">
                            <span v-if="props.item.entregado" class="blue--text"> Entregado </span>
                                <span v-else class="red--text"> Sin entregar </span>
                                <span v-if="props.item.calificado" class="green--text"> Calificado</span>
                        </td>
                        <td class="text-xs-left">
                            <v-tooltip top v-if="isValid(props.item)">
                                <template v-slot:activator="{ on }">
                                    <v-btn flat small v-on="on" color="primary" @click="navigateTo(props.item)">
                                    <v-icon fab dark>edit</v-icon> {{props.item.entregado ? 'Reenviar' : 'Responder'}}</v-btn>
                                </template>
                                    <span>{{props.item.entregado ? 'Reenviar tarea' : 'Responder tarea'}}</span>
                            </v-tooltip>
                        </td>
                    </tr>
                </template>
                <template v-slot:expand="props">
                    <v-card flat>
                    <v-card-text>
                        <hr /><hr />
                        <v-container fluid grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 md6 lg6>
                                    <h3>Información de la asignación</h3>
                                                    <hr />
                                </v-flex>
                                <v-flex xs12 md6 lg6>
                                    <h3>Información de respuesta</h3>
                                                    <hr />
                                </v-flex>
                                <v-flex xs12 md6 lg6>
                                    <b>Título: </b>{{props.item.asignacion.titulo}} <br />
                                    <b>Fecha habilita: </b> {{props.item.asignacion.fecha_habilitacion | moment('DD/MM/YYYY')}} <br />
                                    <b>Fecha entrega:</b> {{props.item.asignacion.fecha_entrega | moment('DD/MM/YYYY')}}<br />
                                    <b>Nota:</b> {{props.item.asignacion.nota}} pts <br />
                                    <b>Podrá entregarse tarde: </b> {{props.item.asignacion.entrega_tarde ? 'SI' : 'NO'}} <br />
                                    <b>Adjunto: </b> 
                                        <v-tooltip top v-if="props.item.asignacion.adjunto !== null && props.item.asignacion.adjunto !==''">
                                            
                                            <template v-slot:activator="{ on }">
                                                <v-icon color="error" @click="verAdjunto(props.item.asignacion.adjunto)" v-on="on"> file_download_off</v-icon>
                                            </template>
                                            <span>Descargar archivo adjunto</span>
                                        </v-tooltip>
                                        <br />
                                    <b>Descripcion:</b> {{props.item.asignacion.descripcion}} <br />
                                </v-flex>
                                <v-flex xs12 md6 lg6>
                                    <b>Fecha entrega:</b> {{props.item.fecha_entrega | moment('DD/MM/YYYY')}}<br />
                                    <b>Nota:</b> {{props.item.nota}} pts <br />
                                    <b>Entregó tarde: </b> {{props.item.entrega_tarde ? 'SI' : 'NO'}} <br />
                                    <b>Calificado: </b> {{props.item.calificado ? 'SI' : 'NO'}} <br />
                                    <b>Adjunto: </b> 
                                        <v-tooltip top v-if="props.item.adjunto !== null && props.item.adjunto !==''">
                                            
                                            <template v-slot:activator="{ on }">
                                                <v-icon color="error" @click="verAdjunto(props.item.adjunto)" v-on="on"> file_download_off</v-icon>
                                            </template>
                                            <span>Descargar archivo adjunto</span>
                                        </v-tooltip>
                                        <br />
                                    <b>Observaciones:</b> {{props.item.observaciones}} <br />
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <hr /><hr />
                    </v-card-text>
                    </v-card>
                </template>
            </v-data-table>

            <template>
                <br />
                   <b> Puntos acumulados ({{getText(tareas)}})</b>
                <v-progress-linear
                    color="success"
                    height="8"
                    :value="getVal(tareas)"

                    ></v-progress-linear>
            </template>

            </v-card-text>
          
                <v-card-title>
                    <b>CUESTIONARIOS</b>
                </v-card-title>
                <v-card-text>
                <v-data-table
                    :items="cuestionarios"
                    class="elevation-1"
                    hide-actions
                    :headers="headers"
                    >
                    <template v-slot:items="props">
                        <tr>
                            <td class="text-xs-left">
                                <v-icon @click="props.expanded = !props.expanded">{{!props.expanded ? 'keyboard_arrow_right' : 'keyboard_arrow_down' }} </v-icon>
                                {{props.item.asignacion.titulo}}
                            </td>
                            <td class="text-xs-left">{{props.item.asignacion.fecha_entrega | moment('DD/MM/YYYY')}}</td>
                            <td class="text-xs-left">{{props.item.asignacion.nota}} pts</td>
                            <td class="text-xs-left">{{props.item.nota}} pts</td>
                            <td class="text-xs-left">
                                <span v-if="props.item.entregado" class="blue--text"> Entregado </span>
                                <span v-else class="red--text"> Sin entregar </span>
                                <span v-if="props.item.calificado" class="green--text"> Calificado</span>
                            </td>
                            <td class="text-xs-left">
                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-btn flat small  v-on="on" color="primary" @click="navigateTo(props.item)">
                                            <v-icon fab dark>{{!isValid(props.item) ? 'remove_red_eye' : 'edit'}}</v-icon> {{!isValid(props.item) ? 'Ver' :  'Responder'}}
                                        </v-btn>
                                    </template>
                                    <span>{{!isValid(props.item) ? 'Ver resultados' :  'Responder cuestionario'}}</span>
                                </v-tooltip>
                            </td>
                        </tr>
                    </template>
                    <template v-slot:expand="props">
                    <v-card flat>
                    <v-card-text>
                        <hr /><hr />
                        <v-container fluid grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 md6 lg6>
                                    <h3>Información de la asignación</h3>
                                                    <hr />
                                </v-flex>
                                <v-flex xs12 md6 lg6>
                                    <h3>Información de respuesta</h3>
                                                    <hr />
                                </v-flex>
                                <v-flex xs12 md6 lg6>
                                    <b>Título: </b>{{props.item.asignacion.titulo}} <br />
                                    <b>Fecha habilita: </b> {{props.item.asignacion.fecha_habilitacion | moment('DD/MM/YYYY')}} <br />
                                    <b>Fecha entrega:</b> {{props.item.asignacion.fecha_entrega | moment('DD/MM/YYYY')}}<br />
                                    <b>Nota:</b> {{props.item.asignacion.nota}} pts <br />
                                    <b>Podrá entregarse tarde: </b> {{props.item.asignacion.entrega_tarde ? 'SI' : 'NO'}} <br />
                                    <b>Adjunto: </b> 
                                        <v-tooltip top v-if="props.item.asignacion.adjunto !== null && props.item.asignacion.adjunto !==''">
                                            
                                            <template v-slot:activator="{ on }">
                                                <v-icon color="error" @click="verAdjunto(props.item.asignacion.adjunto)" v-on="on"> file_download_off</v-icon>
                                            </template>
                                            <span>Descargar archivo adjunto</span>
                                        </v-tooltip>
                                        <br />
                                    <b>Descripcion:</b> {{props.item.asignacion.descripcion}} <br />
                                </v-flex>
                                <v-flex xs12 md6 lg6>
                                    <b>Fecha entrega:</b> {{props.item.fecha_entrega | moment('DD/MM/YYYY')}}<br />
                                    <b>Nota:</b> {{props.item.nota}} pts <br />
                                    <b>Entregó tarde: </b> {{props.item.entrega_tarde ? 'SI' : 'NO'}} <br />
                                    <b>Calificado: </b> {{props.item.calificado ? 'SI' : 'NO'}} <br />
                                    <b>Adjunto: </b> 
                                        <v-tooltip top v-if="props.item.adjunto !== null && props.item.adjunto !==''">
                                            
                                            <template v-slot:activator="{ on }">
                                                <v-icon color="error" @click="verAdjunto(props.item.adjunto)" v-on="on"> file_download_off</v-icon>
                                            </template>
                                            <span>Descargar archivo adjunto</span>
                                        </v-tooltip>
                                        <br />
                                    <b>Observaciones:</b> {{props.item.observaciones}} <br />
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <hr /><hr />
                    </v-card-text>
                    </v-card>
                </template>
                </v-data-table>
                <template>
                    <br />
                   <b> Puntos acumulados ({{getText(cuestionarios)}})</b>
                <v-progress-linear
                        color="success"
                        height="8"
                        :value="getVal(cuestionarios)"
                        ></v-progress-linear>
                </template>
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
      dialog: false,
      alumno: null,
      inscripcion: null,
      loading: false,
      current_date: moment(),
      asignaciones: [],
      tareas: [],
      cuestionarios: [],
      inscripcion_id: null,
      curso_grado_nivel_id: null,

     headers: [
        { text: 'Asignacion', value: 'asignacion.titulo' },
        { text: 'Fecha entrega', value: 'asignacion.fecha_entrega' },
        { text: 'Nota', value: 'asignacion.nota' },
        { text: 'Nota alumno', value: 'nota' },
        { text: 'Estado', value: '' },
        { text: 'Acciones', value: '', sortable: false }
      ],
    }
  },

  created() {
    let self = this
    self.inscripcion_id = self.$route.params.inscripcion_id
    self.curso_grado_nivel_id = self.$route.params.curso_grado_nivel_id
    events.$on('asignaciones_alumno',self.onEventAsignacion)
  },

  beforeDestroy(){
      let self = this
      events.$off('asignaciones_alumno',self.onEventAsignacion)
  },

  methods: {
    onEventAsignacion(data){
        let self = this
        self.setData(data)
    },

      setData(data){
        let self = this
        data = data.filter(x=>moment(x.asignacion.fecha_habilitacion).format('YYYY-MM-DD') <= moment().format('YYYY-MM-DD'))
        self.tareas = data.filter(x=>!x.asignacion.cuestionario)
        self.cuestionarios = data.filter(x=>x.asignacion.cuestionario)
      },

       //obtener valor para pago al credito
    getVal(data){
      let self = this

      var total_1 = data.reduce(function(a, b) {
          return a + parseFloat(b.asignacion.nota)
      }, 0)

      var total_2 = data.reduce(function(a, b) {
          return a + parseFloat(b.nota)
      }, 0)
      var val = (parseFloat(total_2) * 100) / parseFloat(total_1)

      return val.toFixed(2)
      
    },

    //obtener texto
    getText(data){
      let self = this
      var total_1 = data.reduce(function(a, b) {
          return a + parseFloat(b.asignacion.nota)
      }, 0)

      var total_2 = data.reduce(function(a, b) {
          return a + parseFloat(b.nota)
      }, 0)

      return total_2.toString() + ' / ' + total_1.toString()
    },

    navigateTo(data){
        let self = this
        if(!data.asignacion.flag_tiempo){
            self.$router.push('/entrega_asignacion/'+data.id)
        }else{
            if(!data.entregado && moment(data.asignacion.fecha_entrega).format('YYYY-MM-DD') >= moment().format('YYYY-MM-DD')){
                self.$router.push('/cuestionario/curso/'+data.asignacion.asignar_curso_profesor_id+'/asignacion_alumno/'+data.id)
            }else{
                self.$router.push('/view_cuestionario/curso/'+data.asignacion.asignar_curso_profesor_id+'/asignacion_alumno/'+data.id)
            }
        }
    },

    isValid(data){
        let self = this
        let now = moment().format('YYYY-MM-DD')
        if(data.calificado){
            return false
        }
        if(!data.asignacion.entrega_tarde && (now > moment(data.asignacion.fecha_entrega).format('YYYY-MM-DD'))){
            return false
        }
        if(data.asignacion.cuestionario && data.entregado){
            return false
        }

        let rol = self.$store.state.usuario.rol
        if(rol !== undefined && rol.rol !== 'alumno'){
            return false
        }
        return true
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