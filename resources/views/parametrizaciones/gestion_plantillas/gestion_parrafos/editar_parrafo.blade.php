@extends('layouts.principal')

@section('contenido')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Gestión de plantillas y párrafos</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('listar_plantillas') }}">Listar plantillas</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('listar_parrafos', ['id' => $parrafo->id_plantilla]) }}">Listar parrafos</a>
                            </li>
                            <li class="breadcrumb-item active">Editar parrafo
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
                            <h4 class="card-title">Editar parrafo</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="form_edit_parrafo" class="form" action="{{ route('editar_parrafo') }}" method="post">
                                @csrf
                                @method('put')
                                <input type="hidden" name="id_parrafo" value="{{ $parrafo->id_parrafo }}">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <div class="form-group">
                                            <label for="texto">Texto (*)</label>
                                            <textarea class="@error('texto') is-invalid @enderror form-control border-primary" name="texto" id="texto" cols="30" rows="5">{{ $parrafo->texto }}</textarea>
                                            @error('texto')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <p id="error_uno"></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="numero_parrafo">Numero de parrafo (*)</label>
                                            <input value="{{ $parrafo->numero_parrafo }}" class="@error('numero_parrafo') is-invalid @enderror form-control border-primary" name="numero_parrafo" id="numero_parrafo">
                                            @error('numero_parrafo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <p id="error_dos"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions text-center">
                                    <button type="submit" class="btn btn-primary btn-block" id="btn_submit">
                                        <i class="la la-save"></i>
                                        Guardar
                                    </button>
                                    <a href="{{ route('listar_parrafos', ['id' => $parrafo->id_plantilla]) }}" class="btn btn-warning mr-1 btn-block">
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

        /* Método para letras con acentos */
        jQuery.validator.addMethod("letras", function(value, element) {
            return this.optional(element) || /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/.test(value);
        });

        $("#form_edit_parrafo").validate({

            // onfocusin: function(element) { $(element).valid(); },
            onfocusout: function(element) { $(element).valid(); },
            // onclick: function(element) { $(element).valid(); },
            // onkeyup: function(element) { $(element).valid(); },

            rules: {
                texto: {
                    required: true,
                    letras: true
                },

                numero_parrafo: {
                    required: true,
                    number: true
                }
            },
            messages: {
                texto: {
                    required: "Este campo es obligatorio",
                    letras: "Solo se admiten letras"
                },

                numero_parrafo: {
                    required: "Este campo es obligatorio",
                    number: "Solo se admiten números"
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
                    $('#form_edit_parrafo').submit()
                }
            })

        })

    });
</script>
<!-- Fin de validación/////////////////////////////////////////////////////////////////////////////////////-->
@endsection