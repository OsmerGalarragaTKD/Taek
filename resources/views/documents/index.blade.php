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
                                <i class="bi bi-file-text me-2"></i>
                                Documentos y Constancias
                            </h5>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('documents.pending') }}" class="btn btn-warning me-2">
                                <i class="bi bi-clock-history me-1"></i>
                                Pendientes
                            </a>
                            <a href="{{ route('documents.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>
                                Nueva Solicitud
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="documentsTable" class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">Atleta</th>
                                    <th>Tipo</th>
                                    <th>Evento</th>
                                    <th>Fecha Solicitud</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-end px-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $document)
                                    <tr>
                                        <td class="px-4">{{ $document->athlete->full_name }}</td>
                                        <td>{{ $document->template->name }}</td>
                                        <td>{{ $document->event->name ?? 'N/A' }}</td>
                                        <td>{{ $document->created_at->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            @switch($document->status)
                                                @case('Pending')
                                                    <span class="badge bg-warning">Pendiente</span>
                                                    @break
                                                @case('Approved')
                                                    <span class="badge bg-success">Aprobado</span>
                                                    @break
                                                @case('Rejected')
                                                    <span class="badge bg-danger">Rechazado</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $document->status }}</span>
                                            @endswitch
                                        </td>
                                        <td class="text-end px-4">
                                            @if($document->status === 'Pending')
                                                <form action="{{ route('documents.approve.single', $document->id) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-success me-2" 
                                                            title="Aprobar Documento">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger me-2" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectModal{{ $document->id }}"
                                                        title="Rechazar Documento">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            @endif
                                            <a href="{{ route('documents.show', $document->id) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Ver Detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Modal de Rechazo -->
                                    <div class="modal fade" id="rejectModal{{ $document->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('documents.reject', $document->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Rechazar Documento</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="rejection_reason" class="form-label">Motivo del Rechazo</label>
                                                            <textarea class="form-control" 
                                                                      id="rejection_reason" 
                                                                      name="rejection_reason" 
                                                                      rows="3" 
                                                                      required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-danger">Rechazar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                                <p class="mb-0 mt-2">No hay documentos registrados</p>
                                                <a href="{{ route('documents.create') }}" class="btn btn-sm btn-primary mt-3">
                                                    <i class="bi bi-plus-circle me-1"></i>
                                                    Crear Nueva Solicitud
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
    $('#documentsTable').DataTable({
        "order": [[3, 'desc']],
        "columnDefs": [{
            "targets": -1,
            "orderable": false
        }]
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