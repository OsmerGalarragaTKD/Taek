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
                                <i class="bi bi-clock-history me-2"></i>
                                Documentos Pendientes
                            </h5>
                        </div>
                        <div class="col text-end">
                            <button type="button" id="approveSelected" class="btn btn-success" disabled>
                                <i class="bi bi-check-all me-1"></i>
                                Aprobar Seleccionados
                            </button>
                        </div>
                    </div>
                </div>

                <form id="bulkApprovalForm" action="{{ route('documents.approve.bulk') }}" method="POST">
                    @csrf
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="pendingDocumentsTable" class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                                <label class="form-check-label" for="selectAll">
                                                    Todos
                                                </label>
                                            </div>
                                        </th>
                                        <th>Atleta</th>
                                        <th>Tipo</th>
                                        <th>Evento</th>
                                        <th>Fecha Solicitud</th>
                                        <th class="text-end px-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingDocuments as $document)
                                        <tr>
                                            <td class="px-4">
                                                <div class="form-check">
                                                    <input class="form-check-input document-checkbox" 
                                                           type="checkbox" 
                                                           name="document_ids[]" 
                                                           value="{{ $document->id }}">
                                                </div>
                                            </td>
                                            <td>{{ $document->athlete->full_name }}</td>
                                            <td>{{ $document->template->name }}</td>
                                            <td>{{ $document->event->name ?? 'N/A' }}</td>
                                            <td>{{ $document->created_at->format('d/m/Y') }}</td>
                                            <td class="text-end px-4">
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
                                                    <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                                    <p class="mb-0 mt-2">No hay documentos pendientes</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
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
    // Inicializar DataTable
    $('#pendingDocumentsTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        },
        "order": [[4, 'desc']],
        "columnDefs": [
            {
                "targets": [0, 5],
                "orderable": false
            }
        ]
    });

    // Manejar el checkbox "Seleccionar Todo"
    $('#selectAll').change(function() {
        $('.document-checkbox').prop('checked', $(this).prop('checked'));
        updateApproveButton();
    });

    // Manejar los checkboxes individuales
    $('.document-checkbox').change(function() {
        updateApproveButton();
        
        // Actualizar el estado del checkbox "Seleccionar Todo"
        if ($('.document-checkbox:checked').length === $('.document-checkbox').length) {
            $('#selectAll').prop('checked', true);
        } else {
            $('#selectAll').prop('checked', false);
        }
    });

    // Actualizar el estado del botón de aprobación masiva
    function updateApproveButton() {
        const checkedCount = $('.document-checkbox:checked').length;
        $('#approveSelected').prop('disabled', checkedCount === 0);
        
        // Actualizar el texto del botón
        if (checkedCount > 0) {
            $('#approveSelected').html(`
                <i class="bi bi-check-all me-1"></i>
                Aprobar Seleccionados (${checkedCount})
            `);
        } else {
            $('#approveSelected').html(`
                <i class="bi bi-check-all me-1"></i>
                Aprobar Seleccionados
            `);
        }
    }

    // Manejar el click en el botón de aprobación masiva
    $('#approveSelected').click(function() {
        const checkedCount = $('.document-checkbox:checked').length;
        if (confirm(`¿Está seguro de aprobar ${checkedCount} documento${checkedCount !== 1 ? 's' : ''}?`)) {
            $('#bulkApprovalForm').submit();
        }
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