import moment from 'moment'
export default {

    //funcion para convertir decimales a horas, si no se envia los paramtros opciones devuelve hora completa
    decimalToHour(a, only_hour = false, only_minutes = false) {
        var hrs = parseInt(Number(a))
        var min = Math.round((Number(a) - hrs) * 60)
        hrs < 10 ? hrs = '0' + hrs : hrs
        min < 10 ? min = '0' + min : min
        return only_hour ? hrs : only_minutes ? min : hrs + ':' + min
    },

    getHoursByMinutes(mins) {
        var decimals = mins / 60
        return this.decimalToHour(decimals)
    },

    formatPrice(value, symbol = 'Q') {
        let val = (value / 1).toFixed(2).replace('.', '.')
        return symbol + ' ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    },

    //obtener mes por numero
    getMonthByNumber(number) {
        var months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
            "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
        ];
    },

    returnMes(mes) {
        let self = this
        return moment().month(mes - 1).format('MMMM')
    },

    //formatear codigo para tarjeta de reponsabilidades
    formatCode(n, len = 4) {
        return (new Array(len + 1).join('0') + n).slice(-len)
    },

    //obtener full name
    getFullName(data, tercer_nombre = false) {
        Object.keys(data).forEach(function(key) {
            if (data[key] === null) {
                data[key] = '';
            }
        })
        var pn = data.primer_nombre
        var sn = data.segundo_nombre
        var tn = tercer_nombre ? data.tercer_nombre : ''
        var pa = data.primer_apellido
        var sa = data.segundo_apellido
        var name = pn + ' ' + sn + ' ' + tn + ' ' + pa + ' ' + sa
        return name.replace(/\s+/g, " ").replace(/^\s|\s$/g, "");
    },

    //funcion para convertir numeros a letras
    numeroALetras(num) {
        var data = {
            numero: num,
            enteros: Math.floor(num),
            centavos: (((Math.round(num * 100)) - (Math.floor(num) * 100))),
            letrasCentavos: "",
            letrasMonedaPlural: '', //"PESOS", 'Dólares', 'etcs'
            letrasMonedaSingular: '', //"PESO", 'Dólar', 'etc'

            letrasMonedaCentavoPlural: "CENTAVOS",
            letrasMonedaCentavoSingular: "CENTAVO"
        };

        if (data.centavos > 0) {
            data.letrasCentavos = "CON " + (function() {
                if (data.centavos == 1)
                    return this.Millones(data.centavos) + " " + data.letrasMonedaCentavoSingular
                else
                    return this.Millones(data.centavos) + " " + data.letrasMonedaCentavoPlural
            })();
        };

        if (data.enteros == 0)
            return "CERO " + data.letrasMonedaPlural + " " + data.letrasCentavos
        if (data.enteros == 1)
            return this.Millones(data.enteros) + " " + data.letrasMonedaSingular + " " + data.letrasCentavos
        else
            return this.Millones(data.enteros) + " " + data.letrasMonedaPlural + " " + data.letrasCentavos
    },


    Unidades(num) {

        switch (num) {
            case 1:
                return "UN"
            case 2:
                return "DOS"
            case 3:
                return "TRES"
            case 4:
                return "CUATRO"
            case 5:
                return "CINCO"
            case 6:
                return "SEIS"
            case 7:
                return "SIETE"
            case 8:
                return "OCHO"
            case 9:
                return "NUEVE"
        }

        return "";
    },

    Decenas(num) {

        var decena = Math.floor(num / 10);
        var unidad = num - (decena * 10);

        switch (decena) {
            case 1:
                switch (unidad) {
                    case 0:
                        return "DIEZ"
                    case 1:
                        return "ONCE"
                    case 2:
                        return "DOCE"
                    case 3:
                        return "TRECE"
                    case 4:
                        return "CATORCE"
                    case 5:
                        return "QUINCE"
                    default:
                        return "DIECI" + this.Unidades(unidad);
                }
            case 2:
                switch (unidad) {
                    case 0:
                        return "VEINTE"
                    default:
                        return "VEINTE Y " + this.Unidades(unidad)
                }
            case 3:
                return this.DecenasY("TREINTA Y", unidad)
            case 4:
                return this.DecenasY("CUARENTA Y", unidad)
            case 5:
                return this.DecenasY("CINCUENTA Y", unidad)
            case 6:
                return this.DecenasY("SESENTA Y", unidad)
            case 7:
                return this.DecenasY("SETENTA Y", unidad)
            case 8:
                return this.DecenasY("OCHENTA Y", unidad)
            case 9:
                return this.DecenasY("NOVENTA Y", unidad)
            case 0:
                return this.Unidades(unidad)
        }
    },

    DecenasY(strSin, numUnidades) {
        if (numUnidades > 0)
            return strSin + " Y " + this.Unidades(numUnidades)

        return strSin;
    },

    Centenas(num) {
        var centenas = Math.floor(num / 100);
        var decenas = num - (centenas * 100);

        switch (centenas) {
            case 1:
                if (decenas > 0)
                    return "CIENTO " + this.Decenas(decenas)
                return "CIEN";
            case 2:
                return "DOSCIENTOS " + this.Decenas(decenas)
            case 3:
                return "TRESCIENTOS " + this.Decenas(decenas)
            case 4:
                return "CUATROCIENTOS " + this.Decenas(decenas)
            case 5:
                return "QUINIENTOS " + this.Decenas(decenas)
            case 6:
                return "SEISCIENTOS " + this.Decenas(decenas)
            case 7:
                return "SETECIENTOS " + this.Decenas(decenas)
            case 8:
                return "OCHOCIENTOS " + this.Decenas(decenas)
            case 9:
                return "NOVECIENTOS " + this.Decenas(decenas)
        }

        return this.Decenas(decenas);
    },

    Seccion(num, divisor, strSingular, strPlural) {
        var cientos = Math.floor(num / divisor)
        var resto = num - (cientos * divisor)

        var letras = "";

        if (cientos > 0)
            if (cientos > 1)
                letras = this.Centenas(cientos) + " " + strPlural
            else
                letras = strSingular

        if (resto > 0)
            letras += ""

        return letras;
    },

    Miles(num) {
        var divisor = 1000;
        var cientos = Math.floor(num / divisor)
        var resto = num - (cientos * divisor)

        var strMiles = this.Seccion(num, divisor, "UN MIL", "MIL")
        var strCentenas = this.Centenas(resto)

        if (strMiles == "")
            return strCentenas

        return strMiles + " " + strCentenas
    },

    Millones(num) {
        var divisor = 1000000;
        var cientos = Math.floor(num / divisor)
        var resto = num - (cientos * divisor)

        var strMillones = this.Seccion(num, divisor, "UN MILLON DE", "MILLONES DE");
        var strMiles = this.Miles(resto);

        if (strMillones == "")
            return strMiles;

        return strMillones + " " + strMiles;
    },

    getLogo() {
        return 'data:image/jpeg;base64,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'
    },

    getCircleLogo() {
        return 'data:image/png;base64,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'
    }
}