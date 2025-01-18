@extends('layouts.app')

@section('content_header')
    <h1>Categorias</h1>
@stop

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-auto">
                            <h5 class="mb-0">
                                <i class="bi bi-folder me-2"></i>
                                Listado de Categorias
                            </h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>
                                Nueva Categoria
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="categoriesTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-4">Nombre</th>
                                    <th scope="col">Rango Edad</th>
                                    <th scope="col">Rango Peso</th>
                                    <th scope="col">Genero</th>
                                    <th scope="col" class="text-center">Estado</th>
                                    <th scope="col" class="text-end px-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td class="px-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    @if($category->gender == 'M')
                                                        <i class="bi bi-file-earmark text-primary" style="font-size: 1.5rem;"></i>
                                                    @else
                                                        <i class="bi bi-file-earmark text-pink" style="font-size: 1.5rem;"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="mb-0 fw-medium">{{ $category->name }}</p>
                                                    <small class="text-muted">
                                                        {{ $category->type ?? 'No especificado' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $category->min_age.' - '.$category->max_age ?? 'No registrado' }}</td>
                                        <td>{{ $category->min_weight.'Kg. '.' - '.$category->max_weight.'Kg. ' ?? 'No registrado' }}</td>
                                        <td>{{ $category->gender ?? 'No registrado' }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-success">Activo</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('categories.show', $category->id) }}" class="btn btn-sm btn-info me-2">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('¿Estás seguro de eliminar este registro?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                                <p class="mb-0 mt-2">No hay atletas registrados</p>
                                                <a href="{{ route('categories.create') }}" class="btn btn-sm btn-primary mt-3">
                                                    <i class="bi bi-plus-circle me-1"></i>
                                                    Crear Nueva Categoria
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
<style>
    .avatar-sm {
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .table > :not(caption) > * > * {
        padding: 1rem 0.5rem;
    }
    .pagination {
        margin-bottom: 0;
    }
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
    $('#categoriesTable').DataTable({
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":           "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
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