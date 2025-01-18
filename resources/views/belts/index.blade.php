@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
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
                                <i class="bi bi-award me-2"></i>
                                Listado de Grados
                            </h5>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('belts.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>
                                Nuevo Grado
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="beltsTable" class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="px-4">Nombre</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Nivel</th>
                                    <th scope="col">Color</th>
                                    <th scope="col" class="text-end px-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($belts as $belt)
                                    <tr>
                                        <td class="px-4">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <p class="mb-0 fw-medium">{{ $belt->name }}</p>
                                                    <small class="text-muted">
                                                        {{ $belt->description ?? 'Sin descripción' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $belt->type }}</td>
                                        <td>{{ $belt->level }}</td>
                                        <td>{{ $belt->color ?? 'No especificado' }}</td>
                                        <td class="text-end px-4">
                                            <a href="{{ route('belts.show', $belt->id) }}" class="btn btn-sm btn-info me-2">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form action="{{ route('belts.destroy', $belt->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('¿Estás seguro de eliminar este grado?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                                <p class="mb-0 mt-2">No hay grados registrados</p>
                                                <a href="{{ route('belts.create') }}" class="btn btn-sm btn-primary mt-3">
                                                    <i class="bi bi-plus-circle me-1"></i>
                                                    Crear Nuevo Grado
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

@push('js')
<script>
$(document).ready(function() {
    $('#beltsTable').DataTable({
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sSearch":         "Buscar:",
            "sUrl":           "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
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
        "pageLength": 10
    });
});
</script>
@endpush
@endsection