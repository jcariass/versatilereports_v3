<table>
    <thead>
        <tr>
            <th colspan="16"  style="text-align: center;"><strong>Reporte contratistas con contratos activos</strong></th>
        </tr>
        <tr>
            <th style="width: 18px; text-align: center;"><strong>Documento</strong></th>
            <th style="width: 20px; text-align: center;"><strong>Lugar expedicion</strong></th>
            <th style="width: 20px; text-align: center;"><strong>Nombres</strong></th>
            <th style="width: 20px; text-align: center;"><strong>Apellidos</strong></th>
            <th style="width: 30px; text-align: center;"><strong>Correo sena</strong></th>
            <th style="width: 30px; text-align: center;"><strong>Correo</strong></th>
            <th style="width: 15px; text-align: center;"><strong>Celular 1</strong></th>
            <th style="width: 15px; text-align: center;"><strong>Celular 2</strong></th>
            <th style="width: 25px; text-align: center;"><strong>Numero contrato</strong></th>
            <th style="width: 18px; text-align: center;"><strong>Fecha de inicio</strong></th>
            <th style="width: 18px; text-align: center;"><strong>Fecha de fin</strong></th>
            <th style="width: 20px; text-align: center;"><strong>Valor</strong></th>
            <th style="width: 20px; text-align: center;"><strong>Objeto de contrato</strong></th>
            <th style="width: 20px; text-align: center;"><strong>Proceso</strong></th>
            <th style="width: 40px; text-align: center;"><strong>Forma de pago</strong></th>
        </tr>
    </thead>
    <tbody>
    @foreach($contratistas as $item)
        <tr>
            <td>{{ $item->tipo_documento . '. ' . $item->documento }}</td>
            <td>{{ $item->nombre_municipio }}</td>
            <td>{{ $item->nombre }}</td>
            <td>{{ $item->primer_apellido . ' ' . $item->segundo_apellido }}</td>
            <td>{{ $item->correo_sena }}</td>
            <td>{{ $item->correo }}</td>
            <td>{{ $item->celular_uno }}</td>
            <td>{{ $item->celular_dos }}</td>
            <td>{{ $item->numero_contrato }}</td>
            <td>{{ date('d/m/Y', strtotime($item->fecha_inicio)) }}</td>
            <td>{{ date('d/m/Y', strtotime($item->fecha_fin)) }}</td>
            <td>${{ number_format($item->valor, 2, ',', '.') }}</td>
            <td>{{ $item->nombre_objeto }}</td>
            <td>{{ $item->nombre_proceso }}</td>
            <td>{{ $item->forma_pago }}</td>
        </tr>
    @endforeach
    </tbody>
</table>