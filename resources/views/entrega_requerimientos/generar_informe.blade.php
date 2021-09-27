<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Informe ejecución contractual</title>
</head>
<body>
    <div class="header">
        <p class="logo_sena">
            LOGO
        </p>
        <h2 class="linea_2">
            PROCESO GESTIÓN DEL TALENTO HUMANO
        </h2>
        <h2 class="linea_3">
            FORMATO INFORME MENSUAL EJECUCIÓN CONTRACTUAL
        </h2>
    </div>
    <div class="fecha">
        FECHA
    </div>
    <div class="presentacion">
        <p class="p_linea_1">Señor (a)</p>
        <p class="p_linea_2">CLAUDIA MARCELA PORRAS ORTÍZ</p>
        <p class="p_linea_3">SUPERVISOR(A) CONTRATO No.CO1.PCCNTR.2233731</p>
        <p class="p_linea_4">Coordinadora académica</p>
        <p class="p_linea_5">Centro de Servicios y Gestión Empresarial</p>
        <p class="p_linea_6">Ciudad</p>
        <p class="p_linea_7">Informe: {{ $informe->fecha_carga }}</p>
    </div>
    <div class="asunto">
        <p><span>Asunto: </span>Informe mensual de ejecución contractual Mes Abril del año 2021</p>
    </div>
    <div class="referencia">
        <p><span>Referencia: </span>No CO1.PCCNTR.2233731 del año 2021</p>
    </div>
    <div class="parrafo_contratista">
        <span>DONY DARÍO CARDENAS ARRIETA</span>, identificado con la Cédula de ciudadanía No. de72284820, 
        en mi calidad de Contratista del SENA, en Centro de Servicios y Gestión Empresarial, 
        en cumplimiento del Contrato de Prestación de Servicios de la referencia, a continuación, 
        presento el Informe de actividades realizadas en el mes objeto de cobro.
    </div>
    <div class="valor_y_forma_pago">
        <span>Valor y forma de Pago</span>: el valor total la suma de <span>cuarenta millones cuatrocientos 
        treinta mil pesos ($40430000.)</span>. Esta suma será pagada por el SENA de la siguiente 
        manera: en mensualidades máximo de <span>tres millones novecientos mil pesos ($ 3900000) </span>
        y el equivalente por la fracción de mes en caso de ser así.
    </div>
    <div class="plazo">
        <span>Plazo: Será hasta el 18 de diciembre de 2021.</span>
    </div>
    <div class="objeto_contrato">
        <table>
            <thead>
                <tr>
                    <th>Objeto:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>REGULAR: PRESTAR SERVICIOS PERSONALES DE CARÁCTER TEMPORAL PARA PLANEAR 
                    Y ORIENTAR LA FORMACIÓN PROFESIONAL INTEGRAL, QUE PROGRAME EL CENTRO DE FORMACIÓN 
                    EN SUS DIFERENTES NIVELES Y MODALIDADES, ATENDIENDO LAS POLÍTICAS INSTITUCIONALES 
                    Y LA NORMATIVIDAD</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="obligaciones">
        <p><span>Obligaciones Especificas:</span></p>
        <table border="1" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Obligaciones</th>
                    <th>Acciones realizadas</th>
                    <th>Evidencias</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($obligaciones as $obligacion)
                    @php
                        $contador = 0;
                        $respuestas = \App\Http\Controllers\EntregaRequerimientoController::getRespuesta($informe->id_informe, $obligacion->id_obligacion);
                    @endphp
                    @if (count($respuestas) > 0)
                        @php
                            $contador += 1;
                        @endphp
                        <tr>
                            <th>{{ $contador }}</th>
                            <th>{{ $obligacion->detalle }}</th>
                            <th>
                                @foreach ($respuestas as $actividad)
                                    @if (count($respuestas) == 1)
                                        $actividad->respuesta_actividad
                                    @else
                                        <th>$actividad->respuesta_actividad</th>
                                    @endif
                                @endforeach
                            </th>
                            <th>
                                @foreach ($respuestas as $actividad)
                                    @if (count($respuestas) == 1)
                                        $actividad->respuesta_evidencia
                                    @else
                                        <th>$actividad->respuesta_evidencia</th>
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="desplazamiento_1">
        A continuación relaciono los desplazamientos que realicé previo a la presentación de 
        este informe. Una vez finalizado cada desplazamiento presenté al ordenador del gasto el 
        informe en el Formato Informe Legalización Desplazamiento Contratista GTH-F-087, en el que 
        se describieron las actividades desarrolladas y los resultados de cada desplazamiento. 
        Cada informe cuenta con el visto bueno del Supervisor.
    </div>
    <div class="desplazamiento_2">
        Se lista a continuación el soporte de la legalización de los desplazamientos realizados, 
        los cuales forman parte integral del presente informe de ejecución contractual.
    </div>
    <div class="tabla_desplazamientos">
        <table border="1">
            <thead>
                <tr>
                    <th>ITEM</th>
                    <th>No DE LA ORDEN DE VIAJE</th>
                    <th>LUGAR DE DESPLAZAMIENTO</th>
                    <th>FECHAS DE DESPLAZAMIENTOS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>No aplica</td>
                    <td>No aplica</td>
                    <td>No aplica</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>No aplica</td>
                    <td>No aplica</td>
                    <td>No aplica</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="desplazamiento_nota">
        <p><span><span>Nota 1:</span> Por cada desplazamiento que haya realizado el contratista, adjuntará el respectivo informe
            que la soporte. En caso de haber realizado el desplazamiento en fecha posterior a la presentación
            del informe de ejecución contractual, deberá reportarlo en el siguiente informe de ejecución contractual.</span></p>
    </div>
</body>
</html>