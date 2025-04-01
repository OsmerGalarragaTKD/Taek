@extends('adminlte::page')

@section('title', 'Pagos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Gestión de Pagos</h1>
        <div>
            <a href="{{ route('payments.pending') }}" class="btn btn-warning mr-2">
                <i class="fas fa-clock mr-1"></i> Pagos Pendientes
            </a>
            <a href="{{ route('payments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Registrar Pago
            </a>
        </div>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="payments-table" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Atleta</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Método</th>
                            <th>Estado</th>
                            <th>Referencia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ $payment->athlete->full_name }}</td>
                                <td>${{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                <td>
                                    @switch($payment->payment_type)
                                        @case('Monthly_Fee')
                                            Mensualidad
                                        @break

                                        @case('Event_Registration')
                                            Evento
                                        @break

                                        @case('Equipment')
                                            Equipo
                                        @break

                                        @default
                                            {{ $payment->payment_type }}
                                    @endswitch
                                </td>
                                <td>
                                    @switch($payment->payment_method)
                                        @case('Transfer')
                                            Transferencia
                                        @break

                                        @case('Card')
                                            Tarjeta
                                        @break

                                        @case('Cash')
                                            Efectivo
                                        @break

                                        @default
                                            {{ $payment->payment_method }}
                                    @endswitch
                                </td>
                                <td>
                                    <span
                                        class="badge badge-{{ $payment->status === 'Completed' ? 'success' : ($payment->status === 'Pending' ? 'warning' : 'danger') }}">
                                        {{ $payment->status }}
                                    </span>
                                </td>
                                <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-info"
                                            title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if ($payment->status === 'Pending')
                                            <form action="{{ route('payments.approve-single', $payment->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Aprobar pago"
                                                    onclick="return confirm('¿Está seguro de aprobar este pago?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/buttons.bootstrap4.min.css') }}">
@stop

@section('js')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/jszip.min.js') }}"></script>
    <script src="{{ asset('js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/buttons.print.min.js') }}"></script>

    <script>
      
                $(document).ready(function() {
            // Verifica que el elemento con ID 'payments-table' exista antes de inicializar DataTables
            if ($('#payments-table').length) {
                $('#payments-table').DataTable({
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
                    "dom": '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>' +
                           '<"row"<"col-sm-12"l>>' +
                           '<"row"<"col-sm-12"tr>>' +
                           '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                    "buttons": [
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
                    ],
                    "columnDefs": [
                        {
                            "targets": -1, // Última columna (acciones)
                            "orderable": false // No permitir ordenar
                        }
                    ]
                });
            } else {
                console.error("La tabla con ID 'payments-table' no existe en el DOM.");
            }
        });

        // Auto-cerrar alertas después de 5 segundos
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    </script>
@stop
