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
                                <i class="bi bi-calendar-event me-2"></i>
                                Detalles del Evento
                            </h5>
                        </div>
                        <div class="col text-end">
                            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editEventModal">
                                <i class="bi bi-pencil me-1"></i>
                                Editar
                            </button>
                            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Nombre del Evento</h6>
                            <p class="h5 mb-0">{{ $event->name }}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Tipo</h6>
                            <p class="mb-0">{{ str_replace('_', ' ', $event->type) }}</p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Sede</h6>
                            <p class="mb-0">{{ $event->venue->name ?? 'No asignada' }}</p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Estado</h6>
                            <p class="mb-0">
                                @switch($event->status)
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
                                        <span class="badge bg-secondary">{{ $event->status }}</span>
                                @endswitch
                            </p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Fecha de Inicio</h6>
                            <p class="mb-0">{{ $event->start_date ? $event->start_date->format('d/m/Y') : 'No definida' }}</p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Fecha de Fin</h6>
                            <p class="mb-0">{{ $event->end_date ? $event->end_date->format('d/m/Y') : 'No definida' }}</p>
                        </div>

                        <div class="col-12">
                            <h6 class="text-muted mb-1">Fecha Límite de Inscripción</h6>
                            <p class="mb-0">{{ $event->registration_deadline ? $event->registration_deadline->format('d/m/Y') : 'No definida' }}</p>
                        </div>

                        <div class="col-12">
                            <h6 class="text-muted mb-1">Descripción</h6>
                            <p class="mb-0">{{ $event->description ?? 'Sin descripción' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edición -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEventModalLabel">Editar Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('events.update', $event->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Evento</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ $event->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Tipo de Evento</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="Competition" {{ $event->type == 'Competition' ? 'selected' : '' }}>Competencia</option>
                            <option value="Promotion_Test" {{ $event->type == 'Promotion_Test' ? 'selected' : '' }}>Examen de Promoción</option>
                            <option value="Training" {{ $event->type == 'Training' ? 'selected' : '' }}>Entrenamiento</option>
                            <option value="Other" {{ $event->type == 'Other' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="venue_id" class="form-label">Sede</label>
                        <select class="form-select" id="venue_id" name="venue_id">
                            <option value="">Seleccionar sede...</option>
                            @foreach($venues as $venue)
                                <option value="{{ $venue->id }}" 
                                        {{ $event->venue_id == $venue->id ? 'selected' : '' }}>
                                    {{ $venue->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Fecha de Inicio</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ $event->start_date ? $event->start_date->format('Y-m-d') : '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Fecha de Fin</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ $event->end_date ? $event->end_date->format('Y-m-d') : '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="registration_deadline" class="form-label">Fecha Límite de Inscripción</label>
                        <input type="date" class="form-control" id="registration_deadline" name="registration_deadline" 
                               value="{{ $event->registration_deadline ? $event->registration_deadline->format('Y-m-d') : '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="3">{{ $event->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Estado</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Planned" {{ $event->status == 'Planned' ? 'selected' : '' }}>Planificado</option>
                            <option value="Active" {{ $event->status == 'Active' ? 'selected' : '' }}>Activo</option>
                            <option value="Completed" {{ $event->status == 'Completed' ? 'selected' : '' }}>Completado</option>
                            <option value="Cancelled" {{ $event->status == 'Cancelled' ? 'selected' : '' }}>Cancelado</option>
                        </select>
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
document.addEventListener('DOMContentLoaded', function() {
    // Validación de fechas en el modal
    const modalStartDate = document.querySelector('#editEventModal #start_date');
    const modalEndDate = document.querySelector('#editEventModal #end_date');
    const modalRegistrationDeadline = document.querySelector('#editEventModal #registration_deadline');

    modalStartDate.addEventListener('change', function() {
        modalEndDate.min = this.value;
    });

    modalEndDate.addEventListener('change', function() {
        modalStartDate.max = this.value;
    });

    modalRegistrationDeadline.addEventListener('change', function() {
        if(modalStartDate.value && this.value > modalStartDate.value) {
            this.value = modalStartDate.value;
            alert('La fecha límite de inscripción no puede ser posterior a la fecha de inicio del evento.');
        }
    });
});
</script>
@endpush
@endsection