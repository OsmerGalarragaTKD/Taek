@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
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
                                    @case('Planned')
                                        <span class="badge bg-warning">Planificado</span>
                                        @break
                                    @case('Active')
                                        <span class="badge bg-primary">Activo</span>
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
                            <p class="mb-0">{{ $pago->payment_type }}</p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Método de Pago</h6>
                            <p class="mb-0">{{ $pago->payment_method }}</p>
                        </div>

                        <div class="col-12">
                            <h6 class="text-muted mb-1">Número de Referencia</h6>
                            <p class="mb-0">{{ $pago->reference_number }}</p>
                        </div>

                        <div class="col-12">
                            <h6 class="text-muted mb-1">URL del Recibo</h6>
                            <p class="mb-0">
                                <a href="{{ $pago->receipt_url }}" target="_blank">
                                    {{ $pago->receipt_url }}
                                </a>
                            </p>
                        </div>

                        <div class="col-12">
                            <h6 class="text-muted mb-1">Notas</h6>
                            <p class="mb-0">{{ $pago->notes }}</p>
                        </div>
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
            <form action="{{ route('payments.update', $pago->id) }}" method="POST">
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
                                    <option value="Monthly" {{ $pago->payment_type == 'Monthly' ? 'selected' : '' }}>Mensualidad</option>
                                    <option value="Event" {{ $pago->payment_type == 'Event' ? 'selected' : '' }}>Evento</option>
                                    <option value="Other" {{ $pago->payment_type == 'Other' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Método de Pago</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="Cash" {{ $pago->payment_method == 'Cash' ? 'selected' : '' }}>Efectivo</option>
                                    <option value="Transfer" {{ $pago->payment_method == 'Transfer' ? 'selected' : '' }}>Transferencia</option>
                                    <option value="Card" {{ $pago->payment_method == 'Card' ? 'selected' : '' }}>Tarjeta</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Estado</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Planned" {{ $pago->status == 'Planned' ? 'selected' : '' }}>Planificado</option>
                            <option value="Active" {{ $pago->status == 'Active' ? 'selected' : '' }}>Activo</option>
                            <option value="Completed" {{ $pago->status == 'Completed' ? 'selected' : '' }}>Completado</option>
                            <option value="Cancelled" {{ $pago->status == 'Cancelled' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="reference_number" class="form-label">Número de Referencia</label>
                        <input type="text" class="form-control" 
                               id="reference_number" name="reference_number" 
                               value="{{ $pago->reference_number }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="receipt_url" class="form-label">URL del Recibo</label>
                        <input type="url" class="form-control" 
                               id="receipt_url" name="receipt_url" 
                               value="{{ $pago->receipt_url }}" required>
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
@endsection