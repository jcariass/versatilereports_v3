@extends('layouts.principal')

@section('style')
    <style>
        .error{
            color: red;
            font-style: italic;
        }
        #progressBar{
            width: 100%;
        }
    </style>
@endsection

@section('contenido')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Entrega de requerimientos - Cargar archivo</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('listar_ent_requerimientos') }}">Listar requerimientos</a>
                            </li>
                            <li class="breadcrumb-item active">Cargar archivo
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
                            <h4 class="card-title">Cargar archivo</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-content collapse show">
                                <form enctype="multipart/form-data" id="form_insertar_archivo" class="form" action="{{ route('insertar_archivo') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_requerimiento" id="id_requerimiento" value="{{ $requerimiento->id_requerimiento }}">
                                    <div class="row justify-content-md-center">
                                        <div class="col-md-6">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label for="archivo">Archivo (*)</label>
                                                    <input autocomplete="off" onchange="uploadFile()" type="file" class="form-control border-primary @error('archivo') is-invalid @enderror" name="archivo" id="archivo">
                                                    @error('archivo')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <progress id="progressBar" value="0" max="100"></progress>
                                                    <h3 id="status"></h3>
                                                    <p id="loaded_n_total"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="fomr-actions text-center">
                                        <a href="{{ route('listar_ent_requerimientos') }}" class="btn btn-warning mr-1">
                                            <i class="la la-close"></i>
                                            Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
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
<script>
    $(document).ready(function() {
        $("#form_insertar_archivo").validate({
            rules: {
                archivo: {
                    required: true,
                    minlength: 4,
                    maxlength: 40,
                    extension: "pdf"
                },
            },
            messages : {
                archivo: {
                    required: "Campo obligatorio",
                    minlength: "El archivo debe tener un nombre de minimo 4 caracteres",
                    maxlength: "El archivo debe tener un nombre de maximo 40 caracteres",
                    extension: "El archivo debe estar en formato pdf"
                }
            }
        });
  });
    function _(el) {
    return document.getElementById(el);
    }

    function uploadFile() {
    var file = _("archivo").files[0];
    // alert(file.name+" | "+file.size+" | "+file.type);
    var formdata = new FormData();
    formdata.append("archivo", file);
    var ajax = new XMLHttpRequest();
    ajax.upload.addEventListener("progress", progressHandler, false);
    ajax.addEventListener("load", completeHandler, false);
    ajax.addEventListener("error", errorHandler, false);
    ajax.addEventListener("abort", abortHandler, false);
    ajax.open("POST", "file_upload_parser.php");
    ajax.send(formdata);
    }

    function progressHandler(event) {
    _("loaded_n_total").innerHTML = "Cargado " + event.loaded + " bytes de " + event.total;
    var percent = (event.loaded / event.total) * 100;
    _("progressBar").value = Math.round(percent);
    _("status").innerHTML = Math.round(percent) + "% cargando... porfavor espere.";
    }

    function completeHandler(event) {
    _("status").innerHTML = event.target.responseText;
    _("progressBar").value = 0;
    }

    function errorHandler(event) {
    _("status").innerHTML = "Fallo al cargar el archivo";
    }

    function abortHandler(event) {
    _("status").innerHTML = "La carga de archivo fue abortada";
    }
</script>
@endsection