@extends('layouts.principal')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/css/plugins/forms/wizard.css') }}">
@endsection

@section('contenido')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Gestión de contratistas - Gestión de contratos</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('listar_contratistas') }}">Listar contratistas</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('listar_contratos', ['id' => $persona->id_persona]) }}">Listar contratos</a>
                            </li>
                            <li class="breadcrumb-item active">Editar contrato
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
                            <h4 class="card-title">Editar contrato</h4>
                            @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    @foreach ($errors->all() as $item)
                                        {{$item}}
                                    @endforeach
                                </div>
                            @endif
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="card-content collapse show">
                                <form id="form_edit_contrato" action="{{ route('editar_contrato') }}" class="number-tab-steps wizard-circle" method="POST" >
                                    @csrf
                                    @method('put')
                                    <input type="hidden" name="id_contrato" value="{{ $contrato->id_contrato }}">
                                    <!-- Paso 1 -->
                                    <h6>Paso 1</h6>
                                    <fieldset>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="numero_contrato">Numero contrato: (*)</label>
                                                    <input type="text" value="{{ $contrato->numero_contrato }}" autocomplete="off" class="form-control @error('numero_contrato') is-invalid @enderror" name="numero_contrato" id="numero_contrato">
                                                    @error('numero_contrato')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="fecha_inicio">Fecha inicio: (*)</label>
                                                    <input type="date" value="{{ date("Y-m-d", strtotime($contrato->fecha_inicio)) }}" class="form-control @error('fecha_inicio') is-invalid @enderror" name="fecha_inicio" id="fecha_inicio">
                                                    @error('fecha_inicio')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="fecha_fin">Fecha fin: (*)</label>
                                                    <input type="date" value="{{ date("Y-m-d", strtotime($contrato->fecha_fin)) }}" class="form-control @error('fecha_fin') is-invalid @enderror" name="fecha_fin" id="fecha_fin">
                                                    @error('fecha_fin')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <!-- Paso 2 -->
                                    <h6>Paso 2</h6>
                                    <fieldset>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="valor_contrato">Valor del contrato: (*)</label>
                                                    <input type="text" value="{{ $contrato->valor }}" autocomplete="off" class="form-control @error('valor_contrato') is-invalid @enderror" name="valor_contrato" id="valor_contrato">
                                                    @error('valor_contrato')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="forma_pago_contrato">Forma de pago del contrato: (*)</label>
                                                    <textarea name="forma_pago_contrato" id="forma_pago_contrato" cols="30" rows="5" class="form-control @error('forma_pago_contrato') is-invalid @enderror">{{ $contrato->forma_pago }}</textarea>
                                                    @error('forma_pago_contrato')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Lugar contrato / Departamento / Municipio (*)</label>
                                                    <div class="d-flex justify-content-between">
                                                        <select id="id_departamento" class="form-control">
                                                            <option value="">Seleccion un departamento</option>
                                                            @foreach ($departamentos as $item)
                                                                <option value="{{ $item->id_departamento }}" {{ $contrato->id_departamento == $item->id_departamento ? 'selected' : '' }}>{{ $item->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                        <select name="id_municipio" id="id_municipio" class="@error('id_municipio') is-invalid @enderror form-control">
                                                            @foreach ($municipios as $item)
                                                                <option value="{{ $item->id_municipio }}" {{ $contrato->id_municipio == $item->id_municipio ? 'selected' : '' }}>{{ $item->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_municipio')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                    </fieldset>

                                    <!-- Paso 3 -->
                                    <h6>Paso 3</h6>
                                    <fieldset>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="id_objeto">Objeto de contrato:</label>
                                                    <select name="id_objeto" id="id_objeto" class="form-control @error('id_objeto') is-invalid @enderror">
                                                        <option value="">Seleccion un objeto de contrato</option>
                                                        @foreach ($objetos as $item)
                                                            <option value="{{ $item->id_objeto }}" {{ $item->id_objeto == $contrato->id_objeto ? 'selected' : '' }}>{{ $item->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_objeto')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="id_supervisor">Supervisor a cargo:</label>
                                                    <select name="id_supervisor" id="id_supervisor" class="form-control @error('id_supervisor') is-invalid @enderror">
                                                        <option value="">Seleccione</option>
                                                        @foreach ($supervisores as $item)
                                                            <option value="{{ $item->id_supervisor }}" {{ $item->id_supervisor == $contrato->id_supervisor ? 'selected' : '' }}>{{ $item->nombre . ' ' . $item->primer_apellido }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_supervisor')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="id_proceso">Proceso:</label>
                                                    <select name="id_proceso" id="id_proceso" class="form-control @error('id_proceso') is-invalid @enderror">
                                                        <option value="">Seleccione</option>
                                                        @foreach ($procesos as $item)
                                                            <option value="{{ $item->id_proceso }}" {{ $item->id_proceso == $contrato->id_proceso ? 'selected' : '' }}>{{ $item->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_proceso')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <!-- Paso 4 -->
                                    <h6>Paso 4</h6>
                                    <fieldset>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="id_centro">Centro SENA:</label>
                                                    <select name="id_centro" id="id_centro" class="form-control @error('id_centro') is-invalid @enderror">
                                                        <option value="">Seleccione</option>
                                                        @foreach ($centros as $item)
                                                            <option value="{{ $item->id_centro }}" {{ $item->id_centro == $contrato->id_centro ? 'selected' : '' }}>{{ $item->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_centro')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table style="width: 100%;" class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="table table-column">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td><strong>Nombres: </strong><td>{{ $persona->nombre }}.</td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><strong>Apellidos: </strong><td>{{ $persona->primer_apellido . ' ' . $persona->segundo_apellido }}.</td></td>
                                                                            </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
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
<script>
$(document).ready(function(){
    $('#id_departamento').on('change', function(){
        let id_departamento = $(this).val();
        if($.trim(id_departamento) != null){
            $.get('/municipios', {id_departamento: id_departamento}, function(municipios){
                $('#id_municipio').empty();
                $('#id_municipio').append("<option value=''>Seleccione el municipio</option>");
                $.each(municipios, function (id, nombre){
                    $('#id_municipio').append("<option value='"+ id +"'>"+ nombre +"</option>")
                })
            })
        }
    })
});
</script>

<script src="{{ asset('sweet_alert2/sweetalert2@11.js') }}"></script>
<script src="{{ asset('dashboard/app-assets/vendors/js/extensions/jquery.steps.min.js') }}"></script>

<script>
    var form = $("#form_edit_contrato").show();
    $("#form_edit_contrato").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            previous: "Anterior",
            next: "Siguiente",
            finish: 'Actualizar contrato'
        },
        onStepChanging: function (event, currentIndex, newIndex) {
            if (currentIndex > newIndex) {
                return true;
            }

            // if (newIndex === 1) {
            //     if($("#fecha_inicio").val() >= $("#fecha_fin").val()) {
            //         Swal.fire({
            //             icon: 'error',
            //             title: '¡Fecha incorrecta!',
            //             text: 'La fecha de inicio debe ser mayor a la fecha de fin.'
            //         })
            //         return false;
            //     }
            // }

            if (currentIndex < newIndex) {
                //Remover estilos cuando se complete un paso del formulario
                form.find(".body:eq(" + newIndex + ") label.error").remove();
                form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
            }

            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();

        },

        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },

        onFinished: function (event, currentIndex) {
            Swal.fire({
                title: '¿Estás seguro de guardar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form_edit_contrato').submit()
                }
            })
        }
    });

    $.validator.addMethod("minDate", function(value, element) {
            let fecha_inicio = $('#fecha_inicio').val();
            let fecha_fin = $('#fecha_fin').val();
            if (fecha_inicio < fecha_fin)
                return true;
            return false;
        });

    //Initialize validation
    $("#form_edit_contrato").validate({
        // ignore: 'input[type=hidden]', // ignore hidden fields
        // errorClass: 'danger',
        // successClass: 'success',
        // highlight: function (element, errorClass) {
        //     $(element).removeClass(errorClass);
        // },
        // unhighlight: function (element, errorClass) {
        //     $(element).removeClass(errorClass);
        // },
        // errorPlacement: function (error, element) {
        //     error.insertAfter(element);
        // },
        rules: {
            numero_contrato : {
                required: true,
                maxlength: 30
            },
            fecha_inicio : {
                required: true,
                date: true
            },
            fecha_fin : {
                required: true,
                date: true,
                minDate: true
            },
            valor_contrato : {
                required: true,
                number: true
            },
            forma_pago_contrato : {
                required: true,
                maxlength: 2000
            },
            estado_contrato : {
                required: true
            },
            id_proceso : {
                required: true
            },
            id_objeto : {
                required: true
            },
            id_supervisor : {
                required: true
            },
            id_centro : {
                required: true
            },
            id_municipio: {
                required: true
            }
        },
        messages : {
            numero_contrato : {
                required: "Este campo es obligatorio",
                maxlength: "Número de contrato puede tener máximo 30 caracteres"
            },
            fecha_inicio : {
                required: "Este campo es obligatorio",
                date: "El dato ingresado debe ser una fecha"
            },
            fecha_fin : {
                required: "Este campo es obligatorio",
                date: "El dato ingresado debe ser una fecha",
                minDate: "La fecha final debe ser mayor a la fecha de inicio"
            },
            valor_contrato : {
                required: "Este campo es obligatorio",
                number: "El valor debe ser un número"
            },
            forma_pago_contrato : {
                required: "Este campo es obligatorio",
                maxlength: "Forma de pago no debe superar los 2000 caracteres"
            },
            estado_contrato : {
                required: "Este campo es obligatorio"
            },
            id_proceso : {
                required: "Este campo es obligatorio"
            },
            id_objeto : {
                required: "Este campo es obligatorio"
            },
            id_supervisor : {
                required: "Este campo es obligatorio"
            },
            id_centro : {
                required: "Este campo es obligatorio"
            },
            id_municipio: {
                required: "Este campo es obligatorio"
            }
        }
    });

</script>
@endsection