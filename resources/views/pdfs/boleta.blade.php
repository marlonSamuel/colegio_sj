<html>
    <head>
        <style>
        	 @page {
           		 margin: 0cm 0cm;
           		 font-weight: bold;
           		 text-transform: uppercase;
	       	 }

	        /** Define now the real margins of every page in the PDF **/
	        body {
	            margin-top: 2cm;
	            margin-left: 2cm;
	            margin-right: 2cm;
	            margin-bottom: 2cm;
	            font-size: 12px;
	        }

	        /** Define the header rules **/
	        header {
	            position: fixed;
	            top: 1cm;
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
	            font-size: 12px;
	            font-weight: bold;
	        }

	        .text-body {
	            font-size: 12px;
	            font-weight: normal;
	            text-align: justify;
	        }

	        #table_header {
	        	width: 100%;
	        }

	        #table_body {
	        	border-collapse: collapse;
          		width: 100%;
	        }

	        #table_body, th, td {
	          border: 1px solid black;
	          text-align: left;
	          padding-top: 8px;
	          padding-bottom: 8px;
	          padding-left: 3px;
	        }

	        .rotate {

				  transform: rotate(-30deg);
				  -webkit-transform: rotate(-30deg);
				  -moz-transform: rotate(-30deg);
				  -ms-transform: rotate(-30deg);
				  -o-transform: rotate(-30deg);
				  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);

				}

			.page_break { page-break-before: always; }

        </style>
    </head>

    <body>

    	@foreach($data as $item)
        <header>
			<p style="text-align: center;">
				COLEGIO DE PREUNIVERSITARIO SAN PABLO <br />
				CICLO ESCOLAR {{strtoupper($ciclo->ciclo)}} <br />
				REPORTE DE CALIFICACIONES BIMENSUALES <br />
				GRADO: {{strtoupper($grado->grado->nombre.' '.$grado->nivelEducativo->nombre)}}
			</p>
        </header>
        <main style="margin-top: 70px;">
        	<table id="table_header">
        		<tbody>
        			<tr>
        				<td>
        					<div style="padding: 10px;">
	        					<p>
	        						<p style="text-align: center;">
	        							<span style="margin-right: 90px;">{{$item->primer_nombre.' '.$item->segundo_nombre}}</span>
	        							<span style="margin-right: 90px;">{{$item->primer_apellido}}</span>
	        							<span style="margin-right: 40px;">{{$item->segundo_apellido}}</span>
	        						</p>

	        						ALUMNO: ___________________________________________________________________________________________
	        					</p>
	        					<p style="text-align: center; font-size: 7px; margin-top: -20px;"> 
	        						<span style="margin-right: 50px;">NOMBRE(S)</span>
	        						<span style="margin-left: 80px;">PRIMER APELLIDO </span>
	        						<span style="margin-left: 80px;">SEGUNDO APELLIDO</span>
	        					</p>
        					</div>
        				</td>
        			</tr>
        		</tbody>
        	</table><br />

        	<table id="table_body" style="font-size: 10px;">
        		<thead>
    				<tr>
    					<th colspan="2"></th>
    					<th colspan="5">
    						<p style="text-align: center;">CALIFICACIONES</p>
    					</th>
    				</tr>
    				<tr>
    					<th style="width: 5%">
    						<p class="rotate">No.</p>
    					</th>

    					<th style="width: 30%">
    						<p class="rotate"  style="text-align: center">AREAS</p> 
    					</th>

    					<th style="width: 13%">
    						<p class="rotate"  style="text-align: center">1er. BIMESTRE</p>
    					</th>

    					<th style="width: 13%">
    						<p class="rotate"  style="text-align: center">2do. BIMESTRE</p>
    					</th>

    					<th style="width: 13%">
    						<p class="rotate"  style="text-align: center">3er. BIMESTRE</p>
    					</th>

    					<th style="width: 13%">
    						<p class="rotate"  style="text-align: center">4to. BIMESTRE</p>
    					</th>

    					<th style="width: 12%">
    						<p class="rotate"  style="text-align: center">PROMEDIO</p>
    					</th>
    				</tr>
        		</thead>
        		<tbody>
        			@foreach($item->notas as $nota)
	        			<tr>
	        				<td style="text-align: center;">{{$nota['no']}}</td>
	        				<td>{{$nota['curso']}}</td>
	        				<td style="text-align: center;">{{$nota['primer_bimestre']}}</td>
	        				<td style="text-align: center;">{{$nota['segundo_bimestre']}}</td>
	        				<td style="text-align: center;" >{{$nota['tercer_bimestre']}}</td>
	        				<td style="text-align: center;">{{$nota['cuarto_bimestre']}}</td>
	        				<td style="text-align: center;">{{$nota['promedio']}}</td>
	        			</tr>
        			@endforeach
        			<tr>
        				<td style="background: black;"></td>
        				<td style="text-align: center;">PROMEDIO</td>
        				<td style="text-align: center;">{{$item->primer_bimestre}}</td>
        				<td style="text-align: center;">{{$item->segundo_bimestre}}</td>
        				<td style="text-align: center;">{{$item->tercer_bimestre}}</td>
        				<td style="text-align: center;">{{$item->cuarto_bimestre}}</td>
        				<td style="background: black;"></td>
        			</tr>
        		</tbody>
        	</table>

        	<br />
        	<p>
        		<p style="margin-left: 80px; padding-bottom: -38px;">25/03/1995</p>
        		FECHA: ________________________________
        	</p>

        	<br />
        	<br />
        	<br />
        	<p style="text-align: center;">RESULTADO</p>
        	<br />
        	<br />
        	<table style="width: 100%">
        		<tr>
        			<th style="width: 25%; border: none;">SATISFACTORIO</th>
        			<th style="width: 25%"></th>
        			<th style="width: 25%; border: none; text-align: center;">INSATISFACTORIO</th>
        			<th style="width: 25%"></th>
        		</tr>
        	</table>
        	<br />
        	<p>
        		OBSERVACIONES: ____________________________________________________________________________________________<br />
        		______________________________________________________________________________________________________________<br />
        		______________________________________________________________________________________________________________
        	</p>
        	<br />
        	<table style="width: 100%;">
        		<tbody>
        			<tr>
        				<td style="width: 40%; border: none;">
        					<P style="padding-bottom: -17px;">(F)</P>
        					____________________________________________
        					<p style="text-align: center; font-size: 9px; margin-top: 0px;"> 
        						<span>MAESTRA GUIA</span>
        					</p>
        				</td>
        				<td style="border: none; width: 30%;"></td>
        				<td style="border: none;">
        					<P style="padding-bottom: -17px;">Vo.Bo.</P>
        					___________________________________________
        					<p style="text-align: center; font-size: 9px; margin-top: 0px;"> 
        						<span>DIRECTOR</span>
        					</p>
        				</td>
        			</tr>
        		</tbody>
        	</table>
        </main>

    @if (count($data) > 1 && ($loop->index+1) < count($data))
        <div class="page_break"></div>
    @endif
    @endforeach
    </body>
</html>