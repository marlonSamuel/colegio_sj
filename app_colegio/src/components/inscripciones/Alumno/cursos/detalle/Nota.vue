<template>
  <v-layout align-startv-loading="loading">
      <v-flex>
          <v-dialog v-model="dialog" max-width="1000px">
            <v-card>
                <v-card-text>
                    <v-btn @click="print"><v-icon>print</v-icon></v-btn>
                        <div ref="boleta" id="boleta">
                            <div>
                                <table id="facarticulo">
                                    <tbody>
                                        <tr>
                                            <td style="text-align:center;"  >
                                                <div id="logo">
                                                    <img :src="logo" id="imagen" width="150px;" height="100px;">
                                                </div>
                                            </td>
                                            <td style="text-align:left;">
                                                    <p id="encabezado">
                                                        <b>IncanatoIT</b><br>José Gálvez 1368, Chongoyape - Chiclayo, Perú<br>Telefono:(+51)931742904<br>Email:jcarlos.ad7@gmail.com
                                                    </p>
                                            </td>
                                            <td style="text-align:right;">
                                                <p>Factura<br>
                                                0001-0004<br>
                                                15/12/2018</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <section>
                                <div>
                                    <table id="facliente">
                                        <tbody>
                                            <tr>
                                                <td id="cliente">
                                                    <strong>Alumno:</strong> 47715777<br>
                                                    <strong>Grado:</strong> Zarumilla 113 - Chiclayo<br>
                                                    <strong>Curso:</strong> 931742904<br>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                            <br>
                            <section>
                                <div>
                                    <table id="facarticulo">
                                        <thead>
                                            <tr id="fa">
                                                <th>CANT</th>
                                                <th>DESCRIPCION</th>
                                                <th>PRECIO UNIT</th>
                                                <th>DESC.</th>
                                                <th>PRECIO TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align:center;">cant</td>
                                                <td>descripcion del producto descripcion del producto descripcion del producto</td>
                                                <td style="text-align:right;">precio uni</td>
                                                <td style="text-align:right;">descuento</td>
                                                <td style="text-align:right;">precio total</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align:right;">SUBTOTAL</th>
                                                <th style="text-align:right;">subtotal</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align:right;">IVA</th>
                                                <th style="text-align:right;">iva</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align:right;">TOTAL</th>
                                                <th style="text-align:right;">total</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </section>
                            <br>
                            <br>
                            <footer>
                                <div id="gracias">
                                    <p><b>Gracias por su compra!</b></p>
                                </div>
                            </footer>
                        </div>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-card>
            <v-card-title>
                NOTAS
            </v-card-title>
            <v-card-text>
                <v-btn @click="openDialog"><v-icon>print</v-icon></v-btn>
                
            </v-card-text>
        </v-card>
      </v-flex>
  </v-layout>
</template>

<style scoped media="print" type="text/css">
    #boleta {
        padding: 20px;
        font-family: Arial, sans-serif;
        font-size: 16px ;
    }

    #logo {
        float: left;
        margin-left: 2%;
        margin-right: 2%;
    }
    #imagen {
        width: 100px;
    }

    #fact {
        font-size: 18px;
        font-weight: bold;
        text-align: center;
    }   

    #datos {
        float: left;
        margin-top: 0%;
        margin-left: 2%;
        margin-right: 2%;
        /*text-align: justify;*/
    }

    #encabezado {
        text-align: center;
        margin-left: 10px;
        margin-right: 10px;
        font-size: 16px;
    }

    section {
        clear: left;
    }

    #cliente {
        text-align: left;
    }

    #facliente {
        width: 40%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
    }

    #fa {
        color: #FFFFFF;
        font-size: 14px;
    }

    #facarticulo {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        padding: 20px;
        margin-bottom: 15px;
    }

    #facarticulo thead {
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;
    }

    #gracias {
        text-align: center;
    }
</style>

<script>
import moment from 'moment'

export default {
  name: "NotasView",
  components: {

  },
  props: {
      source: String
    },
  data() {
    return {
      loading: false,
      dialog: false,
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
    //self.get()
    events.$on('asignaciones_alumno',self.onEventAsignacion)

  },

  beforeDestroy(){
      let self = this
      events.$off('asignaciones_alumno',self.onEventAsignacion)
  },

  methods: {
    onEventAsignacion(){
        let self = this 
    },

    openDialog(){
        let self = this
        self.dialog = true
    },

    print(){
         var divContents = document.getElementById("boleta").innerHTML; 
        var a = window.open('', '','_blank', 'height=500, width=500'); 
        a.document.write(divContents); 
        a.document.close(); 
        a.print();
        
    },

    printDiv(){
        let printContents, popupWin
        printContents = document.getElementById('boleta').innerHTML
        popupWin = window.open('', '_blank', 'top=0,left=0,height=100%,width=auto')
        popupWin.document.open()
        
        popupWin.document.write(`
            <html>
            <head>
                <link rel="stylesheet" href="{{ asset('lib/bootstrap/css/bootstrap.css') }}">
            </head>
            <style>

                @media print {
                    body { font-size: 10pt }
                    }
                    @media screen {
                        body { font-size: 13px }
                    }
                    @media screen, print {
                        body { line-height: 1.2 }
                    }
            </style>
                <body onload="window.print();window.close()">${printContents}</body>
            </html>`
        );
        popupWin.document.close();
    }
  },

  computed: {
      logo(){
        let self = this
        return self.$store.state.global.getLogo()
      }
    },
};
</script>