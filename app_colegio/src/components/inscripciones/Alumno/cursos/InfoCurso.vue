<template>
  <v-layout align-start v-loading="loading">
      <v-flex sm12 lg12 md12>
          <v-container fluid grid-list-md>
              <v-card>
                    <v-layout row wrap justify-end>
                        <div>
                            <v-breadcrumbs :items="itemsB">
                            <template v-slot:divider>
                                <v-icon>forward</v-icon>
                            </template>
                            </v-breadcrumbs>
                        </div>
                    </v-layout>
                <v-layout row wrap>
                    <v-flex lg12 md12 xs12>
                        <v-flex>
                            <h5 flat color="white" v-if="inscripcion !== null">
                                    ALUMNO: {{getName(inscripcion.alumno) | uppercase}}<br />
                                    CICLO ESCOLAR: <span>{{inscripcion.ciclo.ciclo}}</span><br />
                                    GRADO: <span>{{inscripcion.grado_nivel_educativo.grado.nombre + ' '+inscripcion.grado_nivel_educativo.nivel_educativo.nombre | uppercase}}</span><br />
                                    CURSO: <span v-if="curso !== null">{{curso.curso.nombre | uppercase}}</span><br />
                            </h5>
                        </v-flex>
                        <v-flex>
                        <el-divider></el-divider>
                        <v-flex xs12 sm4 md4>
                            <v-autocomplete
                                v-model="periodo_id"
                                label="Seleccione periodo escolar"
                                placeholder="Periodo académico"
                                :items="periodos"
                                item-text="periodo_academico.nombre"
                                item-value="id"
                                @input="selectPeriodo"
                                >
                            </v-autocomplete>
                        </v-flex>
                            <v-layout>
                                <v-flex>
                                    <el-tabs type="border-card" @tab-click="selectOption">
                                        <el-tab-pane label="1">
                                            <span slot="label"> <v-icon small color="blue">file_copy</v-icon> Asignaciones</span>
                                                <b> Asignaciones</b>
                                                <el-divider></el-divider>
                                                <asignacion></asignacion>
                                        </el-tab-pane>
                                        <el-tab-pane label="2">
                                            <span slot="label"> <v-icon small color="blue">apartment</v-icon> Salon de clases</span>
                                                <b> Salón de clases</b>
                                                <el-divider></el-divider>
                                                 <salon-clase></salon-clase>
                                        </el-tab-pane>
                                    </el-tabs>
                                </v-flex>
                            </v-layout>
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
import Asignacion from './detalle/Asignacion'
import SalonClase from './detalle/SalonClase'
export default {
  name: "AlumnoCursos",
  components: {
      Asignacion,
      SalonClase
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
      inscripciones: [],
      asignaciones: [],
      pagos: [],
      periodos:[],
      materiales: [],
      periodo: null,
      periodo_id: null,
      curso: null,
      inscripcion_id: null,
      curso_grado_nivel_id: null,
    }
  },

  created() {
    let self = this
    self.inscripcion_id = self.$route.params.inscripcion_id
    self.curso_grado_nivel_id = self.$route.params.curso_grado_nivel_id
    self.getCurso(self.$route.params.curso_grado_nivel_id)
    self.get(self.$route.params.inscripcion_id)
  },

  beforeDestroy(){
      let self = this
  },

  methods: {
    onEventSaldo(saldo){
        let self = this
        self.saldo = saldo
    },

     //obtener alumno
    getCurso(id){
        let self = this
        self.loading = true
        let data = self.form
        self.$store.state.services.gradoNivelEducativoService
        .getCurso(id)
        .then(r => {
            self.loading = false
            self.curso = r.data

        }).catch(r => {});
    },

    //obtener alumno
    get(id){
        let self = this
        self.loading = true
        let data = self.form
        self.$store.state.services.inscripcionService
        .get(id)
        .then(r => {
            self.loading = false
            self.inscripcion = r.data
            self.getPeriodos(self.inscripcion.ciclo_id)

        }).catch(r => {});
    },

    
    //obtener asignaciones para curso
    getAsignaciones(id){
        let self = this
        self.loading = true
        self.$store.state.services.asignacionAlumnoService
        .getAsignacionByCurso(self.inscripcion_id, self.curso_grado_nivel_id)
        .then(r => {
            self.loading = false
            self.asignaciones = r.data
            console.log(self.asignaciones)
            this.$nextTick(() => {  
                events.$emit('asignaciones_alumno',self.asignaciones.filter(x=>x.asignacion.ciclo_periodo_academico_id == self.periodo_id))
            })
        }).catch(e => {

        })
      },

    //obtener material de apoyo para curso
    getApoyo(id){
        let self = this
        self.loading = true
        self.$store.state.services.materialService
        .getByCicloCurso(self.inscripcion_id, self.curso_grado_nivel_id)
        .then(r => {
            self.loading = false
            self.materiales = r.data
            this.$nextTick(() => {  
                events.$emit('material_apoyo_alumno',self.materiales.filter(x=>x.ciclo_periodo_academico_id == self.periodo_id))
            })
        }).catch(e => {

        })
      },

    //obtener bimestre actual
    getPeriodos(id){
        let self = this
        self.loading = true
        self.$store.state.services.cicloService
        .getPeriodos(id)
        .then(r => {
            self.loading = false
            if (self.$store.state.global.captureError(r)) {
                return
            }
            self.periodos = r.data
            self.periodo = r.data.find(x=>x.actual)
            if(self.periodo == null || self.periodo == undefined){
                let now = moment()
                self.periodo = r.data.find(x=>moment(now).isBetween(moment(x.inicio), moment(x.fin), undefined,'[]'))
            }
            self.periodo_id = self.periodo.id
            self.getAsignaciones()
            self.getApoyo()

        }).catch(e => {

        })
    },

    
    

    getName(data, tercer_nombre = false){
        let self = this
        return self.$store.state.global.getFullName(data, tercer_nombre)
    },

    selectOption(option){
        let self = this
        var ob = parseInt(option.label)

    },
    selectPeriodo(id){
        let self = this
        self.periodo_id = id
        let data_a = self.asignaciones.filter(x=>x.asignacion.ciclo_periodo_academico_id == id)
        let data_m = self.materiales.filter(x=>x.ciclo_periodo_academico_id == id)
        this.$nextTick(() => {  
            events.$emit('asignaciones_alumno',data_a)
            events.$emit('material_apoyo_alumno',data_m)
        })
    },
  },

  computed: {
        isAdmin(){
            let self = this
            var user = self.$store.state.usuario
            if(!_.isEmpty(user) && user.rol.rol === 'admin'){
                return true
            }
            return false
        },

      itemsB(){
          let self = this
          
          if(self.inscripcion !== null){
            
            return [{
                        text: 'GRADOS Y CURSOS',
                        disabled: false,
                        href: '#/cursos_alumnos_index/'+self.inscripcion.alumno.id,
                    },
                    {
                        text: 'CURSO',
                        disabled: true,
                    }
            ]
          }
          return []
      } 
    },
};
</script>