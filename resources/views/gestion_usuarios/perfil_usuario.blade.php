@extends('layouts.principal')

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
                        @if(Session::has('success'))
                            <div class="card-body">
                                <div class="alert alert-success">
                                    {{Session::get('success')}}
                                </div>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="card-body">
                                <div class="alert alert-danger" role="alert">
                                    @foreach ($errors->all() as $item)
                                        {{$item}}
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="card-body">
                            <form enctype="multipart/form-data" id="form_crear_usuario" class="form" action="{{ route('update_perfil') }}" method="post">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="correo">Correo personal (*)</label>
                                            <input value="{{ $usuario->correo }}" placeholder="Correo personal..." type="text" class="@error('correo') is-invalid @enderror form-control border-primary" name="correo" id="correo">
                                            @error('correo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="correo_sena">Correo sena</label>
                                            <input value="{{ $usuario->correo_sena }}" placeholder="Correo sena..." type="text" class="@error('correo_sena') is-invalid @enderror form-control border-primary" name="correo_sena" id="correo_sena">
                                            @error('correo_sena')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="celular_uno">Celular uno (*)</label>
                                            <input value="{{ $usuario->celular_uno }}" placeholder="Celular uno..." type="text" class="@error('celular_uno') is-invalid @enderror form-control border-primary" name="celular_uno" id="celular_uno">
                                            @error('celular_uno')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="celular_dos">Celular dos</label>
                                            <input value="{{ $usuario->celular_dos }}" placeholder="Celular dos..." type="text" class="@error('celular_dos') is-invalid @enderror form-control border-primary" name="celular_dos" id="celular_dos">
                                            @error('celular_dos')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="firma">Firma</label>
                                        <input type="file" name="firma" id="firma" class="form-control border-primary @error('firma') is-invalid @enderror">
                                        @error('firma')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Firma actual</h5>
                                            </div>
                                            <div class="card-body">
                                                @if ($usuario->firma == null)
                                                    <h6>No existe firma actual.</h6>
                                                @else
                                                    <img src="{{ asset('uploads/firmas/'.$usuario->firma) }}" width="500px" height="100px" alt="Imagen firma">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h2>Cambiar contraseña</h2>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Contraseña</label>
                                                            <input placeholder="Contraseña..." type="password" name="password" class="form-control border-primary @error('password') is-invalid @enderror" id="password">
                                                            @error('password')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Confirmar contraseña</label>
                                                            <input placeholder="Confirmar contraseña..." type="password" name="password_confirmation" class="form-control border-primary" id="password_confirmation">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions text-center">
                                    <button type="submit" class="btn btn-primary btn-block" id="btn_submit">
                                        <i class="la la-save"></i>
                                        Guardar
                                    </button>
                                    <a href="{{ route('dashboard') }}" class="btn btn-warning mr-1 btn-block">
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