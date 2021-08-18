@extends('layouts.principal')

@section('contenido')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Gestión de contratistas - Gestión de contratos</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('listar_contratistas') }}">Listar contratistas</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('listar_contratos', ['id' => $contrato->id_persona]) }}">Listar contratos</a>
                            </li>
                            <li class="breadcrumb-item active">Detalle de contrato
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
                            <h4 class="card-title">Detalle de contrato</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-content collapse show">
                                <div class="table-responsive">
                                    <table style="width: 100%;" class="table table-bordered">
                                        <thead>
                                            <th class="align-center">CONTRATISTA: {{ $contrato->nombre_persona . ' ' . $contrato->primer_apellido_persona }}</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <table class="table table-column">
                                                        <tbody>
                                                            <tr>
                                                                <td><strong>Nombres: </strong><td>{{ $contrato->nombre_persona }}</td></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Apellidos: </strong><td>{{ $contrato->primer_apellido_persona . ' ' . $contrato->segundo_apellido_persona }}</td></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Documento: </strong><td>{{ $contrato->tipo_documento_persona . '. ' .$contrato->documento_persona }}</td></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Numero de contrato: </strong><td>{{ $contrato->numero_contrato }}</td></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Fecha inicio contrato: </strong><td>{{ $contrato->fecha_inicio }}</td></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Fecha fin contrato: </strong><td>{{ $contrato->fecha_fin }}</td></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Valor del contrato: </strong><td>{{ $contrato->valor }}</td></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Forma de pago del contrato: </strong><td>{{ $contrato->forma_pago }}</td></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Estado del contrato: </strong><td>{{ $contrato->estado == 0 ? 'Sin asignar' : ($contrato->estado == 1 ? 'Contrato asignado' : 'Contrato vencido') }}</td></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Lugar de contrato: </strong><td>{{ $contrato->nombre_departamento . ' / ' . $contrato->nombre_municipio }}</td></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Proceso: </strong><td>{{ $contrato->nombre_proceso }}</td></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Objeto de contrato: </strong><td>{{ $contrato->nombre_objeto }}</td></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Supervisor del contrato: </strong><td>{{ $contrato->nombre_supervisor.' '.$contrato->primer_apellido_supervisor.' '.$contrato->segundo_apellido_supervisor }}</td></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Centro SENA: </strong><td>{{ $contrato->nombre_centro }}</td></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Firma: </strong><td><img src="{{ asset('uploads/firmas/'.$contrato->firma_persona.'') }}" width="80" height="80"></td></td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <div class="container">
                                                                        <div class="row justify-content-center">
                                                                            <div class="col-md-4">
                                                                                <a href="{{ route('listar_contratos', ['id' => $contrato->id_persona]) }}" class="btn btn-gris col-8 ml-5">Regresar</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection