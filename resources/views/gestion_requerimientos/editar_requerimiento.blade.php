@extends('layouts.principal')

@section('style')
    <style>
        label.error {
            color: red;
            font-size: 1rem;
            font-style: italic;
            display: block;
            margin-top: 5px;
        }
    </style>
@endsection

@section('contenido')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Gestión de requerimientos</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('listar_requerimientos') }}">Lista de requerimientos</a>
                            </li>
                            <li class="breadcrumb-item active">Editar requerimiento
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
                            <h4 class="card-title">Editar requerimiento</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-content collapse show">
                                <form id="form_edit_requerimiento" class="form" action="{{ route('editar_requerimientos') }}" method="POST">
                                    @csrf
                                    @method('put')
                                    <input type="hidden" name="id_requerimiento" value="{{ $requerimiento->id_requerimiento }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nombre">Nombre (*)</label>
                                                <input value="{{ $requerimiento->nombre }}" autocomplete="off" type="text" class="form-control border-primary @error('nombre') is-invalid @enderror" name="nombre" id="nombre">
                                                @error('nombre')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="detalle">Detalle (*)</label>
                                                <textarea class="form-control border-primary @error('detalle') is-invalid @enderror" name="detalle" id="detalle" cols="30" rows="3">{{ $requerimiento->detalle }}</textarea>
                                                @error('detalle')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="fecha_finalizacion">Fecha finalización</label>
                                                <input value="{{ $requerimiento->fecha_finalizacion }}" autocomplete="off" type="date" class="form-control border-primary @error('fecha_finalizacion') is-invalid @enderror" name="fecha_finalizacion" id="fecha_finalizacion">
                                                @error('fecha_finalizacion')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="fomr-actions text-center">
                                        <a href="{{ route('listar_requerimientos') }}" class="btn btn-warning mr-1">
                                            <i class="la la-close"></i>
                                            Cancelar
                                        </a>
                                        <button id="guardar_form" type="submit" class="btn btn-primary">
                                            <i class="la la-save"></i>
                                            Guardar
                                        </button>
                                    </div>
                                </form>
                            </div>
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
<script src="{{ asset('moment_js/moment.js') }}"></script>
<script src="{{ asset('sweet_alert2/sweetalert2@11.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#fecha_finalizacion').on("blur", function(){
            let fecha_finalizacion = $('#fecha_finalizacion').val();
            let fecha_actual = 	moment().format('YYYY-MM-DD');
            if(fecha_actual >= fecha_finalizacion){
                Swal.fire({
                    icon: 'error',
                    title: '¡Fecha incorrecta!',
                    text: 'La fecha de finalización debe ser un día mayor a la fecha actual.',
                    footer: '<strong>Nota:</strong>&nbsp<p>Actualiza la fecha y da clic fuera del campo de texto</p>'
                });
                $('#guardar_form').hide("slow");
            }else{
                $('#guardar_form').show("slow");
            }
        });

        /* Método para letras con acentos */
        jQuery.validator.addMethod("letras", function(value, element) {
            return this.optional(element) || /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/.test(value);
        });

        $("#form_edit_requerimiento").validate({

            onfocusin: function(element) { $(element).valid(); },
            onfocusout: function(element) { $(element).valid(); },
            onclick: function(element) { $(element).valid(); },
            onkeyup: function(element) { $(element).valid(); },

            rules: {
                nombre: {
                    required: true,
                    letras: true,
                    minlength: 3,
                    maxlength: 100
                },

                detalle: {
                    required: true,
                    letras: true,
                    minlength: 3,
                    maxlength: 255
                },

                fecha_finalizacion: {
                    required: true
                }
            },
            messages: {
                nombre: {
                    required: "Este campo es obligatorio",
                    letras: "Solo se admiten letras",
                    minlength: "El nombre debe tener minimo 3 caracteres",
                    maxlength: "El nombre puede tener máximo 100 caracteres"
                },

                detalle: {
                    required: "Este campo es obligatorio",
                    letras: "Solo se admiten letras",
                    minlength: "El detalle debe tener minimo 3 caracteres",
                    maxlength: "El detalle puede tener máximo 255 caracteres"
                },

                fecha_finalizacion: {
                    required: "Debe seleccionar una fecha",
                }
            },
        });

        /* función para confirmar */
        $("#guardar_form").click(function(evento){
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
                    $('#form_edit_requerimiento').submit()
                }
            })

        })

    });
    
</script>
@endsection