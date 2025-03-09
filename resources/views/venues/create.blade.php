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
                                <i class="bi bi-building-add me-2"></i>
                                Crear Nueva Sede
                            </h5>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('venues.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('venues.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre de la Sede</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   required maxlength="255">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address_state" class="form-label">Estado</label>
                                    <input type="text" class="form-control @error('address_state') is-invalid @enderror" 
                                           id="address_state" name="address_state" value="{{ old('address_state') }}">
                                    @error('address_state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address_city" class="form-label">Ciudad</label>
                                    <input type="text" class="form-control @error('address_city') is-invalid @enderror" 
                                           id="address_city" name="address_city" value="{{ old('address_city') }}">
                                    @error('address_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address_parish" class="form-label">Parroquia</label>
                                    <input type="text" class="form-control @error('address_parish') is-invalid @enderror" 
                                           id="address_parish" name="address_parish" value="{{ old('address_parish') }}">
                                    @error('address_parish')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="founding_date" class="form-label">Fecha de Fundación</label>
                                    <input type="date" class="form-control @error('founding_date') is-invalid @enderror" 
                                           id="founding_date" name="founding_date" value="{{ old('founding_date') }}" 
                                           min="{{ now()->subYears(200)->format('Y-m-d') }}" max="{{ now()->subYears(2)->format('Y-m-d') }}">
                                    @error('founding_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address_details" class="form-label">Detalles de la Dirección</label>
                            <textarea class="form-control @error('address_details') is-invalid @enderror" 
                                      id="address_details" name="address_details" rows="2">{{ old('address_details') }}</textarea>
                            @error('address_details')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="director_name" class="form-label">Nombre del Director</label>
                            <input type="text" class="form-control @error('director_name') is-invalid @enderror" 
                                   id="director_name" name="director_name" value="{{ old('director_name') }}">
                            @error('director_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" 
                                           pattern="^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Estado</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>
                                Guardar Sede
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    form.addEventListener('submit', function (event) {
        let isValid = true;

        // Validar el nombre
        const nameInput = document.getElementById('name');
        if (nameInput.value.trim() === '') {
            isValid = false;
            alert('El nombre de la sede es obligatorio.');
        }

        // Validar el teléfono
        const phoneInput = document.getElementById('phone');
        const phonePattern = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;
        if (phoneInput.value && !phonePattern.test(phoneInput.value)) {
            isValid = false;
            alert('El formato del teléfono no es válido.');
        }

        // Validar la fecha de fundación
        const foundingDateInput = document.getElementById('founding_date');
        const today = new Date(); // Fecha actual
        const minDate = new Date();
        minDate.setFullYear(today.getFullYear() - 200); // 200 años atrás

        if (foundingDateInput.value) {
            const selectedDate = new Date(foundingDateInput.value);

            if (selectedDate > today) {
                isValid = false;
                alert('La fecha de fundación no puede ser mayor a la fecha actual.');
            } else if (selectedDate < minDate) {
                isValid = false;
                alert('La fecha de fundación no puede ser menor a 200 años atrás.');
            }
        }

        // Si no es válido, prevenir el envío del formulario
        if (!isValid) {
            event.preventDefault();
        }
    });
});
</script>    
@endpush

@endsection