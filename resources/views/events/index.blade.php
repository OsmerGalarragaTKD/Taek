@extends('layouts.app')

@section('title', 'Eventos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Gestión de Eventos</h1>
        <a href="{{ route('events.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear Evento
        </a>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
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
                <table id="events-table" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Sede</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            <tr>
                                <td>{{ $event->name }}</td>
                                <td>{{ str_replace('_', ' ', $event->type) }}</td>
                                <td>{{ $event->venue->name ?? 'No asignada' }}</td>
                                <td>{{ $event->start_date ? $event->start_date->format('d/m/Y') : 'No definida' }}</td>
                                <td>{{ $event->end_date ? $event->end_date->format('d/m/Y') : 'No definida' }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $event->status === 'Active'
                                            ? 'success'
                                            : ($event->status === 'Planned'
                                                ? 'info'
                                                : ($event->status === 'Completed'
                                                    ? 'secondary'
                                                    : 'danger')) }}">
                                        {{ $event->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-info"
                                            title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('eliminar_eventos')

                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmarEliminacion('{{ $event->id }}')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endcan
                                    </div>
                                    <form id="delete-form-{{ $event->id }}"
                                        action="{{ route('events.destroy', $event->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
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
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
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
            $('#events-table').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel mr-1"></i> Excel',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf mr-1"></i> PDF',
                        className: 'btn btn-danger btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print mr-1"></i> Imprimir',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    }
                ],
            });
        });

        function confirmarEliminacion(eventId) {
            if (confirm('¿Está seguro que desea eliminar este evento?')) {
                document.getElementById('delete-form-' + eventId).submit();
            }
        }

        // Auto-cerrar alertas después de 5 segundos
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    </script>
@stop
