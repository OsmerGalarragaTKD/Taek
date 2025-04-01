@extends('adminlte::page')

@section('title', 'Registros de Eventos')

@section('content_header')
    <h1>Registros de Eventos</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Eventos Activos</h3>
        </div>
        <div class="card-body">
            <table id="activeEventsTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre del Evento</th>
                        <th>Tipo</th>
                        <th>Fecha Límite de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeEvents as $event)
                        <tr>
                            <td>{{ $event->name }}</td>
                            <td>{{ str_replace('_', ' ', $event->type == 'Promotion Test' ? 'Examen de Promocion' : 'Competicion') }}</td>
                            <td>{{ $event->registration_deadline->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('eventregistration.createEvent', $event) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus mr-1"></i> Registrar Atleta
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay eventos activos en este momento.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Registros Existentes</h3>
        </div>
        <div class="card-body">
            <table id="registrationsTable" class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Atleta</th>
                        <th>Evento</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Estado Pago</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $registration)
                        <tr>
                            <td>{{ $registration->athlete->full_name }}</td>
                            <td>{{ $registration->event->name }}</td>
                            <td>{{ $registration->category->name }}</td>
                            <td>{{ $registration->status === 'Registered' ? 'Registrado' : 'No Registrado' }}</td>
                            <td>{{ $registration->payment_status === 'Completed' ? 'Completado' : 'Pendiente' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No hay registros existentes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
<script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>    

@stop

@section('js')
    
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
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
            // Inicializar la tabla de eventos activos
            if ($('#activeEventsTable').length) {
                $('#activeEventsTable').DataTable({
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
                    "order": [[0, 'asc']],
                    "pageLength": 10,
                    "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                           '<"row"<"col-sm-12"tr>>' +
                           '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                    "columnDefs": [
                        {
                            "targets": -1, // Última columna (acciones)
                            "orderable": false // No permitir ordenar
                        }
                    ]
                });
            } else {
                console.error("La tabla con ID 'activeEventsTable' no existe en el DOM.");
            }

            // Inicializar la tabla de registros existentes
            if ($('#registrationsTable').length) {
                $('#registrationsTable').DataTable({
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
                    "order": [[0, 'asc']],
                    "pageLength": 10,
                    "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                           '<"row"<"col-sm-12"tr>>' +
                           '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                    "columnDefs": [
                        {
                            "targets": -1, // Última columna (acciones)
                            "orderable": false // No permitir ordenar
                        }
                    ]
                });
            } else {
                console.error("La tabla con ID 'registrationsTable' no existe en el DOM.");
            }
        });
    </script>
@stop