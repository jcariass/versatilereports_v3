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
                <h3 class="content-header-title mb-0">Detalle del requerimiento</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('listar_rev_requerimientos') }}">Listar requerimientos</a>
                            </li>
                            <li class="breadcrumb-item active">Detalle del requerimiento
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body"> 
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Detalle del requerimiento</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li>
                                        <form action="{{ route('reporte_requerimientos') }}" method="post">
                                            @csrf
                                            <input type="hidden" value="{{ $requerimiento->id_tipo_requerimiento }}" name="id_tipo_requerimiento" id="id_tipo_requerimiento">
                                            <input type="hidden" value="{{ $requerimiento->id_requerimiento }}" name="id_requerimiento" id="id_requerimiento">
                                            <button type="submit" class="btn btn-gris"><i class="ft-file"></i> Generar reporte</button>
                                        </form>
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
                                    <table id="requerimientos_detalles" class="table table-column">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Documento</th>
                                                <th>Fecha carga</th>
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
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="{{ asset('sweet_alert2/sweetalert2@11.js') }}"></script>
<script>
    let id_requerimiento = {{ $requerimiento->id_requerimiento }}
    let tipo = {{ $requerimiento->id_tipo_requerimiento }}
    $('#requerimientos_detalles').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/revision/requerimientos/detalles/listar/"+id_requerimiento+"/"+tipo,
        columns: [
            {data: 'nombre', name: 'nombre'},
            {data: 'documento', name: 'documento'},
            {data: 'fecha_carga', name: 'fecha_carga'},
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
    function confirm(id_respuesta_requerimiento, estado) {
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
                    url: "/revision/requerimientos/estado/archivo/"+id_respuesta_requerimiento+"/"+estado,
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

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function confirm_dos(id_informe, estado) {
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
                    url: "/revision/requerimientos/estado/uno/informe/"+id_informe+"/"+estado,
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

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function confirm_tres(id_respuesta_requerimiento, estado) {
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
                    url: "/revision/requerimientos/estado/dos/informe/"+id_respuesta_requerimiento+"/"+estado,
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