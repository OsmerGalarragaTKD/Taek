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
                                Pagos Pendientes
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

                <form id="bulkApprovalForm" action="{{ route('payments.approve.bulk') }}" method="POST">
                    @csrf
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="pendingPaymentsTable" class="table table-hover mb-0">
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
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Método</th>
                                        <th>Referencia</th>
                                        <th class="text-end px-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingPayments as $payment)
                                        <tr>
                                            <td class="px-4">
                                                <div class="form-check">
                                                    <input class="form-check-input payment-checkbox" 
                                                           type="checkbox" 
                                                           name="payment_ids[]" 
                                                           value="{{ $payment->id }}">
                                                </div>
                                            </td>
                                            <td>{{ $payment->athlete->full_name }}</td>
                                            <td>${{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                            <td>{{ str_replace('_', ' ', $payment->payment_type) }}</td>
                                            <td>{{ $payment->payment_method }}</td>
                                            <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                            <td class="text-end px-4">
                                                <form action="{{ route('payments.approve.single', $payment->id) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-success me-2" 
                                                            title="Aprobar Pago">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                                <a href="{{ route('payments.show', $payment->id) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Ver Detalles">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                                    <p class="mb-0 mt-2">No hay pagos pendientes</p>
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
    $('#pendingPaymentsTable').DataTable({
        "order": [[3, 'desc']],
        "columnDefs": [
            {
                "targets": [0, 7],
                "orderable": false
            }
        ]
    });

    // Manejar el checkbox "Seleccionar Todo"
    $('#selectAll').change(function() {
        $('.payment-checkbox').prop('checked', $(this).prop('checked'));
        updateApproveButton();
    });

    // Manejar los checkboxes individuales
    $('.payment-checkbox').change(function() {
        updateApproveButton();
        
        // Actualizar el estado del checkbox "Seleccionar Todo"
        if ($('.payment-checkbox:checked').length === $('.payment-checkbox').length) {
            $('#selectAll').prop('checked', true);
        } else {
            $('#selectAll').prop('checked', false);
        }
    });

    // Actualizar el estado del botón de aprobación masiva
    function updateApproveButton() {
        const checkedCount = $('.payment-checkbox:checked').length;
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
        const checkedCount = $('.payment-checkbox:checked').length;
        if (confirm(`¿Está seguro de aprobar ${checkedCount} pago${checkedCount !== 1 ? 's' : ''}?`)) {
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