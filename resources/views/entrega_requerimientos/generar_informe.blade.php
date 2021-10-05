<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Informe ejecución contractual</title>
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/informe.css') }}">
</head>
<body>
    <header>
        <img src="{{ asset('dashboard/assets/img/logoSena.jpeg') }}" class="img" alt="logo sena">
    </header>
    <footer>
        GTH-F-062 V08
    </footer>
    <div>
        <h2 class="text-center bold size-16">
            PROCESO GESTIÓN DEL TALENTO HUMANO
        </h2>
        <h2 class="text-center bold size-16">
            FORMATO INFORME MENSUAL EJECUCIÓN CONTRACTUAL
        </h2>
    </div>
    <div class="text-right mr-100 mt-25 size-14">
        Medellín, {{ $fecha_generacion }}
    </div>
    <div class="text-left size-14">
        <p>
            Señor (a)<br>
            <span class="bold">{{ $informacion->nombre_supervisor . ' ' . $informacion->primer_apellido_supervisor . ' ' . $informacion->segundo_apellido_supervisor }}</span><br>
            SUPERVISOR(A) CONTRATO No.{{ $informacion->numero_contrato }}<br>
            Coordinadora académica<br>
            {{ $informacion->nombre_centro }}<br>
            Ciudad
        </p>
        <div class="text-right mt-asunto">
            <p><span class="bold">Asunto: </span>Informe mensual de ejecución contractual<br><span class="mr-118">Mes Septiembre del año 2021</span></p>
        </div>
    </div>
    <div class="mt-asunto mb-25 size-14">
        <p><span class="bold">Referencia: </span>No {{ $informacion->numero_contrato }} del año 2021</p>
    </div>
    <div class="mb-25 size-14">
        <span class="bold">{{ $informacion->nombre_contratista . ' ' . $informacion->primer_apellido_contratista . ' ' . $informacion->segundo_apellido_contratista }}</span>, 
        identificado con la Cédula de ciudadanía No. de{{ $informacion->documento_contratista }}, 
        en mi calidad de Contratista del SENA, en Centro de Servicios y Gestión Empresarial, 
        en cumplimiento del Contrato de Prestación de Servicios de la referencia, a continuación, 
        presento el Informe de actividades realizadas en el mes objeto de cobro.
    </div>
    <div class="mb-20 size-14">
        <span class="bold">Valor y forma de Pago</span>: {{ $informacion->forma_pago_contrato }}
    </div>
    <div class="mb-25 size-14">
        <span><span class="bold">Plazo:</span> Será hasta el 18 de diciembre de 2021.</span>
    </div>
    <div class="mb-25 size-14 mt-objeto">
        <table class="w100 collapsed" border="1">
            <thead>
                <tr>
                    <th class="text-left">OBJETO:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $informacion->nombre_objeto . ': ' . $informacion->detalle_objeto }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="mb-25 size-14">
        <table class="w100 collapsed" border="1">
            <thead class="table-border-bottom">
                <tr>
                    <th class="bold" colspan="4" align="left">Obligaciones Especificas:</th>
                </tr>
                <tr>
                    <th>No</th>
                    <th>Obligaciones</th>
                    <th>Acciones realizadas</th>
                    <th>Evidencias</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $contador = 0;
                @endphp
                @foreach ($obligaciones as $obligacion)
                    @php
                        $respuestas = \App\Http\Controllers\EntregaRequerimientoController::getRespuesta($informe->id_informe, $obligacion->id_obligacion);
                    @endphp
                    @if (count($respuestas) > 0)
                        @php
                            $contador += 1;
                        @endphp
                        <tr>
                            <th class="text-lighter">{{ $contador }}</th>
                            <th class="text-lighter">{{ $obligacion->detalle }}</th>
                            <th class="text-lighter">
                                @foreach ($respuestas as $actividad)
                                    <p>{{ $actividad->respuesta_actividad }}</p>
                                @endforeach
                            </th>
                            <th class="text-lighter">
                                @foreach ($respuestas as $evidencia)
                                    <p>{{ $evidencia->respuesta_evidencia }}</p>
                                @endforeach
                            </th>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mb-25 size-14">
        A continuación relaciono los desplazamientos que realicé previo a la presentación de 
        este informe. Una vez finalizado cada desplazamiento presenté al ordenador del gasto el 
        informe en el Formato Informe Legalización Desplazamiento Contratista GTH-F-087, en el que 
        se describieron las actividades desarrolladas y los resultados de cada desplazamiento. 
        Cada informe cuenta con el visto bueno del Supervisor.
    </div>
    <div class="mb-25 size-14">
        Se lista a continuación el soporte de la legalización de los desplazamientos realizados, 
        los cuales forman parte integral del presente informe de ejecución contractual.
    </div>
    <div class="mb-25 size-14">
        <table class="w100 collapsed" border="1">
            <thead>
                <tr>
                    <th>ITEM</th>
                    <th>No DE LA ORDEN DE VIAJE</th>
                    <th>LUGAR DE DESPLAZAMIENTO</th>
                    <th>FECHAS DE DESPLAZAMIENTOS</th>
                </tr>
            </thead>
            <tbody>
                @if (count($desplazamientos) > 0)
                    @php
                        $contador = 0;
                    @endphp
                    @foreach ($desplazamientos as $desplazamiento)
                        @php
                            $contador += 1;
                        @endphp
                        <tr>
                            <td>{{ $contador }}</td>
                            <td class="text-center">{{ $desplazamiento->numero_orden }}</td>
                            <td class="text-center">{{ $desplazamiento->lugar }}</td>
                            <td class="text-center">{{ date('d-m-Y', strtotime($desplazamiento->fecha_inicio)) . '  /  ' . date('d-m-Y', strtotime($desplazamiento->fecha_fin)) }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="mb-25 size-14">
        <p><span class="italic"><span class="bold">Nota 1:</span> Por cada desplazamiento que haya realizado el contratista, adjuntará el respectivo informe
            que la soporte. En caso de haber realizado el desplazamiento en fecha posterior a la presentación
            del informe de ejecución contractual, deberá reportarlo en el siguiente informe de ejecución contractual.</span></p>
    </div>
    <div class="page-break"></div>
    <div class="mb-25 size-14">
        Para el trámite de la cuenta me permito adjuntar: Documentos electrónicos enunciados como evidencias 
        del cumplimiento de las obligaciones contractuales y los desplazamientos realizados y el número de 
        planilla 49620091 pagada por el operador MI PLANILLA periodo de marzo de 2021. (Decreto Ley 2106 de 
        2019 – “Decreto Ley Antitrámites”)
    </div>
    <div class="mb-25 size-14">
        Evidencias en ( ) folios
    </div>
    <div class="mb-25 size-14">
        Cordialmente,
    </div>
    <div class="mt-25 mb-25 bold size-14">
        <p>________________________________________</p>
        <p>{{ $informacion->nombre_contratista . ' ' . $informacion->primer_apellido_contratista . ' ' . $informacion->segundo_apellido_contratista }}</p>
        <p>Contratista</p>
        <p>{{ $informacion->tipo_documento_contratista }}. No. {{ $informacion->documento_contratista }}</p>
    </div>
    <div class="mb-25 size-14">
        Recibí a satisfacción:
    </div>
    <div class="mb-25 bold size-14">
        <p>{{ $informacion->nombre_supervisor . ' ' . $informacion->primer_apellido_supervisor . ' ' . $informacion->segundo_apellido_supervisor }}</p>
        <p>Supervisor(a) Contrato No.{{ $informacion->numero_contrato }} de 2021</p>
        <p>Coordinadora académica</p>
    </div>
</body>
</html>