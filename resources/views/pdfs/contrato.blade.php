<html>
    <head>
        <style>
            /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 0cm 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 2cm;
                margin-left: 2cm;
                margin-right: 2cm;
                margin-bottom: 2cm;
            }

            /** Define the header rules **/
             header {
                position: fixed;
                top: 0.5cm;
                left: 0cm;
                right: 0cm;
                height: 2cm;

                /** Extra personal styles **/
                text-align: center;
                line-height: 0.5cm;
                font-size: 14px;
            }

            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;
            }

            .logo {
                position: fixed;
                text-align: left;
                margin: 30px, 20px, 0px, 0px;
            }

            .title-body {
                font-size: 14.5px;
                font-weight: bold;
            }

            .right {
                text-align: right;
            }
            p{
                line-height: 1.6em;
                font-size: 16px;
            }

            ol li {
              padding: 5px;
            }
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            <img class="logo" src="{{asset('img/logo2.jpg')}}" width="100px" height="100px"/> 
            <b>CONTRATO POR ADHESION POR PRESTACION DE SERVICIOS EDUCATIVOS <br />
             DEL {{strtoupper($institucion->nombre)}} </b>
        </header>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            <p class="right"> Contrato No. {{$inscripcion->numero}}<br />
            <b> Autorizado según resolución DIACO ____________</b>
            </p><br /><br />

            <p>En el municipio del puerto de san josé departamento de Escuintla el dia <span>{{$date->dia}}</span><br />
              del mes de {{$date->mes}} del año {{$date->anio}}</p><br /><br />

            <p class="title-body" align="center"> DATOS DEL PROPIETARIO O REPRESENTANTE LEGAL DEL ESTABLECIMIENTO EDUCATIVO</p>
            <p>Nombre completo: {{$institucion->representante_legal}}<br />

            De: {{ \Carbon\Carbon::createFromDate($institucion->fecha_nac)->age}} años de edad, de estado civil {{$institucion->estado_civil}}, nacionalidad {{$institucion->nacionalidad}} de profesión u oficio: {{$institucion->profesion}}, me identifico con D.P.I. numero {{$institucion->cui}} extendida por el Alcalde Municipal de: {{$institucion->municipio->nombre}}, Departamento de {{$institucion->municipio->departamento->nombre}} actuó en mi calidad de {{$institucion->calidad_de}} del Centro Educativo.
            Denominado: {{$institucion->nombre}} de conformidad con: Resolución Número 0341-2001 de fecha 23-5-2001.

            </p><br /><br /><br /><br />

            <p class="title-body" align="center">DATOS DEL PADRE RESPONSABLE O REPRESENTANTE LEGAL DEL ALUMNO</p>
            <p>
                Nombre completo: {{$responsable->apoderado->primer_nombre.' '.$responsable->apoderado->segundo_nombre.' '.$responsable->apoderado->primer_apellido.' '.$responsable->apoderado->segundo_apellido}} de: 
                {{ \Carbon\Carbon::createFromDate($responsable->apoderado->fecha_nac)->age}} años de edad, de estado civil 
                {{$responsable->apoderado->estado_civil}}, nacionalidad Guatemalteca de profesión u oficio: {{$responsable->apoderado->ocupacion}} me identifico con la cedula de vecindad numero: ________________________________________ o D.P.I. numero: {{$responsable->apoderado->cui}} extendida por el Alcalde Municipal de: {{$responsable->apoderado->municipio->nombre}} del Departamento de: {{$responsable->apoderado->municipio->departamento->nombre}} con residencia en: {{$responsable->apoderado->direccion}} con número de teléfono

                @foreach ($responsable->apoderado->telefonos as $t)
                      <span style="margin-left: 5px;"></span> {{ $t->tipo_telefono == 'C' ? '   Casa: '.$t->telefono : $t->tipo_telefono == 'O' ? '    Oficina: '.$t->telefono : '      Celular: '.$t->telefono }}
                @endforeach

                , actuó en mi calidad de: @if ($responsable->tipo_apoderado === 'P')
                                                    Padre de familia
                                                @elseif ($responsable->tipo_apoderado === 'M')
                                                    Madre de familia
                                                @else
                                                    Representante legal 
                                                @endif,
             Los arriba identificados, aseguramos ser los datos de identificación anotados, estar en libre ejercicio de nuestros derechos civiles y por el presente acto comparecemos a celebrar CONTRATO DE ADHESION POR PRESTACION DE SERVICIOS EDUCATIVOS de conformidad con las siguientes clausulas:

            </p>
            <div style="page-break-before:always;">
                <br /><br /><br /><br />
                <b>PRIMERA: DEL ALUMNO A QUIEN SE LE PROPORCINARA EL SERVICIO EDUCATIVO</b><br /><br />

                Nombre completo: {{$alumno->primer_nombre.' '.$alumno->segundo_nombre.' '.$alumno->tercer_nombre.' '.$alumno->primer_apellido.' '.$alumno->segundo_apellido}} quien cursara el {{ $inscripcion->grado_nivel_educativo->grado->nombre }} grado nivel  
                {{!$inscripcion->grado_nivel_educativo->nivelEducativo->es_carrera ? $inscripcion->grado_nivel_educativo->nivelEducativo->nombre : '_________________________________________'}} 
                carrera: {{$inscripcion->grado_nivel_educativo->nivelEducativo->es_carrera ? $inscripcion->grado_nivel_educativo->nivelEducativo->nombre : '_________________________________________'}} jornada/plan {{$inscripcion->jornada == 'M' ? 'Matutina': 'Vespertina'}} el cual está debidamente autorizado por el Ministerio de Educación de conformidad con la resolución No.____________________________________________________________________ misma que se pone a la vista.<br /><br />

                <b>SEGUNDA: DEL PLAZO DE VIGENCIA</b><br /><br />

                El servicio educativo convenido mediante este Contrato de Adhesión será válido para el presente ciclo lectivo durante el plazo de vigencia no puede ser modificada ninguna de sus cláusulas.<br /><br />

                <b>TERCERA: DERECHOS DEL ALUMNO Y PADRE DE FAMILIA</b><br /><br />

                El Alumno y el Padre de Familia o Representante Legal del mismo como usuarios del servicio educativo contrato, en armonía con el Artículo cuatro (4) de la Ley de Protección al Consumido y Usuario, tendrá derecho a:<br />

                <ol type="a">
                  <li> 
                    La protección a la vida, salud y seguridad en la adquisición consumo y uso de bienes y servicios: Las instalaciones del centro educativo están adecuadas para que los alumnos no corran ninguna clase de riesgo que ponga en peligro su integridad física, así también están dotadas de todos los servicios básicos ofrecidos a los padres de familia. En el caso que la prestación del servicio de transporte sea brindado por una entidad ajena al centro educativo este proporcionara todas las medidas de seguridad necesarias para la debida protección de los alumnos. Asimismo propondrán medidas de seguridad a los padres a través de un seguro escolar el cual no será obligatorio pero debe queda en acta la decisión del padre de la adquisición o la no adquisición de mismo, se debe de velar por la seguridad de los alumnos en cualquier actividad propuesta y hacerlo siempre del conocimiento de los padres para su respectivo consentimiento de la participación.
                  </li>  
                  <li> 
                    La libertad de elección del bien o servicio: Los padres de familia tienen el derecho de poder adquirir tanto los útiles escolares, como los uniformes, transporte, seguro y otros servicios adicionales en el establecimiento comercial que se adecue mejor a su capacidad económica, sin embargo el centro educativo puede facilitar la compra o prestación de servicios, siempre y cuando medie convenio por escrito en el cual lo solicitan los padres de familia, debiendo en tal caso cumplir con las obligaciones tributarias correspondientes.
                  </li>
                  <li> 
                    La libertad de contratación: el padres de familia o representante legal del alumno tiene derecho de libre contratación por lo que para los bienes o servicios que sean necesarios para la educación de su(s) hijo(s) pueden contratar o adquirir los bienes o servicios que más se adecuen con su capacidad económica. Transporte, Seguro Escolar y otros.
                  </li>
                  <li> 
                    La información veraz, suficiente, clara y oportuna sobre los bienes y servicios: El centro educativo se compromete a proporcionar a los padres de familia información completa sobre el servicio contratado y especialmente los horarios de clases, los grados y las carreras autorizadas que imparten, los sistemas de evaluación, los cursos adicionales que imparten, el monto de las cuotas que se cobran tanto de inscripción como de cuota mensual así como de las actividades extraescolares a que se pueden realizar durante el ciclo lectivo.
                    El centro educativo tiene la obligación de cumplir con cada una de las clausulas estipuladas en el presente contrato así como también con todo lo ofrecido a los padres de familia, tanto en la publicidad efectuada en los medios de comunicación, información escrita o cualquier otro documento publicitario.
                  </li>
                </ol>
            </p>
            </div>
        </main>

        <footer>
            
        </footer>
    </body>
</html>