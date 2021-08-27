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
                            <li class="breadcrumb-item active">Informe de ejecución contractual
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
                            <h4 class="card-title">Informe de ejecución contractual</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-content collapse show">
                                <form id="form_insert_report" action="{{ route('guardar_informe') }}" class="number-tab-steps wizard-circle" method="POST" >
                                    @csrf
                                    <input type="hidden" name="id_requerimiento" id="id_requerimiento" value="{{ $requerimiento->id_requerimiento }}">
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
                                                    <div class="card">
                                                        <div class="card-body border border-primary">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>{{ $pregunta["pregunta_actividad"] }}</label>
                                                                        <input type="text" class="form-control border-primary" name="actividades[]">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>{{ $pregunta["pregunta_evidencia"] }}</label>
                                                                        <input type="text" class="form-control border-primary" name="evidencias[]">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </fieldset>
                                        @endif
                                    @endforeach
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
    var form = $("#form_insert_report").show();
    $("#form_insert_report").steps({
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
            $("#form_insert_report").submit()
        }
    });

    // Initialize validation
    // $("#form_insert_report").validate({
    //     ignore: 'input[type=hidden]', // ignore hidden fields
    //     errorClass: 'danger',
    //     successClass: 'success',
    //     highlight: function (element, errorClass) {
    //         $(element).removeClass(errorClass);
    //     },
    //     unhighlight: function (element, errorClass) {
    //         $(element).removeClass(errorClass);
    //     },
    //     errorPlacement: function (error, element) {
    //         error.insertAfter(element);
    //     },
    //     rules: {

    //     },
    //     messages : {
            
    //     }
    // });

</script>
@endsection