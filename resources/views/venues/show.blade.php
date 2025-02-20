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
                                <i class="bi bi-building me-2"></i>
                                Detalles de la Sede
                            </h5>
                        </div>
                        <div class="col text-end">
                            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editVenueModal">
                                <i class="bi bi-pencil me-1"></i>
                                Editar
                            </button>
                            <a href="{{ route('venues.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Nombre de la Sede</h6>
                            <p class="h5 mb-0">{{ $venue->name }}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Estado</h6>
                            <p class="mb-0">
                                @if($venue->status == 'Active')
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </p>
                        </div>

                        <div class="col-12">
                            <h6 class="text-muted mb-1">Dirección Completa</h6>
                            <p class="mb-0">
                                {{ $venue->address_details }}
                                {{ $venue->address_parish ? ', ' . $venue->address_parish : '' }}
                                {{ $venue->address_city ? ', ' . $venue->address_city : '' }}
                                {{ $venue->address_state ? ', ' . $venue->address_state : '' }}
                            </p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Director</h6>
                            <p class="mb-0">{{ $venue->director_name ?? 'No asignado' }}</p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Fecha de Fundación</h6>
                            <p class="mb-0">{{ $venue->founding_date ? $venue->founding_date->format('d/m/Y') : 'No registrada' }}</p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Teléfono</h6>
                            <p class="mb-0">{{ $venue->phone ?? 'No registrado' }}</p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Email</h6>
                            <p class="mb-0">
                                @if($venue->email)
                                    <a href="mailto:{{ $venue->email }}">{{ $venue->email }}</a>
                                @else
                                    No registrado
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edición -->
<div class="modal fade" id="editVenueModal" tabindex="-1" aria-labelledby="editVenueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVenueModalLabel">Editar Sede</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('venues.update', $venue->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre de la Sede</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ $venue->name }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_state" class="form-label">Estado</label>
                                <input type="text" class="form-control" id="address_state" 
                                       name="address_state" value="{{ $venue->address_state }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_city" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="address_city" 
                                       name="address_city" value="{{ $venue->address_city }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_parish" class="form-label">Parroquia</label>
                                <input type="text" class="form-control" id="address_parish" 
                                       name="address_parish" value="{{ $venue->address_parish }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="founding_date" class="form-label">Fecha de Fundación</label>
                                <input type="date" class="form-control" id="founding_date" 
                                       name="founding_date" 
                                       value="{{ $venue->founding_date ? $venue->founding_date->format('Y-m-d') : '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address_details" class="form-label">Detalles de la Dirección</label>
                        <textarea class="form-control" id="address_details" 
                                  name="address_details" rows="2">{{ $venue->address_details }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="director_name" class="form-label">Nombre del Director</label>
                        <input type="text" class="form-control" id="director_name" 
                               name="director_name" value="{{ $venue->director_name }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="phone" 
                                       name="phone" value="{{ $venue->phone }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" 
                                       name="email" value="{{ $venue->email }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Estado</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Active" {{ $venue->status == 'Active' ? 'selected' : '' }}>Activo</option>
                            <option value="Inactive" {{ $venue->status == 'Inactive' ? 'selected' : '' }}>Inactivo</option>
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
@endsection

@push('css')
    
@endpush

@push('js')
    
@endpush