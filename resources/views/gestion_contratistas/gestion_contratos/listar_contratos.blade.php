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
                <h3 class="content-header-title mb-0">Gestión de contratistas - Gestión de contratos</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('listar_contratistas') }}">Listar contratistas</a>
                            </li>
                            <li class="breadcrumb-item active">Lista de contratos
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
                            <h4 class="card-title">Lista de contratos - {{ $persona->nombre . ' ' . $persona->primer_apellido . ' ' . $persona->segundo_apellido }}</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li>
                                        <a href="{{ route('crear_contratos', ['id' => $persona->id_persona]) }}" class="btn btn-versatile_reports">
                                            <i class="ft-plus-square"></i> Nuevo
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
                                <div id="validation-message"></div>
                                <div class="table-responsive">
                                    <table id="contratos" style="width: 100%;" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Número contrato</th>
                                                <th>Fecha inicio</th>
                                                <th>Fecha fin</th>
                                                <th>Valor</th>
                                                <th>Estado</th>
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
<script src="{{ asset('sweet_alert2/sweetalert2@11.js') }}"></script>
<script>
    let id_persona = {{ $persona->id_persona }}
    $('#contratos').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/contratistas/contratos/listar/"+id_persona,
        columns: [
            {data: 'numero_contrato', name: 'numero_contrato'},
            {data: 'fecha_inicio', name: 'fecha_inicio'},
            {data: 'fecha_fin', name: 'fecha_fin'},
            {data: 'valor', name: 'valor'},
            {data: 'estado', name: 'estado'},
            {data: 'Opciones', name: 'Opciones', orderable: false, searchable: false}
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

    /* función para cambiar estado */
    function confirm(id_contrato, estado) {
        Swal.fire({
            title: '¿Estás seguro de cambiar el estado?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed){
                $.ajax({
                    url: "/contratistas/contratos/cambiar/estado/"+id_contrato+"/"+estado,
                    type: 'GET',
                    success: function(result) {
                        if(result){
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: '¡Se actualizó el estado!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(() => {  
                                location.reload();
                            }, 1500);
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: '¡Ocurrió un error inesperado!'
                            });
                        }
                    }
                });
            }
        })
    }

</script>
@endsection