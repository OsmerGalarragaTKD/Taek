@extends('layouts.app')

@section('title', 'Crear Pago')

@section('content')
    <div class="container">
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

                <form action="{{ route('payments.store') }}" method="POST" id="paymentForm">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="athlete_id" class="form-label">Atleta <span class="text-danger">*</span></label>
                                <select name="athlete_id" id="athlete_id"
                                    class="form-select @error('athlete_id') is-invalid @enderror" required>
                                    <option value="">Seleccione un atleta...</option>
                                    @foreach ($athletes as $athlete)
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
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_type" class="form-label">Tipo de Pago <span
                                        class="text-danger">*</span></label>
                                <select name="payment_type" id="payment_type"
                                    class="form-select @error('payment_type') is-invalid @enderror" required>
                                    <option value="">Seleccione tipo de pago...</option>
                                    <option value="Monthly_Fee"
                                        {{ old('payment_type') == 'Monthly_Fee' ? 'selected' : '' }}>
                                        Mensualidad
                                    </option>
                                    <option value="Event_Registration"
                                        {{ old('payment_type') == 'Event_Registration' ? 'selected' : '' }}>
                                        Registro de Evento
                                    </option>
                                    <option value="Equipment" {{ old('payment_type') == 'Equipment' ? 'selected' : '' }}>
                                        Equipamiento
                                    </option>
                                </select>
                                @error('payment_type')
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
                                            {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                            {{ $event->name }}
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
                                <label for="payment_date" class="form-label">Fecha de Pago <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="payment_date" id="payment_date"
                                    class="form-control @error('payment_date') is-invalid @enderror"
                                    value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                @error('payment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-end">
                            <a href="{{ route('payments.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Registrar Pago</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentTypeSelect = document.getElementById('payment_type');
            const eventSection = document.getElementById('event_section');
            const monthSection = document.getElementById('month_section');
            const eventSelect = document.getElementById('event_id');
            const monthInput = document.getElementById('month');
            const athleteSelect = document.getElementById('athlete_id');
            const amountInput = document.getElementById('amount');

            function handlePaymentTypeChange() {
                const selectedType = paymentTypeSelect.value;

                // Ocultar todas las secciones primero
                eventSection.style.display = 'none';
                monthSection.style.display = 'none';
                eventSelect.required = false;
                monthInput.required = false;

                // Mostrar sección correspondiente según el tipo de pago
                if (selectedType === 'Event_Registration') {
                    eventSection.style.display = 'block';
                    eventSelect.required = true;
                    loadAvailableEvents();
                } else if (selectedType === 'Monthly_Fee') {
                    monthSection.style.display = 'block';
                    monthInput.required = true;
                }
            }

            async function loadAvailableEvents() {
                const athleteId = athleteSelect.value;
                if (!athleteId) return;

                try {
                    const response = await fetch(`/api/athletes/${athleteId}/available-events`);
                    const data = await response.json();

                    eventSelect.innerHTML = '<option value="">Seleccione un evento...</option>';

                    if (data.status === 'success' && data.data.length > 0) {
                        data.data.forEach(event => {
                            const option = document.createElement('option');
                            option.value = event.id;
                            option.textContent = event.name;
                            option.dataset.cost = event.registration_fee;
                            eventSelect.appendChild(option);
                        });
                    } else {
                        eventSelect.innerHTML +=
                        '<option value="" disabled>No hay eventos disponibles</option>';
                    }
                } catch (error) {
                    console.error('Error cargando eventos:', error);
                    eventSelect.innerHTML = '<option value="">Error al cargar eventos</option>';
                }
            }

            function handleEventChange() {
                const selectedOption = eventSelect.options[eventSelect.selectedIndex];
                if (selectedOption && selectedOption.dataset.cost) {
                    amountInput.value = selectedOption.dataset.cost;
                    amountInput.readOnly = true;
                } else {
                    amountInput.readOnly = false;
                }
            }

            // Event Listeners
            paymentTypeSelect.addEventListener('change', handlePaymentTypeChange);
            athleteSelect.addEventListener('change', function() {
                if (paymentTypeSelect.value === 'Event_Registration') {
                    loadAvailableEvents();
                }
            });
            eventSelect.addEventListener('change', handleEventChange);

            // Inicialización
            if (paymentTypeSelect.value) {
                handlePaymentTypeChange();
            }
        });
    </script>
@endsection
