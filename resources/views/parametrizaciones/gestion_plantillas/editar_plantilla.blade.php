@extends('layouts.principal')

@section('contenido')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Gestión de plantillas</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('listar_plantillas') }}">Listar plantillas</a>
                            </li>
                            <li class="breadcrumb-item active">Editar plantilla
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body"> 
            <!-- Inicio tabla hoverable -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Editar plantilla</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="form_edit_plantilla" class="form" action="{{ route('editar_plantilla') }}" method="post">
                                @csrf
                                @method('put')
                                <input type="hidden" name="id_plantilla" value="{{ $plantilla->id_plantilla }}">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="nombre">Nombre (*)</label>
                                            <input value="{{ $plantilla->nombre }}" placeholder="Nombre..." type="text" class="@error('nombre') is-invalid @enderror form-control border-primary" name="nombre" id="nombre">
                                            @error('nombre')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="fecha_finalizacion">Fecha finalización (*)</label>
                                            <input value="{{ $plantilla->fecha_finalizacion }}" type="date" class="@error('fecha_finalizacion') is-invalid @enderror form-control border-primary" name="fecha_finalizacion" id="fecha_finalizacion">
                                            @error('fecha_finalizacion')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="descripcion">Descripción (*)</label>
                                            <textarea class="@error('descripcion') is-invalid @enderror form-control border-primary" name="descripcion" id="descripcion" cols="30" rows="6">{{ $plantilla->descripcion }}</textarea>
                                            @error('descripcion')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="ciudad">Ciudad (*)</label>
                                            <input value="{{ $plantilla->ciudad }}" placeholder="Ciudad..." type="text" class="@error('ciudad') is-invalid @enderror form-control border-primary" name="ciudad" id="ciudad">
                                            @error('ciudad')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="id_proceso">Proceso (*)</label>
                                            <select class="@error('id_proceso') is-invalid @enderror form-control border-primary" name="id_proceso" id="id_proceso">
                                                <option value="">Seleccion un proceso</option>
                                                @foreach ($procesos as $proceso)
                                                    <option value="{{ $proceso->id_proceso }}" {{ $proceso->id_proceso == $plantilla->id_proceso ? 'selected' : '' }}>{{ $proceso->nombre }}</option>
                                                @endforeach
                                            </select>
                                            @error('id_proceso')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions text-center">
                                    <button type="submit" class="btn btn-primary btn-block" id="btn_submit">
                                        <i class="la la-save"></i>
                                        Guardar
                                    </button>
                                    <a href="{{ route('listar_plantillas') }}" class="btn btn-warning mr-1 btn-block">
                                        <i class="la la-close"></i>
                                        Cancelar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin tabla hoverable -->
        </div>
    </div>
</div>
@endsection


@section('javascript')

<script src="{{ asset('sweet_alert2/sweetalert2@11.js') }}"></script>

<!-- Inicio de validación/////////////////////////////////////////////////////////////////////////////////////-->
<script>
    $(document).ready(function() {

        /* Metodo para letras con acentos */
        jQuery.validator.addMethod("letras", function(value, element) {
            return this.optional(element) || /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/.test(value);
        });

        $("#form_edit_plantilla").validate({

            onfocusin: function(element) { $(element).valid(); },
            onfocusout: function(element) { $(element).valid(); },
            onclick: function(element) { $(element).valid(); },
            onkeyup: function(element) { $(element).valid(); },

            rules: {
                nombre: {
                    required: true,
                    letras: true,
                    minlength: 3,
                    maxlength: 30
                },

                fecha_finalizacion: {
                    required: true
                },

                descripcion: {
                    required: true,
                    letras: true,
                    minlength: 20,
                    maxlength: 800
                },

                ciudad: {
                    required: true,
                    letras: true,
                    minlength: 3,
                    maxlength: 50
                },

                id_proceso: {
                    required: true
                }
            },
            messages: {
                nombre: {
                    required: "Este campo es obligatorio",
                    letras: "Solo se admiten letras",
                    minlength: "El nombre debe tener minimo 3 caracteres",
                    maxlength: "El nombre puede tener máximo 30 caracteres"
                },

                fecha_finalizacion:{
                    required: "Debe establecer una fecha",
                },

                descripcion: {
                    required: "Este campo es obligatorio",
                    letras: "Solo se admiten letras",
                    minlength: "La descripción debe tener minimo 20 caracteres",
                    maxlength: "La descripción debe tener minimo 800 caracteres"
                },

                ciudad: {
                    required: "Este campo es obligatorio",
                    letras: "Solo se admiten letras",
                    minlength: "La ciudad debe tener minimo 3 caracteres",
                    maxlength: "La ciudad debe tener minimo 50 caracteres"
                },

                id_proceso: {
                    required: "Debe seleccionar un proceso",
                }
            }
        });

        /* función para confirmar */
        $("#btn_submit").click(function(evento){
            evento.preventDefault()
            
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
                    $('#form_edit_plantilla').submit()
                }
            })

        })

});
</script>
<!-- Fin de validación/////////////////////////////////////////////////////////////////////////////////////-->
@endsection