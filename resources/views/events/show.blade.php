@extends('layouts.app')

@section('title', 'Detalles del Evento')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detalles del Evento: {{ $event->name }}</h1>
        <div>
        @can('editar_eventos')
            <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#editEventModal">
                <i class="fas fa-edit mr-1"></i>
                Editar Evento
            </button>
            @endcan
            <a href="{{ route('events.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i>
                Volver
            </a>
        </div>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información del Evento</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl>
                                <dt>Nombre del Evento</dt>
                                <dd>{{ $event->name }}</dd>

                                <dt>Tipo de Evento</dt>
                                <dd>{{ str_replace('_', ' ', $event->type) }}</dd>

                                <dt>Sede</dt>
                                <dd>{{ $event->venue->name ?? 'No asignada' }}</dd>

                                <dt>Fecha de Inicio</dt>
                                <dd>{{ $event->start_date ? $event->start_date->format('d/m/Y') : 'No definida' }}</dd>

                                <dt>Fecha de Fin</dt>
                                <dd>{{ $event->end_date ? $event->end_date->format('d/m/Y') : 'No definida' }}</dd>

                                <dt>Fecha Límite de Inscripción</dt>
                                <dd>{{ $event->registration_deadline ? $event->registration_deadline->format('d/m/Y') : 'No definida' }}
                                </dd>

                                <dt>Descripción</dt>
                                <dd>{{ $event->description ?? 'Sin descripción' }}</dd>

                                <dt>Estado</dt>
                                <dd>
                                    <span
                                        class="badge badge-{{ $event->status === 'Active'
                                            ? 'success'
                                            : ($event->status === 'Planned'
                                                ? 'info'
                                                : ($event->status === 'Completed'
                                                    ? 'secondary'
                                                    : 'danger')) }}">
                                        {{ $event->status }}
                                    </span>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Pagos Asociados</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Atleta</th>
                                    <th>Tipo de Pago</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                    <th>Fecha de Pago</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($event->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->athlete->full_name }}</td>
                                        <td>{{ $payment->payment_type }}</td>
                                        <td>{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ $payment->status }}</td>
                                        <td>{{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : 'No definida' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No hay pagos asociados a este evento.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Categorías del Evento -->
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Categorías del Evento</h3>
            @can('editar_eventos')
            <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal"
                data-target="#editCategoriesModal">
                <i class="fas fa-edit"></i> Editar Categorías
            </button>
            @endcan
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th>Tarifa de Registro</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($event->eventCategories as $eventCategory)
                        <tr>
                            <td>{{ $eventCategory->category->name . ' - ' . $eventCategory->category->type }}</td>
                            <td>{{ number_format($eventCategory->registration_fee, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">No hay categorías asociadas a este evento.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para Editar Evento -->
    <div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEventModalLabel">Editar Información del Evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('events.update', $event->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre del Evento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $event->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="type">Tipo de Evento <span class="text-danger">*</span></label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="Competition" {{ $event->type == 'Competition' ? 'selected' : '' }}>Competición</option>
                                <option value="Promotion_Test" {{ $event->type == 'Promotion_Test' ? 'selected' : '' }}>Examen de Promoción</option>
                                <option value="Training" {{ $event->type == 'Training' ? 'selected' : '' }}>Entrenamiento</option>
                                <option value="Other" {{ $event->type == 'Other' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="venue_id">Sede</label>
                            <select class="form-control" id="venue_id" name="venue_id">
                                <option value="">Seleccionar sede...</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" {{ $event->venue_id == $venue->id ? 'selected' : '' }}>
                                        {{ $venue->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date">Fecha de Inicio</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" 
                                           value="{{ $event->start_date ? $event->start_date->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date">Fecha de Fin</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" 
                                           value="{{ $event->end_date ? $event->end_date->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="registration_deadline">Fecha Límite de Inscripción</label>
                                    <input type="date" class="form-control" id="registration_deadline" name="registration_deadline" 
                                           value="{{ $event->registration_deadline ? $event->registration_deadline->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ $event->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="status">Estado <span class="text-danger">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="Planned" {{ $event->status == 'Planned' ? 'selected' : '' }}>Planificado</option>
                                <option value="Active" {{ $event->status == 'Active' ? 'selected' : '' }}>Activo</option>
                                <option value="Completed" {{ $event->status == 'Completed' ? 'selected' : '' }}>Completado</option>
                                <option value="Cancelled" {{ $event->status == 'Cancelled' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Categorías -->
    <div class="modal fade" id="editCategoriesModal" tabindex="-1" role="dialog"
        aria-labelledby="editCategoriesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoriesModalLabel">Editar Categorías del Evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('events.update-categories', $event->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div id="categories-container">
                            @foreach ($event->eventCategories as $index => $eventCategory)
                                <div class="category-row mb-3">
                                    <div class="form-group">
                                        <label for="category_id_{{ $index }}">Categoría</label>
                                        <select name="categories[{{ $index }}][category_id]" class="form-control"
                                            required>
                                            <option value="">Seleccionar categoría...</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $eventCategory->category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name . ' - ' . $category->type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="registration_fee_{{ $index }}">Tarifa de Registro</label>
                                        <input type="number" name="categories[{{ $index }}][registration_fee]"
                                            class="form-control" value="{{ $eventCategory->registration_fee }}"
                                            step="0.01" required>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" id="add-category" class="btn btn-secondary">
                            <i class="fas fa-plus"></i> Agregar Categoría
                        </button>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let categoryIndex = {{ $event->eventCategories->count() }};

            // Agregar una nueva fila de categoría
            document.getElementById('add-category').addEventListener('click', function() {
                const container = document.getElementById('categories-container');
                const newRow = document.createElement('div');
                newRow.classList.add('category-row', 'mb-3');

                newRow.innerHTML = `
                <div class="form-group">
                    <label for="category_id_${categoryIndex}">Categoría</label>
                    <select name="categories[${categoryIndex}][category_id]" class="form-control" required>
                        <option value="">Seleccionar categoría...</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name . ' - ' . $category->type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="registration_fee_${categoryIndex}">Tarifa de Registro</label>
                    <input type="number" name="categories[${categoryIndex}][registration_fee]" class="form-control" step="0.01" required>
                </div>
            `;

                container.appendChild(newRow);
                categoryIndex++;
            });
        });
    </script>
@stop