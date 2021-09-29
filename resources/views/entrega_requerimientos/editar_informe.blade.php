@extends('layouts.principal')

@section('style')
    <style>
        .error{
            color: red;
            font-style: italic;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/css/plugins/forms/wizard.css') }}">
@endsection

@section('contenido')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Entrega de requerimientos - Informe Ejecución Contractual</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('listar_ent_requerimientos') }}">Listar requerimientos</a>
                            </li>
                            <li class="breadcrumb-item active">Editar informe de ejecución contractual
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body"> 
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Editar informe de ejecución contractual</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-content collapse show">
                                <form id="form_editar_informe" action="{{ route('actualizar_informe') }}" class="number-tab-steps wizard-circle" method="POST" >
                                    @csrf
                                    @method('put')
                                    <input type="hidden" name="id_informe" id="id_informe" value="{{ $informe->id_informe }}">
                                    @foreach ($obligaciones as $obligacion)
                                        @php
                                            $preguntas = \App\Http\Controllers\EntregaRequerimientoController::preguntas_informe($obligacion->id_obligacion, $requerimiento->id_formulario);
                                        @endphp
                                        @if (count($preguntas) > 0)
                                            <h6></h6>
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group d-flex justify-content-center">
                                                            <h3>{{ $obligacion->detalle }}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                @foreach ($preguntas as $pregunta)
                                                    <input type="hidden" value="{{ $obligacion->id_obligacion }}" name="obligaciones[]">
                                                    <input type="hidden" value="{{ $pregunta['id_pregunta'] }}" name="preguntas[]">
                                                    @php
                                                        $respuesta = \App\Http\Controllers\EntregaRequerimientoController::buscar_respuesta($pregunta['id_pregunta'], $informe->id_informe);
                                                    @endphp
                                                    <input type="hidden" value="{{ $respuesta != null ? $respuesta->id_actividad_evidencia : '' }}" name="id_respuestas[]">
                                                    <div class="card">
                                                        <div class="card-body border border-primary">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>{{ $pregunta["pregunta_actividad"] }}</label>
                                                                        <input type="text" value="{{ $respuesta != null ? $respuesta->respuesta_actividad : '' }}" class="form-control border-primary" name="actividades[]">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>{{ $pregunta["pregunta_evidencia"] }}</label>
                                                                        <input type="text" value="{{ $respuesta != null ? $respuesta->respuesta_evidencia : '' }}" class="form-control border-primary" name="evidencias[]">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </fieldset>
                                        @endif
                                    @endforeach
                                    <h6></h6>
                                    <fieldset>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group d-flex justify-content-center">
                                                    <h3>Desplazamientos</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header">
                                                <h2>Agrege los desplazamientos que realizo durante este mes</h2>
                                            </div>
                                            <div class="card-body border border-primary">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Numero de orden</label>
                                                            <input type="text" class="form-control border-primary" id="numero_orden">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Lugar</label>
                                                            <input type="text" class="form-control border-primary" id="lugar">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Fecha inicio</label>
                                                            <input type="date" class="form-control border-primary" id="fecha_inicio">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Fecha fin</label>
                                                            <input type="date" class="form-control border-primary" id="fecha_fin">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button type="button" id="añadir_desplazamiento" class="btn btn-versatile_reports col-md-12">Añadir</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-body border border-primary">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-hovered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Numero orden</th>
                                                                        <th>Lugar</th>
                                                                        <th>Fecha inicio</th>
                                                                        <th>Fecha fin</th>
                                                                        <th>Opción</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="caja_desplazamientos">
                                                                    @if (count($desplazamientos) > 0)
                                                                        @php
                                                                            $contador = 0;
                                                                        @endphp
                                                                        @foreach ($desplazamientos as $value)
                                                                            @php
                                                                                $contador += 1;
                                                                            @endphp
                                                                            <tr id="tr-{{ $contador }}">
                                                                                <input type="hidden" name="numeros_orden[]" value="{{ $value->numero_orden }}">
                                                                                <input type="hidden" name="lugares[]" value="{{ $value->lugar }}">
                                                                                <input type="hidden" name="fechas_inicio[]" value="{{ $value->fecha_inicio }}">
                                                                                <input type="hidden" name="fechas_fin[]" value="{{ $value->fecha_inicio }}">
                                                                                <td>{{ $value->numero_orden }}</td>
                                                                                <td>{{ $value->lugar }}</td>
                                                                                <td>{{ $value->fecha_inicio }}</td>
                                                                                <td>{{ $value->fecha_inicio }}</td>
                                                                                <td>
                                                                                    <button type="button" onclick="eliminarDesplazamiento({{ $contador }})" class="btn btn-danger">X</button>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="{{ asset('sweet_alert2/sweetalert2@11.js') }}"></script>
<script src="{{ asset('dashboard/app-assets/vendors/js/extensions/jquery.steps.min.js') }}"></script>

<script>
    var form = $("#form_editar_informe").show();
    $("#form_editar_informe").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            previous: "Anterior",
            next: "Siguiente",
            finish: 'Finalizar'
        },
        onStepChanging: function (event, currentIndex, newIndex) {
            if (currentIndex > newIndex) {
                return true;
            }

            if (currentIndex < newIndex) {
                //Remover estilos cuando se complete un paso del formulario
                form.find(".body:eq(" + newIndex + ") label.error").remove();
                form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
            }
            return form;
        },
        onFinishing: function (event, currentIndex) {
            return form;
        },
        onFinished: function (event, currentIndex) {
            $("#form_editar_informe").submit()
        }
    });

    let contadorDesplazamientos = {{ count($desplazamientos) }};
    $("#añadir_desplazamiento").on("click", function(){
        let numero_orden = $("#numero_orden").val();
        let lugar = $("#lugar").val();
        let fecha_inicio = $("#fecha_inicio").val();
        let fecha_fin = $("#fecha_fin").val();

        contadorDesplazamientos += 1;
        $("#caja_desplazamientos").append(`
            <tr id="tr-${contadorDesplazamientos}">
                <input type="hidden" name="numeros_orden[]" value="${numero_orden}">
                <input type="hidden" name="lugares[]" value="${lugar}">
                <input type="hidden" name="fechas_inicio[]" value="${fecha_inicio}">
                <input type="hidden" name="fechas_fin[]" value="${fecha_fin}">
                <td>${numero_orden}</td>
                <td>${lugar}</td>
                <td>${fecha_inicio}</td>
                <td>${fecha_fin}</td>
                <td>
                    <button type="button" onclick="eliminarDesplazamiento(${contadorDesplazamientos})" class="btn btn-danger">X</button>
                </td>
            </tr>
        `);
        $("#numero_orden").val('');
        $("#lugar").val('');
        $("#fecha_inicio").val('');
        $("#fecha_fin").val('');
    });

    function eliminarDesplazamiento(id){
        $("#tr-"+id).remove()
    }
</script>
@endsection