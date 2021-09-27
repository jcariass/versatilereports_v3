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
                            <li class="breadcrumb-item"><a href="{{ route('listar_parrafos', ['id' => $plantilla->id_plantilla]) }}">Listar párrafos</a>
                            </li>
                            <li class="breadcrumb-item active">Añadir párrafos
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
                            <h4 class="card-title">{{ $plantilla->nombre }} - Añadir párrafos</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="form_crear_parrafo" class="form" action="{{ route('guardar_parrafos') }}" method="post">
                                @csrf
                                <input type="hidden" value="{{ $plantilla->id_plantilla }}" name="id_plantilla">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="plantilla">Plantilla (*)</label>
                                        <input type="text" value="{{ $plantilla->nombre }}" readonly class="form-control border-primary">
                                    </div>
                                </div><br>
                                <div class="card">
                                    <div class="card-body border-primary">
                                        <div class="row">
                                            <div class="col-sm-9">
                                                <label for="texto">Texto (*)</label>
                                                <textarea id="texto" class="form-control border-primary @error('texto') is-invalid @enderror" cols="30" rows="2"></textarea>
                                                @error('texto')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <p id="error_uno"></p>
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="numero_parrafo">Número de párrafo (*)</label>
                                                <input type="text" id="numero_parrafo" class="form-control border-primary @error('numero_parrafo') is-invalid @enderror">
                                                @error('numero_parrafo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <p id="error_dos"></p>
                                            </div>
                                        </div><br>
                                        <button type="button" onclick="agregar_parrafo()" class="btn btn-versatile_reports float-right">Añadir</button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table style="width: 100%;" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Texto</th>
                                                <th>Numero de párrafo</th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="parrafos_ingresados">

                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-actions text-center">
                                    <button type="submit" class="btn btn-primary btn-block" id="btn_submit">
                                        <i class="la la-save"></i>
                                        Guardar
                                    </button>
                                    <a href="{{ route('listar_parrafos', ['id' => $plantilla->id_plantilla]) }}" class="btn btn-warning mr-1 btn-block">
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
        const letras = /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/;
        const numeros = /^([0-9])*$/;
        let error_uno = document.querySelector("#error_uno");
        let error_dos = document.querySelector("#error_dos");
        let error_input_uno = document.querySelector("#texto");
        let error_input_dos = document.querySelector("#numero_parrafo");
        let contador = 0;
        let array_parrafo=[];
        function agregar_parrafo(){
            let texto = $('#texto').val();
            let numero_parrafo = $('#numero_parrafo').val();
            let bandera_uno = false;
            let bandera_dos = false;
            contador = contador + 1;

            if(texto==''){
                error_uno.textContent = "Este campo es obligatorio";
                error_input_uno.setAttribute("style", "border: 1px solid red !important;");
                bandera_uno=false;
            }else{
                if (!letras.exec(texto)) {
                    error_uno.textContent = "Solo se admiten letras";
                    error_input_uno.setAttribute("style", "border: 1px solid red !important;");
                    bandera_uno=false;
                }else{
                    error_uno.textContent = "";
                    error_input_uno.removeAttribute("style");
                    bandera_uno=true;
                }
            }

            if(numero_parrafo==''){
                error_dos.textContent = "Este campo es obligatorio";
                error_input_dos.setAttribute("style", "border: 1px solid red !important;");
                bandera_dos = false;
            }
            else{
                if (!numeros.exec(numero_parrafo)) {
                    error_dos.textContent = "Solo se admiten números";
                    error_input_dos.setAttribute("style", "border: 1px solid red !important;");
                    bandera_dos = false;
                }else{
                    error_dos.textContent = "";
                    error_input_dos.removeAttribute("style");
                    bandera_dos = true;
                }
            }

            if(bandera_uno==true && bandera_dos==true){
                array_parrafo.push(texto);
                $('#parrafos_ingresados').append(`
                    <tr id="tr-${contador}">
                        <input type="hidden" name="numero_parrafo[]" value="${numero_parrafo}">
                        <input type="hidden" name="texto_parrafo[]" value="${texto}">
                        <td>${texto}</td>
                        <td>${numero_parrafo}</td>
                        <td>
                            <button class="btn btn-danger" type="button" onclick="eliminar_parrafo('${contador}')">X</button>
                        </td>
                    </tr>
                `);
                $('#texto').val('');
                $('#numero_parrafo').val('');
                // error_uno.textContent = "";
                // error_dos.textContent = "";
            }
        }
        
        function eliminar_parrafo(contador){
            array_parrafo.splice(contador-1,1);
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
                    if(array_parrafo.length>0){
                        $('#form_crear_parrafo').submit()
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: '¡Advertencia!',
                            text: 'No hay párrafos creados.'
                        });
                    }
                }
            })

        })
        
    </script>
@endsection