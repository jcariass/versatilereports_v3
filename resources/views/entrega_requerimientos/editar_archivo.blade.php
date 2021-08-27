@extends('layouts.principal')

@section('contenido')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Entrega de requerimientos - Editar archivo</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('listar_ent_requerimientos') }}">Listar requerimientos</a>
                            </li>
                            <li class="breadcrumb-item active">Editar archivo
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
                            <h4 class="card-title">Editar archivo</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li>
                                        <a class="btn btn-versatile_reports" href="{{ url('/entrega/requerimientos/descargar/archivo/'.$respuesta->nombre) }}">Descargar archivo existente</a>
                                    </li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-content collapse show">
                                <form enctype="multipart/form-data" class="form" action="{{ route('update_archive') }}" method="POST">
                                    @csrf
                                    @method('put')
                                    <div class="row justify-content-md-center">
                                        <div class="col-md-6">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <input type="hidden" value="{{ $respuesta->id_respuesta_requerimiento }}" name="id_respuesta_requerimiento">
                                                    <label for="archivo">Archivo (*)</label>
                                                    <input required autocomplete="off" type="file" class="form-control border-primary @error('archivo') is-invalid @enderror" name="archivo" id="archivo">
                                                    @error('archivo')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
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
                                        </div>
                                    </div>
                                    <hr>
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