@extends('layouts.principal')

@section('style')
    <style>
        .error{
            color: red;
            font-style: italic;
        }
    </style>
@endsection

@section('contenido')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Gestión de procesos</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('listar_procesos') }}">Listar procesos</a>
                            </li>
                            <li class="breadcrumb-item active">Editar proceso
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
                            <h4 class="card-title">Editar proceso</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-content collapse show">
                                <form id="form_edit_proceso" class="form" action="{{ route('editar_procesos') }}" method="POST">
                                    @csrf
                                    @method('put')
                                    <input type="hidden" name="id_proceso" id="id_proceso" value="{{ $proceso->id_proceso }}">
                                    <div class="row justify-content-md-center">
                                        <div class="col-md-6">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label for="nombre">Nombre proceso (*)</label>
                                                    <input autocomplete="off" type="text" value="{{ $proceso->nombre }}" class="form-control border-primary @error('nombre') is-invalid @enderror" name="nombre" id="nombre">
                                                    @error('nombre')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="fomr-actions text-center">
                                        <a href="{{ route('listar_procesos') }}" class="btn btn-warning mr-1">
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