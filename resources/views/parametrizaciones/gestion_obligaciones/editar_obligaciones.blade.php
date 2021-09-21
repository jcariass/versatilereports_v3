@extends('layouts.principal')

@section('contenido')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Gestión de obligaciones</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('listar_obligaciones') }}">Listar obligaciones</a>
                            </li>
                            <li class="breadcrumb-item active">Editar obligación
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
                            <h4 class="card-title">Editar obligación</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-content collapse show">
                                <form id="form_edit_obligacion" class="form" action="{{ route('editar_obligaciones') }}" method="post">
                                    <input type="hidden" name="id_obligacion" id="id_obligacion" value="{{ $obligacion->id_obligacion }}">
                                    @csrf
                                    @method('put')
                                    <div class="row justify-content-md-center">
                                        <div class="col-md-6">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label for="detalle">Detalle obligación (*)</label>
                                                    <input value="{{ $obligacion->detalle }}" autocomplete="off" type="text" class="form-control border-primary @error('detalle') is-invalid @enderror" name="detalle" id="detalle">
                                                    @error('detalle')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="id_proceso">Proceso (*)</label>
                                                    <select class="form-control border-primary @error('id_proceso') is-invalid @enderror" name="id_proceso" id="id_proceso">
                                                        <option value="">Seleccion el proceso</option>
                                                        @foreach ($procesos as $item)
                                                            <option {{ $item->id_proceso == $obligacion->id_proceso ? 'selected' : '' }} value="{{ $item->id_proceso }}">{{ $item->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_proceso')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="fecha_vencimiento">Fecha vencimiento (*)</label>
                                                    <input value="{{ date('Y-m-d', strtotime($obligacion->fecha_vencimiento)) }}" type="date" id="fecha_vencimiento" name="fecha_vencimiento" class="form-control border-primary @error('fecha_vencimiento') is-invalid @enderror">
                                                    @error('fecha_vencimiento')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="fomr-actions text-center">
                                        <a href="{{ route('listar_obligaciones') }}" class="btn btn-warning mr-1">
                                            <i class="la la-close"></i>
                                            Cancelar
                                        </a>
                                        <button type="submit" id="guardar_form" class="btn btn-primary">
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

        /* función para confirmar fecha */
        $('#fecha_vencimiento').on("blur", function(){
            let fecha_finalizacion = $('#fecha_vencimiento').val();
            let fecha_actual = 	moment().format('YYYY-MM-DD');
            if(fecha_actual >= fecha_finalizacion){
                Swal.fire({
                    icon: 'error',
                    title: '¡Fecha incorrecta!',
                    text: 'La fecha de vencimiento debe ser un día mayor a la fecha actual.',
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

        $("#form_edit_obligacion").validate({

            onfocusin: function(element) { $(element).valid(); },
            onfocusout: function(element) { $(element).valid(); },
            onclick: function(element) { $(element).valid(); },
            onkeyup: function(element) { $(element).valid(); },

            rules: {
                detalle: {
                    required: true,
                    letras: true,
                    minlength: 3,
                    maxlength: 500
                },

                id_proceso: {
                    required: true
                },

                fecha_vencimiento: {
                    required: true
                }
            },
            messages: {
                detalle: {
                    required: "Este campo es obligatorio",
                    letras: "Solo se admiten letras",
                    minlength: "El detalle debe tener minimo 3 caracteres",
                    maxlength: "El detalle puede tener máximo 500 caracteres"
                },

                id_proceso: {
                    required: "Debe seleccionar un proceso",
                },

                fecha_vencimiento:{
                    required: "Debe establecer una fecha",
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
                    $('#form_edit_obligacion').submit()
                }
            })

        })

});
</script>
@endsection