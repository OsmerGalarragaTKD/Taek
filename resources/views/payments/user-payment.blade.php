@extends('layouts.app')

@section('title', 'Registrar Pago')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Registrar Pago</h1>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Registrar Nuevo Pago</h3>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('payments.user-store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount" class="form-label">Monto <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="amount" id="amount"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        value="{{ old('amount') }}" required>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_date" class="form-label">Fecha de Pago <span class="text-danger">*</span></label>
                                <input type="date" name="payment_date" id="payment_date"
                                    class="form-control @error('payment_date') is-invalid @enderror"
                                    value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                @error('payment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_type" class="form-label">Tipo de Pago <span class="text-danger">*</span></label>
                                <select name="payment_type" id="payment_type"
                                    class="form-select @error('payment_type') is-invalid @enderror" required>
                                    <option value="">Seleccione tipo de pago...</option>
                                    <option value="Monthly_Fee" {{ old('payment_type') == 'Monthly_Fee' ? 'selected' : '' }}>
                                        Mensualidad
                                    </option>
                                    <option value="Event_Registration" {{ old('payment_type') == 'Event_Registration' ? 'selected' : '' }}>
                                        Registro de Evento
                                    </option>
                                    <option value="Equipment" {{ old('payment_type') == 'Equipment' ? 'selected' : '' }}>
                                        Equipamiento
                                    </option>
                                    <option value="Other" {{ old('payment_type') == 'Other' ? 'selected' : '' }}>
                                        Otro
                                    </option>
                                </select>
                                @error('payment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_method" class="form-label">Método de Pago <span class="text-danger">*</span></label>
                                <select name="payment_method" id="payment_method"
                                    class="form-select @error('payment_method') is-invalid @enderror" required>
                                    <option value="">Seleccione método de pago...</option>
                                    <option value="Transfer" {{ old('payment_method') == 'Transfer' ? 'selected' : '' }}>
                                        Transferencia
                                    </option>
                                    <option value="Card" {{ old('payment_method') == 'Card' ? 'selected' : '' }}>
                                        Tarjeta
                                    </option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Evento - Se muestra solo cuando el tipo de pago es Event_Registration -->
                    <div class="row mb-3" id="event_section" style="display: none;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="event_id" class="form-label">Evento <span class="text-danger">*</span></label>
                                <select name="event_id" id="event_id"
                                    class="form-select @error('event_id') is-invalid @enderror">
                                    <option value="">Seleccione un evento...</option>
                                    @foreach ($events as $event)
                                        <option value="{{ $event->id }}"
                                            {{ old('event_id') == $event->id ? 'selected' : '' }}
                                            data-cost="{{ $event->registration_fee }}">
                                            {{ $event->name }} - ${{ number_format($event->registration_fee, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('event_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Mes - Se muestra solo cuando el tipo de pago es Monthly_Fee -->
                    <div class="row mb-3" id="month_section" style="display: none;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="month" class="form-label">Mes <span class="text-danger">*</span></label>
                                <input type="month" name="month" id="month"
                                    class="form-control @error('month') is-invalid @enderror" value="{{ old('month') }}">
                                @error('month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="reference_number" class="form-label">Número de Referencia <span class="text-danger">*</span></label>
                                <input type="text" name="reference_number" id="reference_number"
                                    class="form-control @error('reference_number') is-invalid @enderror"
                                    value="{{ old('reference_number') }}" required>
                                @error('reference_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="receipt_url" class="form-label">Comprobante de Pago <span class="text-danger">*</span></label>
                                <input type="file" name="receipt_url" id="receipt_url"
                                    class="form-control @error('receipt_url') is-invalid @enderror" accept="image/*" >
                                <small class="form-text text-muted">Formatos permitidos: JPG, JPEG, PNG. Tamaño máximo: 2MB</small>
                                @error('receipt_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes" class="form-label">Notas o Comentarios</label>
                                <textarea name="notes" id="notes"
                                    class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-end">
                            <a href="{{ route('home') }}" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Registrar Pago</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentTypeSelect = document.getElementById('payment_type');
        const eventSection = document.getElementById('event_section');
        const monthSection = document.getElementById('month_section');
        const eventSelect = document.getElementById('event_id');
        const amountInput = document.getElementById('amount');

        function handlePaymentTypeChange() {
            const selectedType = paymentTypeSelect.value;

            // Ocultar todas las secciones primero
            eventSection.style.display = 'none';
            monthSection.style.display = 'none';

            // Mostrar sección correspondiente según el tipo de pago
            if (selectedType === 'Event_Registration') {
                eventSection.style.display = 'block';
            } else if (selectedType === 'Monthly_Fee') {
                monthSection.style.display = 'block';
            }
        }

        function handleEventChange() {
            const selectedOption = eventSelect.options[eventSelect.selectedIndex];
            if (selectedOption && selectedOption.dataset.cost) {
                amountInput.value = selectedOption.dataset.cost;
                amountInput.readOnly = true; // Bloquear el campo para que no se edite manualmente
            } else {
                amountInput.readOnly = false;
            }
        }

        // Event Listeners
        paymentTypeSelect.addEventListener('change', handlePaymentTypeChange);
        eventSelect.addEventListener('change', handleEventChange);

        // Inicialización
        if (paymentTypeSelect.value) {
            handlePaymentTypeChange();
        }
    });
</script>
@endsection