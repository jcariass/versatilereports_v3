@extends('layouts.principal')

@section('contenido')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Gestión de formularios</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('listar_formularios') }}">Listar formularios</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('obligaciones_formulario', ['id' => $formulario->id_formulario]) }}">Listar preguntas</a>
                            </li>
                            <li class="breadcrumb-item active">Añadir preguntas
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
                            <h4 class="card-title">{{ $formulario->nombre }} - Añadir preguntas</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="form_crear_pregunta" class="form" action="{{ route('registrar_preguntas') }}" method="post">
                                @csrf
                                <input type="hidden" value="{{ $formulario->id_formulario }}" name="id_formulario">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="formulario">Formulario (*)</label>
                                        <input type="text" value="{{ $formulario->nombre }}" readonly class="form-control border-primary">
                                    </div>
                                </div><br>
                                <div class="card">
                                    <div class="card-body border-primary">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label for="id_obligacion">Obligación (*)</label>
                                                <select id="id_obligacion" class="form-control border-primary">
                                                    <option value="">Seleccione la obligación</option>
                                                    @foreach ($obligaciones as $obligacion)
                                                        <option value="{{ $obligacion->id_obligacion }}">{{ $obligacion->detalle }}</option>
                                                    @endforeach
                                                </select>
                                                <p id="error_uno"></p>
                                            </div>
                                        </div><br>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="pregunta_actividad">Pregunta actividad (*)</label>
                                                <input type="text" name="pregunta_actividad" id="pregunta_actividad" class="form-control border-primary @error('pregunta_actividad') is-invalid @enderror">
                                                @error('pregunta_actividad')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <p id="error_dos"></p>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="pregunta_evidencia">Pregunta evidencia (*)</label>
                                                <input type="text" name="pregunta_evidencia" id="pregunta_evidencia" class="form-control border-primary @error('pregunta_evidencia') is-invalid @enderror">
                                                @error('pregunta_evidencia')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <p id="error_tres"></p>
                                            </div>
                                        </div><br>
                                        <button type="button" onclick="agregar_pregunta()" class="btn btn-versatile_reports float-right">Añadir</button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table style="width: 100%;" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Pregunta actividad</th>
                                                <th>Pregunta evidencia</th>
                                                <th>Opción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="preguntas_ingresadas">

                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-actions text-center">
                                    <button type="submit" class="btn btn-primary btn-block" id="btn_submit">
                                        <i class="la la-save"></i>
                                        Guardar
                                    </button>
                                    <a href="{{ route('obligaciones_formulario', ['id' => $formulario->id_formulario]) }}" class="btn btn-warning mr-1 btn-block">
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

    <script>
        // const letras = /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/;
        // const numeros = /^([0-9])*$/;
        let error_uno = document.querySelector("#error_uno");
        let error_dos = document.querySelector("#error_dos");
        let error_tres = document.querySelector("#error_tres");
        // let error_input_uno = document.querySelector("#id_obligacion");
        let error_input_dos = document.querySelector("#pregunta_actividad");
        let error_input_tres = document.querySelector("#pregunta_evidencia");
        let contador = 0;
        let array_pregunta=[];
        function agregar_pregunta(){
            let id_obligacion = $('#id_obligacion option:selected').val();
            let pregunta_actividad = $('#pregunta_actividad').val();
            let pregunta_evidencia = $('#pregunta_evidencia').val();
            let bandera_uno = false;
            let bandera_dos = false;
            let bandera_tres = false;
            contador = contador + 1;

            if(id_obligacion==''){
                error_uno.textContent = "Debe seleccionar una obligación";
                // error_input_uno.setAttribute("style", "border: 1px solid red !important;");
                bandera_uno=false;
            }else{
                error_uno.textContent = "";
                // error_input_uno.removeAttribute("style");
                bandera_uno=true;
            }

            if(pregunta_actividad==''){
                error_dos.textContent = "Este campo es obligatorio";
                error_input_dos.setAttribute("style", "border: 1px solid red !important;");
                bandera_dos=false;
            }else{
                error_dos.textContent = "";
                error_input_dos.removeAttribute("style");
                bandera_dos=true;
            }

            if(pregunta_evidencia==''){
                error_tres.textContent = "Este campo es obligatorio";
                error_input_tres.setAttribute("style", "border: 1px solid red !important;");
                bandera_tres = false;
            }
            else{ 
                error_tres.textContent = "";
                error_input_tres.removeAttribute("style");
                bandera_tres = true;
            }

            if(bandera_uno==true && bandera_dos==true && bandera_tres==true){
                array_pregunta.push(pregunta_actividad);
                $('#preguntas_ingresadas').append(`
                    <tr id="tr-${contador}">
                        <input type="hidden" name="identificaciones_obligacion[]" value="${id_obligacion}">
                        <input type="hidden" name="preguntas_actividad[]" value="${pregunta_actividad}">
                        <input type="hidden" name="preguntas_evidencia[]" value="${pregunta_evidencia}">
                        <td>${contador}</td>
                        <td>${pregunta_actividad}</td>
                        <td>${pregunta_evidencia}</td>
                        <td>
                            <button class="btn btn-danger" type="button" onclick="eliminar_pregunta('${contador}')">X</button>
                        </td>
                    </tr>
                `);
                $('#pregunta_actividad').val('');
                $('#pregunta_evidencia').val('');
            }

        }
        
        function eliminar_pregunta(contador){
            array_pregunta.splice(contador-1,1);
            $('#tr-'+contador).remove();
        }

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
                    if(array_pregunta.length>0){
                        $('#form_crear_pregunta').submit()
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: '¡Advertencia!',
                            text: 'No hay preguntas creadas.'
                        });
                    }
                }
            })

        })

    </script>
@endsection