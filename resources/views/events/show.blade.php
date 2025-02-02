@extends('layouts.app')

@section('title', 'Detalles del Evento')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detalles del Evento: {{ $event->name }}</h1>
        <a href="{{ route('events.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i>
            Volver
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
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
            <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal"
                data-target="#editCategoriesModal">
                <i class="fas fa-edit"></i> Editar Categorías
            </button>
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
