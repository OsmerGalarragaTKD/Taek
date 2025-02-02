@extends('layouts.app')

@section('title', 'Crear Pago')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Registrar Nuevo Pago</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="athlete_id">Atleta</label>
                            <select name="athlete_id" id="athlete_id" class="form-control @error('athlete_id') is-invalid @enderror" required>
                                <option value="">Seleccione un atleta...</option>
                                @foreach($athletes as $athlete)
                                    <option value="{{ $athlete->id }}" {{ old('athlete_id') == $athlete->id ? 'selected' : '' }}>
                                        {{ $athlete->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('athlete_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_type">Tipo de Pago</label>
                            <select name="payment_type" id="payment_type" class="form-control @error('payment_type') is-invalid @enderror" required>
                                <option value="">Seleccione tipo de pago...</option>
                                <option value="Monthly" {{ old('payment_type') == 'Monthly' ? 'selected' : '' }}>Mensualidad</option>
                                <option value="Event_Registration" {{ old('payment_type') == 'Event_Registration' ? 'selected' : '' }}>Evento</option>
                                <option value="Other" {{ old('payment_type') == 'Other' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('payment_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3" id="event_section" style="display: none;">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="event_id">Evento</label>
                            <select name="event_id" id="event_id" class="form-control @error('event_id') is-invalid @enderror">
                                <option value="">Seleccione un evento...</option>
                            </select>
                            @error('event_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="amount">Monto</label>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                            @error('amount')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_date">Fecha de Pago</label>
                            <input type="date" name="payment_date" id="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                            @error('payment_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="status">Estado del Pago</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completado</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div> --}}

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Registrar Pago</button>
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
    const eventSelect = document.getElementById('event_id');
    const athleteSelect = document.getElementById('athlete_id');
    const amountInput = document.getElementById('amount');

    // Función para cargar eventos
    async function loadEvents(athleteId) {
        if (!athleteId) return;
        
        try {
            const response = await fetch(`/api/athletes/${athleteId}/available-events`);
            const result = await response.json();
            
            if (result.status === 'error') {
                console.error('Error del servidor:', result.message);
                return;
            }

            eventSelect.innerHTML = '<option value="">Seleccione un evento...</option>';
            
            if (result.data && result.data.length > 0) {
                result.data.forEach(event => {
                    // Agregamos el costo del evento como atributo data
                    eventSelect.innerHTML += `<option value="${event.id}" data-cost="${event.registration_fee}">${event.name}</option>`;
                });
            } else {
                eventSelect.innerHTML += '<option value="" disabled>No hay eventos disponibles</option>';
            }
        } catch (error) {
            console.error('Error cargando eventos:', error);
            eventSelect.innerHTML = '<option value="">Error al cargar eventos</option>';
        }
    }

    // Función para manejar el cambio de evento
    function handleEventChange() {
        const selectedOption = eventSelect.options[eventSelect.selectedIndex];
        if (selectedOption && selectedOption.dataset.cost) {
            amountInput.value = selectedOption.dataset.cost;
            amountInput.readOnly = true;
        } else {
            amountInput.value = '';
            amountInput.readOnly = false;
        }
    }

    // Función para manejar el cambio de tipo de pago
    function handlePaymentTypeChange() {
        if (paymentTypeSelect.value === 'Event_Registration') {
            eventSection.style.display = 'block';
            eventSelect.required = true;
            if (athleteSelect.value) {
                loadEvents(athleteSelect.value);
            }
        } else {
            eventSection.style.display = 'none';
            eventSelect.required = false;
            amountInput.readOnly = false;
            amountInput.value = '';
        }
    }

    // Event Listeners
    paymentTypeSelect.addEventListener('change', handlePaymentTypeChange);
    
    athleteSelect.addEventListener('change', function() {
        if (paymentTypeSelect.value === 'Event_Registration') {
            loadEvents(this.value);
        }
    });

    eventSelect.addEventListener('change', handleEventChange);

    // Inicialización
    if (paymentTypeSelect.value === 'Event_Registration') {
        handlePaymentTypeChange();
    }
});
</script>
@endsection

