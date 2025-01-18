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
                                <i class="bi bi-calendar-plus me-2"></i>
                                Crear Nuevo Evento
                            </h5>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('events.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del Evento</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Tipo de Evento</label>
                            <select class="form-select @error('type') is-invalid @enderror" 
                                    id="type" name="type" required>
                                <option value="">Seleccionar tipo...</option>
                                <option value="Competition" {{ old('type') == 'Competition' ? 'selected' : '' }}>Competencia</option>
                                <option value="Promotion_Test" {{ old('type') == 'Promotion_Test' ? 'selected' : '' }}>Examen de Promoción</option>
                                <option value="Training" {{ old('type') == 'Training' ? 'selected' : '' }}>Entrenamiento</option>
                                <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="venue_id" class="form-label">Sede</label>
                            <select class="form-select @error('venue_id') is-invalid @enderror" 
                                    id="venue_id" name="venue_id">
                                <option value="">Seleccionar sede...</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" 
                                            {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                        {{ $venue->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('venue_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Fecha de Inicio</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Fecha de Fin</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" name="end_date" value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="registration_deadline" class="form-label">Fecha Límite de Inscripción</label>
                            <input type="date" class="form-control @error('registration_deadline') is-invalid @enderror" 
                                   id="registration_deadline" name="registration_deadline" 
                                   value="{{ old('registration_deadline') }}">
                            @error('registration_deadline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>
                                Guardar Evento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación de fechas
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const registrationDeadline = document.getElementById('registration_deadline');

    startDate.addEventListener('change', function() {
        endDate.min = this.value;
    });

    endDate.addEventListener('change', function() {
        startDate.max = this.value;
    });

    registrationDeadline.addEventListener('change', function() {
        if(startDate.value && this.value > startDate.value) {
            this.value = startDate.value;
            alert('La fecha límite de inscripción no puede ser posterior a la fecha de inicio del evento.');
        }
    });
});
</script>
@endpush