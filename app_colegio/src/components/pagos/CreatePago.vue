<template>
    <v-layout>
        <v-flex>
            <v-dialog persistent v-model="dialog" max-width="1000px" align-start>
                <v-card>
                    <v-card-title>
                        <span class="headline" v-if="inscripcion !== null">Nuevo pago ciclo escolar {{inscripcion.ciclo.ciclo}}</span>
                    </v-card-title>
                    <v-card-text>
                        <v-form>
                            <v-container>
                                <v-layout wrap>
                                    <v-flex xs12 sm6 md6 lg6>
                                        <v-autocomplete
                                            v-model="form.cuota_id"
                                            label="Seleccione concepto pago"
                                            placeholder="Concepto pago"
                                            :items="conceptos"
                                            :readonly="default_cuota"
                                            item-text="concepto_pago.nombre"
                                            item-value="id"
                                            @input="selectConcepto"
                                            prepend-icon="add"
                                            >
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12 sm6 md6>
                                        <v-menu
                                            v-model="menu"
                                            :close-on-content-click="false"
                                            :nudge-right="40"
                                            lazy
                                            transition="scale-transition"
                                            offset-y
                                            full-width
                                            min-width="290px"
                                        >
                                            <template v-slot:activator="{ on }">
                                            <v-text-field
                                                prepend-icon="date_range"
                                                clearable
                                                v-model="dateFormatted"
                                                label="Fecha"
                                                placeholder="ingrese fecha de inscripción"
                                                v-on="on"
                                                v-validate="'required'"
                                                data-vv-name="fecha"
                                                :error-messages="errors.collect('fecha')"
                                            ></v-text-field>
                                            </template>
                                            <v-date-picker locale="es" v-model="form.fecha" @input="menu = false"></v-date-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs12 sm4 md4 lg4>
                                        <v-text-field v-model="form.serie"
                                            prepend-icon="code" 
                                            label='Serie factura'
                                            readonly
                                            v-validate="'required'"
                                            type="text"
                                            data-vv-name="serie"
                                            :error-messages="errors.collect('serie')">
                                        </v-text-field>
                                    </v-flex>
                                     <v-flex xs12 sm4 md4 lg4>
                                        <v-text-field v-model="form.factura" 
                                            label='No factura'
                                            prepend-icon="confirmation_number"
                                            v-validate="'required|integer'"
                                            type="text"
                                            readonly
                                            data-vv-name="no_factura"
                                            data-vv-as="numero de factura"
                                            :error-messages="errors.collect('no_factura')">
                                        </v-text-field>
                                    </v-flex>
                                    <template v-if="concepto !== null">
                                        <v-flex xs12 sm6 md6 lg6 v-if="concepto.concepto_pago.is_parcial && concepto !== null">
                                            <v-checkbox
                                                v-model="form.is_credito"
                                                label="pago al crédito"
                                                ></v-checkbox>
                                        </v-flex>
                                        <v-flex xs12 sm4 md4 lg4>
                                            <v-text-field v-model="form.total_cancelado" 
                                                :label="form.is_credito ? 'Abono':'Total pagado'"
                                                v-validate="form.is_credito ? 'required':''"
                                                type="text"
                                                :readonly="!form.is_credito"
                                                data-vv-name="total_cancelado"
                                                prepend-icon="money"
                                                :error-messages="errors.collect('total_cancelado')">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex md12 v-if="concepto.concepto_pago.forma_pago === 'M'">
                                            <h3 class="grey--text">Seleccione meses a cancelar</h3>
                                            <v-layout row wrap>
                                                <v-flex xs6 sm2 md2 v-for="mes in meses_show" :key="mes.id">
                                                    <v-checkbox
                                                    v-model="form.meses"
                                                    :label="mes.mes"
                                                    color="primary"
                                                    :value="mes.id"
                                                    hide-details
                                                    :disabled="validateDisabled(mes.id)"
                                                    ></v-checkbox>
                                                </v-flex>
                                            </v-layout>
                                        </v-flex>

                                        
                                        <v-flex xs12 sm8 md8 lg8 offset-sm2>
                                            <v-card>
                                                <v-card-title>
                                                <div>
                                                    <h3 class="grey--text">Detalle pago</h3>
                                                    <h4>cuota: {{concepto.cuota | currency('Q ')}}</h4>
                                                    <h4>
                                                        por concepto de {{concepto.concepto_pago.nombre | lowercase()}}
                                                        <span v-for="m in form.meses" :key="m.id">
                                                            {{getLabelMeses(m)}}
                                                        </span>
                                                    </h4>
                                                    <h4>por inscripcion no {{inscripcion.numero}} ciclo escolar {{inscripcion.ciclo.ciclo}}</h4>
                                                </div>
                                                </v-card-title>
                                                <v-card-actions>
                                                <v-btn flat color="blue">total: {{getTotal | currency('Q ')}} </v-btn>
                                                <v-btn flat color="blue">total cancelado : {{form.total_cancelado}}</v-btn>
                                                </v-card-actions>
                                            </v-card>
                                        </v-flex>
                                    </template>
                                </v-layout>
                            </v-container>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-1" flat @click="close">Volver</v-btn>
                    <v-btn color="blue darken-1" flat @click="validate">Guardar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-flex>
    </v-layout>
</template>

<script>
import moment from 'moment'
import router from '../../router';
export default {
  name: "CreatePago",
  components: {
  },
  props: {
    },
  data() {
    return {
        loading: true,
        inscripcion: null,
        conceptos: [],
        concepto: null,
        dialog: false,
        menu: false,
        default_cuota: false,
        pagos: [],
        meses: [],
        meses_show: [],
        form: {
            id: null,
            inscripcion_id: null,
            cuota_id: null,
            apoderado_id: null,
            is_credito: false,
            total: 0,
            total_cancelado: 0,
            serie: '',
            factura: null,
            serie_factura_id: null,
            pago_mensual: false,
            fecha: new Date().toISOString().substr(0, 10),
            meses: []
        }
    };
  },

  created() {
    let self = this
    events.$on('open_dialog_pago',self.onEventOpenDialog)
  },

  beforeDestroy(){
      let self = this
      events.$off('open_dialog_pago',self.onEventOpenDialog)
  },

  methods: {
    onEventOpenDialog(inscripcion, pagos, cuota_id = null, responsable){
        let self = this
        self.clearData()
        self.form.cuota_id = cuota_id
        self.form.apoderado_id = responsable.id
        cuota_id !== null ? self.default_cuota = true : self.default_cuota = false
        self.pagos = pagos.filter(x=>!x.anulado)
        self.dialog = true
        self.inscripcion = inscripcion
        self.form.inscripcion_id = inscripcion.id
        inscripcion !== null ? self.getCuotas(inscripcion.grado_nivel_educativo_id, inscripcion.ciclo_id) : ''
        self.getMeses()
        self.meses_show = self.monthsOfDate(inscripcion.ciclo.inicio, inscripcion.ciclo.fin)
        self.getSerie()
    },

    filterCuotas(){
        let self = this
        self.pagos.forEach(item => {
            if(item.cuota.concepto_pago.forma_pago == 'A'){
                var i = self.conceptos.findIndex(x=>x.id === item.cuota_id)
                if(i>=0){
                    self.conceptos.splice(i,1)
                }
            }
        })
    },
    
    getCuotas(id, ciclo_id){
        let self = this
        self.loading = true
        let data = self.form
        self.$store.state.services.gradoNivelEducativoService
        .getCuotasCiclo(id, ciclo_id)
        .then(r => {
            self.loading = false
            if(r.data.length == 0){
                self.$toastr.error('no se han configurado los montos de las cuotas para ciclo escolar seleccionado, por favor vuelta y configure cuotas')
                self.$router.push('/asignar_cuota/'+ciclo_id+'/'+id)
                return
            }
            self.conceptos = r.data
            self.filterCuotas()
            self.form.cuota_id !== null ? self.selectConcepto(self.form.cuota_id) : ''
        }).catch(r => {});
    },

    getSerie(){
        let self = this
        self.loading = true
        let data = self.form
        self.$store.state.services.serieFacturaService
        .getActiva()
        .then(r => {
            self.loading = false
            if(r.response){
                this.$toastr.error(r.response.data.error, 'error')
                self.$router.push('/serie_factura')
                return
            }
            self.form.serie_factura_id = r.data.id
            self.form.serie = r.data.serie
            if(r.data.pagos.length > 0){
                self.form.factura = self.formatCode(r.data.no_actual+1, String(r.data.no_facturas).length)
            }else{
                self.form.factura = self.formatCode(r.data.no_actual, String(r.data.no_facturas).length)
            }
        }).catch(r => {});
    },

    getMeses(id){
        let self = this
        self.loading = true
        let data = self.form
        self.$store.state.services.mesService
        .getAll(id)
        .then(r => {
            self.loading = false
            self.meses = r.data
        }).catch(r => {});
    },

    //funcion para guardar registro
    create(){
      let self = this
      self.loading = true
      let data = self.form

      if(self.concepto.concepto_pago.forma_pago === 'M' && data.meses.length == 0){
          this.$toastr.error('debe seleccionar al menos un mes', 'error')
          return
      }

      self.$store.state.services.pagoService
        .create(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('pago realizado con éxito', 'éxito')
          self.close()
          events.$emit('get_pagos_ciclo',self.inscripcion)
          self.clearData()
        })
        .catch(r => {});
    },

    //funcion, validar si se guarda o actualiza
    validate(){
      this.$validator.validateAll().then((result) => {
          if (result) {
              self.create()
           }
      });

      let self = this
    },

    //limpiar data de formulario
    clearData(){
        let self = this
        self.concepto = null

        Object.keys(self.form).forEach(function(key,index) {
          if(typeof self.form[key] === "string") 
            self.form[key] = ''
          else if (typeof self.form[key] === "boolean") 
            self.form[key] = false
          else if (typeof self.form[key] === "number") 
            self.form[key] = null
        });
        
        self.form.total_cancelado = 0
        self.form.meses = []
        self.$validator.reset()
    },

    selectConcepto(id){
        let self = this
        self.concepto = self.conceptos.find(x=>x.id === id)
    },

    setTotal(){
        let self = this
    },
    close(){
        let self = this
        self.dialog = false
    },
    

    getLabelMeses(id){
        let self = this
        var mes = self.meses.find(x=>x.id == id)
        return ', '+mes.mes
    },

    monthsOfDate(startDate,endDate){
        let self = this
        startDate = moment(startDate);
        endDate = moment(endDate);
        var meses = [];    
        while (startDate.isBefore(endDate)) {
            var id = parseInt(startDate.format("M"))
            var mes = moment().month(id - 1).format('MMMM')
            meses.push({
                id: id,
                mes: mes
            })

            startDate.add(1, 'month')
        }
        return meses
    },

    validateDisabled(id){
        let self = this
        var pagos = self.pagos.filter(x=>x.cuota_id == self.concepto.id)
        var meses_cancelados = []
        pagos.forEach(pago => {
            meses_cancelados = meses_cancelados.concat(pago.pagos_meses)
        })

        return !!meses_cancelados.find(x => x.mes_id === id)
    },

    //formatear codigo para tarjeta de reponsabilidades
    formatCode(n, len = 4) {
        return (new Array(len + 1).join('0') + n).slice(-len)
    },
  },

  computed: {
      getTotal(){
        let self = this
        if(self.concepto.concepto_pago.forma_pago === 'M'){
            self.form.pago_mensual = true
            self.form.total = self.concepto.cuota * self.form.meses.length
            self.form.total_cancelado = self.concepto.cuota * self.form.meses.length
            if(self.form.is_credito){
                self.form.total_cancelado = 0
            }
        }else {
            self.form.pago_mensual = false
            self.form.total = self.concepto.cuota
            self.form.total_cancelado = self.concepto.cuota
            self.form.meses = []
            if(self.form.is_credito){
                self.form.total_cancelado = 0
            }
        }

        return self.form.total
      },

      dateFormatted(){
        let self = this
        self.form.fecha = moment().format('YYYY-MM-DD')
        return moment(self.form.fecha !== '' ? self.form.fecha : moment()).format('DD/MM/YYYY')
      }
    },
};
</script>
<style>
.item {
    padding: 5px 0;
  }
</style>