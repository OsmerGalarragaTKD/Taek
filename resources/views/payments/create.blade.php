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
                                Registrar Nuevo Pago
                            </h5>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('payments.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="athlete_id" class="form-label">Atleta</label>
                            <select class="form-select @error('athlete_id') is-invalid @enderror" 
                                    id="athlete_id" name="athlete_id" required>
                                <option value="">Seleccionar atleta...</option>
                                @foreach($athletes as $athlete)
                                    <option value="{{ $athlete->id }}" 
                                            {{ old('athlete_id') == $athlete->id ? 'selected' : '' }}>
                                        {{ $athlete->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('athlete_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Monto</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" value="{{ old('amount') }}" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Fecha de Pago</label>
                            <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                   id="payment_date" name="payment_date" value="{{ old('payment_date') }}" required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_type" class="form-label">Tipo de Pago</label>
                                    <select class="form-select @error('payment_type') is-invalid @enderror" 
                                            id="payment_type" name="payment_type" required>
                                        <option value="">Seleccionar tipo...</option>
                                        <option value="Monthly_Fee" {{ old('payment_type') == 'Monthly_Fee' ? 'selected' : '' }}>Mensualidad</option>
                                        <option value="Event_Registration" {{ old('payment_type') == 'Event_Registration' ? 'selected' : '' }}>Evento</option>
                                        <option value="Equipment" {{ old('payment_type') == 'Equipment' ? 'selected' : '' }}>Equipo</option>
                                        <option value="Other" {{ old('payment_type') == 'Other' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    @error('payment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Método de Pago</label>
                                    <select class="form-select @error('payment_method') is-invalid @enderror" 
                                            id="payment_method" name="payment_method" required>
                                        <option value="">Seleccionar método...</option>
                                        <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Efectivo</option>
                                        <option value="Transfer" {{ old('payment_method') == 'Transfer' ? 'selected' : '' }}>Transferencia</option>
                                        <option value="Card" {{ old('payment_method') == 'Card' ? 'selected' : '' }}>Tarjeta</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reference_number" class="form-label">Número de Referencia</label>
                            <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                                   id="reference_number" name="reference_number" value="{{ old('reference_number') }}" >
                            @error('reference_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="receipt_url" class="form-label">URL del Recibo</label>
                            <input type="url" class="form-control @error('receipt_url') is-invalid @enderror" 
                                   id="receipt_url" name="receipt_url" value="{{ old('receipt_url') }}" >
                            @error('receipt_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notas</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>
                                Guardar Pago
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection