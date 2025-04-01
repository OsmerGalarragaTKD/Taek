@extends('layouts.app')

@section('title', 'System Logs')

@section('content_header')
    <h1>System Logs</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All System Logs</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="logs-table" class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Action</th>
                            <th>Model</th>
                            <th>Model ID</th>
                            <th>User</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>
                                    @if($log->action == 'create')
                                        <span class="badge badge-success">{{ ucfirst($log->action) }}</span>
                                    @elseif($log->action == 'update')
                                        <span class="badge badge-info">{{ ucfirst($log->action) }}</span>
                                    @elseif($log->action == 'delete')
                                        <span class="badge badge-danger">{{ ucfirst($log->action) }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($log->action) }}</span>
                                    @endif
                                </td>
                                <td>{{ $log->model }}</td>
                                <td>{{ $log->model_id }}</td>
                                <td>{{ $log->user->name ?? 'Unknown User' }}</td>
                                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('logs.show', $log->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No logs found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Verifica que el elemento con ID 'logs-table' exista antes de inicializar DataTables
            if ($('#logs-table').length) {
                $('#logs-table').DataTable({
                    "language": {
                        "sProcessing":     "Procesando...",
                        "sLengthMenu":     "Mostrar _MENU_ registros",
                        "sZeroRecords":    "No se encontraron resultados",
                        "sEmptyTable":     "Ningún dato disponible en esta tabla",
                        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                        "sSearch":         "Buscar:",
                        "oPaginate": {
                            "sFirst":    "Primero",
                            "sLast":     "Último",
                            "sNext":     "Siguiente",
                            "sPrevious": "Anterior"
                        }
                    },
                    "responsive": true,
                    "autoWidth": false,
                    "order": [[2, 'desc']], // Ordenar por la columna de fecha en orden descendente
                    "pageLength": 10,
                    // Combina la disposición personalizada con los botones de exportación y el selector de registros
                    "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                   '<"row"<"col-sm-12"tr>>' +
                   '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
          
                  
                  /*  "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"B>>' +
                           '<"row"<"col-sm-12"f>>' +
                           '<"row"<"col-sm-12"tr>>' +
                           '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                  */  /* "buttons": [
                        {
                            extend: 'excel',
                            text: '<i class="fas fa-file-excel mr-1"></i> Excel',
                            className: 'btn btn-success btn-sm',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6] // Columnas a exportar
                            }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fas fa-file-pdf mr-1"></i> PDF',
                            className: 'btn btn-danger btn-sm',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6] // Columnas a exportar
                            }
                        },
                        {
                            extend: 'print',
                            text: '<i class="fas fa-print mr-1"></i> Imprimir',
                            className: 'btn btn-info btn-sm',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6] // Columnas a exportar
                            }
                        }
                    ], */
                    "columnDefs": [
                        {
                            "targets": -1, // Última columna (acciones)
                            "orderable": false // No permitir ordenar
                        }
                    ]
                });
            } else {
                console.error("La tabla con ID 'logs-table' no existe en el DOM.");
            }
        });
    </script>
@stop

