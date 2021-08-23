<table>
    <thead>
        <tr></tr>
        <tr>
            <th></th>
            <th colspan="4"  style="text-align: center;"><strong>Reporte respuestas requerimiento</strong></th>
        </tr>
        <tr>
            <th></th>
            <th colspan="4" style="height: 45px" align="left"><strong>Nombre requerimiento:&nbsp;</strong>{{ $respuestas_requerimientos[0]->nombre_requerimiento }}<br><strong>Fecha creacion:&nbsp;</strong>{{ date('d/m/Y', strtotime($respuestas_requerimientos[0]->fecha_creacion)) }}<br><strong>Fecha finalización:&nbsp;</strong>{{ date('d/m/Y', strtotime($respuestas_requerimientos[0]->fecha_finalizacion)) }}</th>
        </tr>
        <tr>
            <th></th>
            <th style="width: 18px; text-align: center;"><strong>Documento</strong></th>
            <th style="width: 20px; text-align: center;"><strong>Nombres</strong></th>
            <th style="width: 20px; text-align: center;"><strong>Apellidos</strong></th>
            <th style="width: 20px; text-align: center;"><strong>Fecha carga</strong></th>
        </tr>
    </thead>
    <tbody>
    @foreach($respuestas_requerimientos as $item)
        <tr>
            <td></td>
            <td>{{ $item->documento }}</td>
            <td>{{ $item->nombre_contratista }}</td>
            <td>{{ $item->primer_apellido . ' ' . $item->segundo_apellido }}</td>
            <td>{{ date('d/m/Y - h:i', strtotime($item->fecha_carga)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>