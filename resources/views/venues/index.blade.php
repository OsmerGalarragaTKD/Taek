@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-0">
                                    <i class="bi bi-building me-2"></i>
                                    Listado de Sedes
                                </h5>
                            </div>
                            <div class="col text-end">
                            @can('crear_sedes') 
                                <a href="{{ route('venues.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Nueva Sede
                                </a>
                            @endcan
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Botones de exportación explícitos -->
                        <div class="export-buttons mb-3">
                            <button class="btn btn-success btn-sm export-excel">
                                <i class="fas fa-file-excel mr-1"></i> Excel
                            </button>
                            <button class="btn btn-danger btn-sm export-pdf">
                                <i class="fas fa-file-pdf mr-1"></i> PDF
                            </button>
                            <button class="btn btn-info btn-sm export-print">
                                <i class="fas fa-print mr-1"></i> Imprimir
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table id="venuesTable" class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="px-4">Nombre</th>
                                        <th scope="col">Director</th>
                                        <th scope="col">Ciudad</th>
                                        <th scope="col">Teléfono</th>
                                        <th scope="col">Email</th>
                                        <th scope="col" class="text-center">Estado</th>
                                        <th scope="col" class="text-end px-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($venues as $venue)
                                        <tr>
                                            <td class="px-4">{{ $venue->name }}</td>
                                            <td>{{ $venue->director_name ?? 'No asignado' }}</td>
                                            <td>{{ $venue->address_city ?? 'No especificada' }}</td>
                                            <td>{{ $venue->phone ?? 'No registrado' }}</td>
                                            <td>{{ $venue->email ?? 'No registrado' }}</td>
                                            <td class="text-center">
                                                @if ($venue->status == 'Active')
                                                    <span class="badge bg-success">Activo</span>
                                                @else
                                                    <span class="badge bg-danger">Inactivo</span>
                                                @endif
                                            </td>
                                            <td class="text-end px-4">
                                                <a href="{{ route('venues.show', $venue->id) }}"
                                                    class="btn btn-sm btn-info me-2">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <form action="{{ route('venues.destroy', $venue->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    @can('eliminar_sedes')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('¿Estás seguro de eliminar esta sede?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                    @endcan
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="bi bi-building-x text-muted" style="font-size: 2rem;"></i>
                                                    <p class="mb-0 mt-2">No hay sedes registradas</p>
                                                    
                                                    <a href="{{ route('venues.create') }}"
                                                        class="btn btn-sm btn-primary mt-3">
                                                        <i class="bi bi-plus-circle me-1"></i>
                                                        Crear Nueva Sede
                                                    </a>
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <!-- Font Awesome for export buttons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/buttons.bootstrap4.min.css') }}">
        
        <style>
            .table> :not(caption)>*>* {
                padding: 1rem 0.5rem;
            }

            .pagination {
                margin-bottom: 0;
            }
            
            /* Estilos para los botones de exportación */
            .export-buttons {
                display: flex;
                gap: 0.5rem;
            }
            
            .export-buttons .btn {
                display: flex;
                align-items: center;
                gap: 0.25rem;
            }
            
            /* Ocultar los botones generados por DataTables */
            .dt-buttons {
                display: none !important;
            }
            
            /* Estilo para los iconos en los botones */
            .fas {
                margin-right: 5px;
            }
        </style>
    @endpush

    @push('js')
        <!-- DataTables JS -->
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
                // Inicializar DataTable
                var table = $('#venuesTable').DataTable({
                    buttons: [
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            },
                            title: 'Listado de Sedes'
                        },
                        {
                            extend: 'pdf',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            },
                            title: 'Listado de Sedes'
                        },
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            },
                            title: 'Listado de Sedes'
                        }
                    ],
                    "pageLength": 25,
                    "order": [[0, 'asc']]
                });
                
                // Conectar botones personalizados con las funciones de DataTables
                $('.export-excel').on('click', function() {
                    table.button('.buttons-excel').trigger();
                });
                
                $('.export-pdf').on('click', function() {
                    table.button('.buttons-pdf').trigger();
                });
                
                $('.export-print').on('click', function() {
                    table.button('.buttons-print').trigger();
                });

                // Auto-cerrar alertas después de 5 segundos
                window.setTimeout(function() {
                    document.querySelectorAll(".alert").forEach(function(alert) {
                        var bsAlert = new bootstrap.Alert(alert);
                        setTimeout(function() {
                            bsAlert.close();
                        }, 5000);
                    });
                }, 1000);
            });
        </script>
    @endpush
@endsection