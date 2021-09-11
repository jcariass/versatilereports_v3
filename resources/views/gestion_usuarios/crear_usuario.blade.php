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
                <h3 class="content-header-title mb-0">Gestión de usuarios</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('listar_usuarios') }}">Listar usuarios</a>
                            </li>
                            <li class="breadcrumb-item active">Crear usuario
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
                            <h4 class="card-title">Crear usuario</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="form_crear_usuario" class="form" action="{{ route('registrar_usuario') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tipo_documento">Tipo documento (*)</label>
                                            <select class="@error('tipo_documento') is-invalid @enderror form-control border-primary" name="tipo_documento" id="tipo_documento">
                                                <option value="">Seleccione</option>
                                                <option value="CC">Cedula Ciudadania</option>
                                                <option value="CE">Cedula Extranjera</option>
                                            </select>
                                            @error('tipo_documento')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="documento">Documento (*)</label>
                                            <input placeholder="Documento..." type="text" class="@error('documento') is-invalid @enderror form-control border-primary" name="documento" id="documento">
                                            @error('documento')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="nombre">Nombre (*)</label>
                                            <input placeholder="Nombre..." type="text" class="@error('nombre') is-invalid @enderror form-control border-primary" name="nombre" id="nombre">
                                            @error('nombre')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="primer_apellido">Primer apellido (*)</label>
                                            <input placeholder="Primer apellido..." type="text" class="@error('primer_apellido') is-invalid @enderror form-control border-primary" name="primer_apellido" id="primer_apellido">
                                            @error('primer_apellido')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="segundo_apellido">Segundo apellido</label>
                                            <input placeholder="Segundo apellido..." type="text" class="@error('segundo_apellido') is-invalid @enderror form-control border-primary" name="segundo_apellido" id="segundo_apellido">
                                            @error('segundo_apellido')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="correo">Correo personal (*)</label>
                                            <input placeholder="Correo personal..." type="text" class="@error('correo') is-invalid @enderror form-control border-primary" name="correo" id="correo">
                                            @error('correo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="celular_uno">Celular uno (*)</label>
                                            <input placeholder="Celular uno..." type="text" class="@error('celular_uno') is-invalid @enderror form-control border-primary" name="celular_uno" id="celular_uno">
                                            @error('celular_uno')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="celular_dos">Celular dos</label>
                                            <input placeholder="Celular dos..." type="text" class="@error('celular_dos') is-invalid @enderror form-control border-primary" name="celular_dos" id="celular_dos">
                                            @error('celular_dos')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Lugar expedición documento / Departamento / Municipio (*)</label>
                                            <div class="d-flex justify-content-between">
                                                <select id="id_departamento" class="form-control border-primary">
                                                    <option value="">Seleccion un departamento</option>
                                                    @foreach ($departamentos as $item)
                                                        <option value="{{ $item->id_departamento }}">{{ $item->nombre }}</option>
                                                    @endforeach
                                                </select>
                                                <select name="id_municipio" id="id_municipio" class="@error('id_municipio') is-invalid @enderror form-control border-primary"></select>
                                                @error('id_municipio')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="id_rol">Rol (*)</label>
                                            <select name="id_rol" id="id_rol" class="@error('id_rol') is-invalid @enderror form-control border-primary">
                                                <option value="">Seleccione</option>
                                                @foreach ($roles as $item)
                                                    <option value="{{ $item->id_rol }}">{{ $item->nombre }}</option>
                                                @endforeach
                                            </select>
                                            @error('id_rol')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Contraseña (*)</label>
                                            <input placeholder="Contraseña..." type="password" name="password" class="form-control border-primary @error('password') is-invalid @enderror" id="password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Confirmar contraseña (*)</label>
                                            <input placeholder="Confirmar contraseña..." type="password" name="password_confirmation" class="form-control border-primary" id="password_confirmation">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions text-center">
                                    <button type="submit" class="btn btn-primary btn-block" id="btn_submit">
                                        <i class="la la-save"></i>
                                        Guardar
                                    </button>
                                    <a href="{{ route('listar_usuarios') }}" class="btn btn-warning mr-1 btn-block">
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

<script src="{{ asset('sweet_alert2/sweetalert2@11.js') }}"></script>

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

            /* Metodo para letras con acentos */
        jQuery.validator.addMethod("letras", function(value, element) {
            return this.optional(element) || /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/.test(value);
        });

        $("#form_crear_usuario").validate({

            onfocusin: function(element) { $(element).valid(); },
            onfocusout: function(element) { $(element).valid(); },
            onclick: function(element) { $(element).valid(); },
            onkeyup: function(element) { $(element).valid(); },

            rules: {
                tipo_documento: {
                    required: true
                },

                documento: {
                    required: true,
                    number: true,
                    minlength: 7,
                    maxlength: 11
                },

                nombre: {
                    required: true,
                    letras: true,
                    minlength: 3,
                    maxlength: 40
                },

                primer_apellido: {
                    required: true,
                    letras: true,
                    minlength: 3,
                    maxlength: 30
                },

                segundo_apellido: {
                    letras: true,
                    minlength: 3,
                    maxlength: 30
                },

                correo: {
                    required: true
                },

                celular_uno: {
                    required: true,
                    number: true,
                    minlength: 7,
                    maxlength: 10
                },

                celular_dos: {
                    number: true,
                    minlength: 7,
                    maxlength: 10
                },

                id_departamento: {
                    required: true
                },

                id_municipio: {
                    required: true
                },

                id_rol: {
                    required: true
                },

                password: {
                    required: true,
                    minlength: 8,
                    maxlength: 20
                },

                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                }
            },

            messages: {
                tipo_documento: {
                    required: "Seleccione un tipo de documento"
                },

                documento: {
                    required: "Este campo es obligatorio",
                    number: "Solo te admiten números",
                    minlength: "El documento debe tener minimo 7 dígitos",
                    maxlength: "El documento puede tener máximo 11 dígitos"
                },

                nombre: {
                    required: "Este campo es obligatorio",
                    letras: "Solo te admiten letras",
                    minlength: "El nombre debe tener minimo 3 caracteres",
                    maxlength: "El nombre puede tener máximo 40 caracteres"
                },

                primer_apellido: {
                    required: "Este campo es obligatorio",
                    letras: "Solo te admiten letras",
                    minlength: "El apellido debe tener minimo 3 caracteres",
                    maxlength: "El apellido puede tener máximo 30 caracteres"
                },

                segundo_apellido: {
                    letras: "Solo te admiten letras",
                    minlength: "El apellido debe tener minimo 3 caracteres",
                    maxlength: "El apellido puede tener máximo 30 caracteres"
                },

                correo: {
                    required: "Este campo es obligatorio"
                },

                celular_uno: {
                    required: "Este campo es obligatorio",
                    number: "Solo te admiten números",
                    minlength: "El celular debe tener minimo 7 dígitos",
                    maxlength: "El celular puede tener máximo 11 dígitos"
                },

                celular_dos: {
                    number: "Solo te admiten números",
                    minlength: "El celular debe tener minimo 7 dígitos",
                    maxlength: "El celular puede tener máximo 11 dígitos"
                },

                id_departamento: {
                    required: "Seleccione un logar de expedición"
                },

                id_municipio: {
                    required: "Seleccione un logar de expedición"
                },

                id_rol: {
                    required: "Seleccione un rol"
                },

                password: {
                    required: "Este campo es obligatorio",
                    minlength: "La contraseña debe tener minimo 8 dígitos",
                    maxlength: "La contraseña puede tener máximo 20 dígitos"
                },

                password_confirmation: {
                    required: "Este campo es obligatorio",
                    equalTo: "Las contraseñas deben coincidir"
                }
            },
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
                    $('#form_crear_usuario').submit()
                }
            })

        })

        });
    </script>
@endsection