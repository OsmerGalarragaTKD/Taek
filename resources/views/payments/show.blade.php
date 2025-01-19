@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
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
                                <i class="bi bi-cash me-2"></i>
                                Detalles del Pago
                            </h5>
                        </div>
                        <div class="col text-end">
                            @if($pago->status === 'Pending')
                                <form action="{{ route('payments.approve.single', $pago->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success me-2">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Aprobar
                                    </button>
                                </form>
                            @endif
                            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editPaymentModal">
                                <i class="bi bi-pencil me-1"></i>
                                Editar
                            </button>
                            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Información del Atleta</h6>
                            <p class="h5 mb-0">{{ $pago->athlete->full_name }}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Monto</h6>
                            <p class="h5 mb-0">${{ number_format($pago->amount, 2) }}</p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Fecha de Pago</h6>
                            <p class="mb-0">{{ $pago->payment_date->format('d/m/Y') }}</p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Estado</h6>
                            <p class="mb-0">
                                @switch($pago->status)
                                    @case('Pending')
                                        <span class="badge bg-warning">Pendiente</span>
                                        @break
                                    @case('Completed')
                                        <span class="badge bg-success">Completado</span>
                                        @break
                                    @case('Cancelled')
                                        <span class="badge bg-danger">Cancelado</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $pago->status }}</span>
                                @endswitch
                            </p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Tipo de Pago</h6>
                            <p class="mb-0">
                                @switch($pago->payment_type)
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
                                        {{ $pago->payment_type }}
                                @endswitch
                            </p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Método de Pago</h6>
                            <p class="mb-0">
                                @switch($pago->payment_method)
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
                                        {{ $pago->payment_method }}
                                @endswitch
                            </p>
                        </div>

                        <div class="col-12">
                            <h6 class="text-muted mb-1">Número de Referencia</h6>
                            <p class="mb-0">{{ $pago->reference_number ?? 'No proporcionado' }}</p>
                        </div>

                        <div class="col-12">
                            <h6 class="text-muted mb-1">Comprobante de Pago</h6>
                            @if($pago->receipt_url)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($pago->receipt_url) }}" 
                                         alt="Comprobante de pago" 
                                         class="img-fluid rounded"
                                         style="max-height: 300px;">
                                    <div class="mt-2">
                                        <a href="{{ Storage::url($pago->receipt_url) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           target="_blank">
                                            <i class="bi bi-eye me-1"></i>
                                            Ver imagen completa
                                        </a>
                                    </div>
                                </div>
                            @else
                                <p class="mb-0">No se ha cargado ningún comprobante</p>
                            @endif
                        </div>

                        @if($pago->notes)
                            <div class="col-12">
                                <h6 class="text-muted mb-1">Notas</h6>
                                <p class="mb-0">{{ $pago->notes }}</p>
                            </div>
                        @endif

                        @if($pago->completed_at)
                            <div class="col-12">
                                <h6 class="text-muted mb-1">Fecha de Aprobación</h6>
                                <p class="mb-0">{{ $pago->completed_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edición -->
<div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPaymentModalLabel">Editar Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('payments.update', $pago->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Monto</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control" 
                                   id="amount" name="amount" value="{{ $pago->amount }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="payment_date" class="form-label">Fecha de Pago</label>
                        <input type="date" class="form-control" 
                               id="payment_date" name="payment_date" 
                               value="{{ $pago->payment_date->format('Y-m-d') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_type" class="form-label">Tipo de Pago</label>
                                <select class="form-select" id="payment_type" name="payment_type" required>
                                    <option value="Monthly_Fee" {{ $pago->payment_type == 'Monthly_Fee' ? 'selected' : '' }}>Mensualidad</option>
                                    <option value="Event_Registration" {{ $pago->payment_type == 'Event_Registration' ? 'selected' : '' }}>Evento</option>
                                    <option value="Equipment" {{ $pago->payment_type == 'Equipment' ? 'selected' : '' }}>Equipo</option>
                                    <option value="Other" {{ $pago->payment_type == 'Other' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Método de Pago</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="Transfer" {{ $pago->payment_method == 'Transfer' ? 'selected' : '' }}>Transferencia</option>
                                    <option value="Card" {{ $pago->payment_method == 'Card' ? 'selected' : '' }}>Tarjeta</option>
                                    <option value="Cash" {{ $pago->payment_method == 'Cash' ? 'selected' : '' }}>Efectivo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Estado</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Pending" {{ $pago->status == 'Pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="Completed" {{ $pago->status == 'Completed' ? 'selected' : '' }}>Completado</option>
                            <option value="Cancelled" {{ $pago->status == 'Cancelled' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="reference_number" class="form-label">Número de Referencia</label>
                        <input type="text" class="form-control" 
                               id="reference_number" name="reference_number" 
                               value="{{ $pago->reference_number }}">
                    </div>

                    <div class="mb-3">
                        <label for="receipt_url" class="form-label">Comprobante de Pago</label>
                        @if($pago->receipt_url)
                            <div class="mb-2">
                                <img src="{{ Storage::url($pago->receipt_url) }}" 
                                     alt="Comprobante actual" 
                                     class="img-thumbnail"
                                     style="max-height: 100px;">
                            </div>
                        @endif
                        <input type="file" class="form-control" 
                               id="receipt_url" name="receipt_url" 
                               accept="image/*">
                        <div class="form-text">Formatos permitidos: JPG, JPEG, PNG. Tamaño máximo: 2MB</div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ $pago->notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
    // Auto-cerrar alertas después de 5 segundos
    window.setTimeout(function() {
        document.querySelectorAll(".alert").forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            setTimeout(function() {
                bsAlert.close();
            }, 5000);
        });
    }, 1000);
</script>
@endpush
@endsection