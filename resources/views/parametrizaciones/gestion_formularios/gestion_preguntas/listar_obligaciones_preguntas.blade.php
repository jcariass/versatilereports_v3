@extends('layouts.principal')

@section('style')
    <style>
        .page-item.active .page-link {
            color: #fff !important;
            background-color: #E96928 !important;
        }
    </style>
@endsection

@section('contenido')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Gestión de formularios - Gestión de preguntas</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('listar_formularios') }}">Listar formularios</a>
                            </li>
                            <li class="breadcrumb-item active">Lista de obligaciones
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
                            <h4 class="card-title">{{ $formulario->nombre }} - Lista de obligaciones</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li>
                                        <a href="{{ route('añadir_preguntas', ['id' => $formulario->id_formulario]) }}" class="btn btn-versatile_reports">
                                            <i class="ft-plus-square"></i> Añadir preguntas
                                        </a>
                                    </li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-content collapse show">
                                @if(Session::has('success'))
                                    <div class="alert alert-success">
                                        {{Session::get('success')}}
                                    </div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger" role="alert">
                                        @foreach ($errors->all() as $item)
                                            {{$item}}
                                        @endforeach
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table id="obligaciones" style="width: 100%;" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Obligacion</th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
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
        // function cambiar_estado(id, estado){
        //     alert("id: "+id+"\nestado: "+estado);
        // }
        let id_formulario = {{ $formulario->id_formulario }}
        $('#obligaciones').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/formularios/listar/obligaciones/'+id_formulario,
            columns: [
                {data: 'id_obligacion', name: 'id_obligacion'},
                {data: 'detalle', name: 'detalle'},
                {data: 'Opciones', name: 'Opciones'}
            ],
            language : {
                "processing": "Procesando...",
                "zeroRecords": "No se encontraron resultados",
                "emptyTable": "Ningún dato disponible en esta tabla",
                "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "infoFiltered": "(filtrado de un total de _MAX_ registros)",
                "search": "Buscar:",
                "infoThousands": ",",
                "loadingRecords": "Cargando...",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "info": "Mostrando de _START_ a _END_ de _TOTAL_ entradas",
                "lengthMenu": "Mostrar <select>"+
                            "<option value='10'>10</option>"+
                            "<option value='25'>25</option>"+
                            "<option value='50'>50</option>"+
                            "<option value='-1'>Todos</option>"+
                            "</select> registros"
            }
        });
    </script>
@endsection