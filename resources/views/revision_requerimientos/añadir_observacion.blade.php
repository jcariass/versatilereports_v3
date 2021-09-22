@extends('layouts.principal')

@section('contenido')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Revisión de requerimientos - añadir observación</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('view_detalles_requerimientos', ['id' => isset($informe) == true ? $informe->id_requerimiento : $archivo->id_requerimiento]) }}">Listar requerimientos</a>
                            </li>
                            <li class="breadcrumb-item active">Agregar observación
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
                            <h4 class="card-title">Agregar observación</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-content collapse show">
                                <form id="agregar_observacion" class="form" action="{{ route('guardar_observacion') }}" method="POST">
                                    @csrf
                                    @method('put')
                                    <input type="hidden" name="id_respuesta" id="id_respuesta" value="{{ isset($informe) == true ? $informe->id_informe : $archivo->id_respuesta_requerimiento }}">
                                    <input type="hidden" name="tipo_requerimiento" id="tipo_requerimiento" value="{{ isset($informe) == true ? 1 : 2 }}">
                                    <input type="hidden" name="id_requerimiento" id="id_requerimiento" value="{{ isset($informe) == true ? $informe->id_requerimiento : $archivo->id_requerimiento }}">
                                    <div class="row justify-content-md-center">
                                        <div class="col-md-6">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label for="observacion">Observación (*)</label>
                                                    <textarea name="observacion" id="observacion" cols="30" rows="8">{{ isset($informe) == true ? ($informe->observacion == null ? 'Sin observación' : $informe->observacion) : ($archivo->observacion == null ? 'Sin observación' : $archivo->observacion) }}</textarea>
                                                    @error('observacion')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="fomr-actions text-center">
                                        <a href="{{ route('view_detalles_requerimientos', ['id' => isset($informe) == true ? $informe->id_requerimiento : $archivo->id_requerimiento]) }}" class="btn btn-warning mr-1">
                                            <i class="la la-close"></i>
                                            Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary" id="btn_submit">
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