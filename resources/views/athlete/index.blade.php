@extends('layouts.app')

@section('title', 'Listado de Atletas')

@section('content_header')
    <h1>Listado de Atletas</h1>
@stop

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
                                    <i class="bi bi-people-fill me-2"></i>
                                    Listado de Atletas
                                </h5>
                            </div>
                            <div class="col text-end">
                            @can('crear_atletas') 
                                <a href="{{ route('athlete.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Nuevo Atleta
                                </a>
                            @endcan
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Botones de exportación explícitos -->
                        <div class="export-buttons mb-3">
                            <button id="export-excel" class="btn btn-success btn-sm">
                                <i class="fas fa-file-excel mr-1"></i> Excel
                            </button>
                            <button id="export-pdf" class="btn btn-danger btn-sm">
                                <i class="fas fa-file-pdf mr-1"></i> PDF
                            </button>
                            <button id="export-print" class="btn btn-info btn-sm">
                                <i class="fas fa-print mr-1"></i> Imprimir
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table id="athleteTable" class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-4">Nombre</th>
                                        <th scope="col">Documento</th>
                                        <th scope="col">Edad</th>
                                        <th scope="col">Grado</th>
                                        <th scope="col">Sede</th>
                                        <th scope="col">Teléfono</th>
                                        <th scope="col" class="text-center">Estado</th>
                                        <th scope="col" class="text-end px-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($athletes as $athlete)
                                        <tr>
                                            <td class="px-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3">
                                                        @if ($athlete->gender == 'M')
                                                            <i class="bi bi-person-circle text-primary"
                                                                style="font-size: 1.5rem;"></i>
                                                        @else
                                                            <i class="bi bi-person-circle text-pink"
                                                                style="font-size: 1.5rem;"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 fw-medium">{{ $athlete->full_name }}</p>
                                                        <small class="text-muted">
                                                            {{ $athlete->nationality ?? 'No especificada' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $athlete->identity_document ?? 'No registrado' }}</td>
                                            <td>
                                                @if ($athlete->birth_date)
                                                    {{ $athlete->birth_date->age }} años
                                                @else
                                                    N/A
                                                @endif
                                            </td>

                                            <!-- Columna de Cinturón sin color -->
                                            <td>
                                                @if ($athlete->currentGrade && $athlete->currentGrade->grade)
                                                    <span class="badge-grade">
                                                        {{ $athlete->currentGrade->grade->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($athlete->venue_id)
                                                    {{ $athlete->venue->name }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $athlete->phone ?? 'No registrado' }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('athlete.toggle-status', $athlete->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="btn btn-sm {{ $athlete->status === 'Active' ? 'btn-success' : ($athlete->status === 'Inactive' ? 'btn-danger' : 'btn-warning') }}"
                                                        title="Click para cambiar estado">
                                                        {{ $athlete->status }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-end px-4">
                                                <a href="{{ route('athlete.show', $athlete->id) }}"
                                                    class="btn btn-sm btn-info me-2">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('athlete.print-constancy', $athlete->id) }}"
                                                    class="btn btn-sm btn-secondary me-2" target="_blank">
                                                    <i class="bi bi-printer"></i>
                                                </a>
                                                <form action="{{ route('athlete.destroy', $athlete->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    @can('eliminar_atletas') 
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('¿Estás seguro de eliminar este registro?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                    @endcan
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                                    <p class="mb-0 mt-2">No hay atletas registrados</p>
                                                    <a href="{{ route('athlete.create') }}"
                                                        class="btn btn-sm btn-primary mt-3">
                                                        <i class="bi bi-plus-circle me-1"></i>
                                                        Crear Nuevo Atleta
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
        <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{ asset('css/buttons.bootstrap4.min.css')}}">
        
        <style>
            .avatar-sm {
                width: 2rem;
                height: 2rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .table> :not(caption)>*>* {
                padding: 1rem 0.5rem;
            }

            .pagination {
                margin-bottom: 0;
            }

            .badge-grade {
                padding: 0.25rem 0.5rem;
                border-radius: 0.25rem;
                font-size: 0.85em;
                font-weight: 500;
                display: inline-block;
                min-width: 80px;
                text-align: center;
                text-transform: uppercase;
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
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
            
            /* Estilo para el texto-rosa */
            .text-pink {
                color: #e83e8c;
            }
            
            /* Ocultar los botones generados por DataTables */
            .dt-buttons {
                display: none !important;
            }
        </style>
    @endpush

    @push('js')
        <!-- jsPDF -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
        <!-- AutoTable plugin for jsPDF -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
        
        <script>
            // Función para imprimir
            function printTable() {
                var table = document.getElementById('athleteTable');
                var rows = table.rows;
                for (var i = 0; i < rows.length; i++) {
                    var cells = rows[i].cells;
                    cells[cells.length - 1].style.display = 'none';
                    cells[cells.length - 2].style.display = 'none';
                }
                var html = table.outerHTML;
                var printWindow = window.open('', '', 'height=500,width=800');
                printWindow.document.write(html);
                printWindow.document.close();
                printWindow.print();
                printWindow.close();
                // Restaurar la tabla a su estado original
                for (var i = 0; i < rows.length; i++) {
                    var cells = rows[i].cells;
                    cells[cells.length - 1].style.display = '';
                    cells[cells.length - 2].style.display = '';
                }
            }

            // Función para exportar a Excel
            function exportToExcel() {
                var table = document.getElementById('athleteTable');
                var rows = table.rows;
                for (var i = 0; i < rows.length; i++) {
                    var cells = rows[i].cells;
                    cells[cells.length - 1].style.display = 'none';
                    cells[cells.length - 2].style.display = 'none';
                }
                var html = table.outerHTML;
                var url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
                var a = document.createElement('a');
                a.href = url;
                a.download = 'listado_atletas.xls';
                a.click();
                // Restaurar la tabla a su estado original
                for (var i = 0; i < rows.length; i++) {
                    var cells = rows[i].cells;
                    cells[cells.length - 1].style.display = '';
                    cells[cells.length - 2].style.display = '';
                }
            }

            // Función para exportar a PDF
            function exportToPdf() {
                var { jsPDF } = window.jspdf;
                var doc = new jsPDF();
                doc.autoTable({ html: '#athleteTable' });
                doc.save('listado_atletas.pdf');
            }

            // Agregar eventos a los botones de exportación
            document.getElementById('export-excel').addEventListener('click', exportToExcel);
            document.getElementById('export-pdf').addEventListener('click', exportToPdf);
            document.getElementById('export-print').addEventListener('click', printTable);
        </script>
    @endpush
@endsection