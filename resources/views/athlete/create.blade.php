@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">{{ __('Crear Nuevo Atleta') }}</h4>
                    </div>

                    <div class="card-body">

                        <!-- Agregar justo después de <div class="card-body"> -->
                        <div class="card-body">
                            <!-- Mensajes de Error y Éxito -->
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Errores de Validación -->
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                                        <h5 class="mb-0">Por favor corrige los siguientes errores:</h5>
                                    </div>
                                    <hr>
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>
                                                <i class="bi bi-dot me-2"></i>
                                                {{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- El resto del formulario continúa igual -->

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('athlete.store') }}" method="POST" class="needs-validation" novalidate>
                                @csrf

                                <!-- Información Personal -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bi bi-person-fill me-2"></i>
                                            <h5 class="mb-0">Información Personal</h5>
                                        </div>
                                        <hr>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="full_name" class="form-label">Nombre Completo <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('full_name') is-invalid @enderror" id="full_name"
                                                name="full_name" value="{{ old('full_name') }}" required>
                                            @error('full_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="identity_document" class="form-label">Documento de Identidad</label>
                                            <input type="text"
                                                class="form-control @error('identity_document') is-invalid @enderror"
                                                id="identity_document" name="identity_document"
                                                value="{{ old('identity_document') }}">
                                            @error('identity_document')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="nationality" class="form-label">Nacionalidad</label>
                                            <select class="form-select @error('nationality') is-invalid @enderror"
                                                id="nationality" name="nationality">
                                                <option value="">Seleccionar</option>
                                                <option value="Venezolano"
                                                    {{ old('nationality') == 'Venezolano' ? 'selected' : '' }}>
                                                    Venezolano</option>
                                                <option value="Extranjero"
                                                    {{ old('nationality') == 'Extranjero' ? 'selected' : '' }}>
                                                    Extranjero</option>
                                            </select>
                                            @error('nationality')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="birth_date" class="form-label">Fecha de Nacimiento</label>
                                            <input type="date"
                                                class="form-control @error('birth_date') is-invalid @enderror"
                                                id="birth_date" name="birth_date" value="{{ old('birth_date') }}"
                                                max="{{ date('Y-m-d', strtotime('-3 years')) }}" required>
                                            @error('birth_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="gender" class="form-label">Género</label>
                                            <select class="form-select @error('gender') is-invalid @enderror" id="gender"
                                                name="gender">
                                                <option value="">Seleccionar</option>
                                                <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>
                                                    Masculino</option>
                                                <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>
                                                    Femenino</option>
                                            </select>
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="email" name="email" value="{{ old('email') }}">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Teléfono</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                                <input type="text"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    id="phone" name="phone" value="{{ old('phone') }}">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Información Física y Médica -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bi bi-heart-fill me-2"></i>
                                            <h5 class="mb-0">Información Física y Médica</h5>
                                        </div>
                                        <hr>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="height" class="form-label">Altura (cm)</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('height') is-invalid @enderror" id="height"
                                                name="height" value="{{ old('height') }}">
                                            @error('height')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="current_weight" class="form-label">Peso Actual (kg)</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('current_weight') is-invalid @enderror"
                                                id="current_weight" name="current_weight"
                                                value="{{ old('current_weight') }}">
                                            @error('current_weight')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="shirt_size" class="form-label">Talla de Camisa</label>
                                            <select class="form-select @error('shirt_size') is-invalid @enderror"
                                                id="shirt_size" name="shirt_size">
                                                <option value="">Seleccionar talla</option>
                                                <option value="XS" {{ old('shirt_size') == 'XS' ? 'selected' : '' }}>
                                                    XS</option>
                                                <option value="S" {{ old('shirt_size') == 'S' ? 'selected' : '' }}>S
                                                </option>
                                                <option value="M" {{ old('shirt_size') == 'M' ? 'selected' : '' }}>M
                                                </option>
                                                <option value="L" {{ old('shirt_size') == 'L' ? 'selected' : '' }}>L
                                                </option>
                                                <option value="XL" {{ old('shirt_size') == 'XL' ? 'selected' : '' }}>
                                                    XL</option>
                                                <option value="XXL" {{ old('shirt_size') == 'XXL' ? 'selected' : '' }}>
                                                    XXL</option>
                                            </select>
                                            @error('shirt_size')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="pants_size" class="form-label">Talla de Pantalón</label>
                                            <input type="text"
                                                class="form-control @error('pants_size') is-invalid @enderror"
                                                id="pants_size" name="pants_size" value="{{ old('pants_size') }}">
                                            @error('pants_size')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="shoe_size" class="form-label">Talla de Calzado</label>
                                            <input type="text"
                                                class="form-control @error('shoe_size') is-invalid @enderror"
                                                id="shoe_size" name="shoe_size" value="{{ old('shoe_size') }}">
                                            @error('shoe_size')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="medical_conditions" class="form-label">Condiciones Médicas</label>
                                            <textarea class="form-control @error('medical_conditions') is-invalid @enderror" id="medical_conditions"
                                                name="medical_conditions" rows="3">{{ old('medical_conditions') }}</textarea>
                                            @error('medical_conditions')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="allergies" class="form-label">Alergias</label>
                                            <textarea class="form-control @error('allergies') is-invalid @enderror" id="allergies" name="allergies"
                                                rows="2">{{ old('allergies') }}</textarea>
                                            @error('allergies')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Contacto de Emergencia -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bi bi-person-lines-fill me-2"></i>
                                            <h5 class="mb-0">Contacto de Emergencia</h5>
                                        </div>
                                        <hr>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="emergency_contact_name" class="form-label">Nombre del
                                                Contacto</label>
                                            <input type="text"
                                                class="form-control @error('emergency_contact_name') is-invalid @enderror"
                                                id="emergency_contact_name" name="emergency_contact_name"
                                                value="{{ old('emergency_contact_name') }}">
                                            @error('emergency_contact_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="emergency_contact_phone" class="form-label">Teléfono del
                                                Contacto</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                                <input type="text"
                                                    class="form-control @error('emergency_contact_phone') is-invalid @enderror"
                                                    id="emergency_contact_phone" name="emergency_contact_phone"
                                                    value="{{ old('emergency_contact_phone') }}">
                                                @error('emergency_contact_phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="emergency_contact_relation" class="form-label">Relación con el
                                                Atleta</label>
                                            <input type="text"
                                                class="form-control @error('emergency_contact_relation') is-invalid @enderror"
                                                id="emergency_contact_relation" name="emergency_contact_relation"
                                                value="{{ old('emergency_contact_relation') }}">
                                            @error('emergency_contact_relation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 text-end">
                                        <a href="{{ route('athlete.index') }}" class="btn btn-secondary me-2">
                                            <i class="bi bi-x-circle me-1"></i> Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-1"></i> Crear Atleta
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                // Validación del lado del cliente
                (function() {
                    'use strict'
                    var forms = document.querySelectorAll('.needs-validation')
                    Array.prototype.slice.call(forms)
                        .forEach(function(form) {
                            form.addEventListener('submit', function(event) {
                                if (!form.checkValidity()) {
                                    event.preventDefault()
                                    event.stopPropagation()
                                }
                                form.classList.add('was-validated')
                            }, false)
                        })
                })()

                // Cierra alertas automáticamente
                window.setTimeout(function() {
                    document.querySelectorAll(".alert").forEach(function(alert) {
                        var bsAlert = new bootstrap.Alert(alert);
                        setTimeout(function() {
                            bsAlert.close();
                        }, 5000);
                    });
                }, 1000);

                // Validación de fecha de nacimiento
                document.getElementById('birth_date').addEventListener('change', function() {
                    const selectedDate = new Date(this.value);
                    const minValidDate = new Date();
                    minValidDate.setFullYear(minValidDate.getFullYear() - 3);

                    if (selectedDate > minValidDate) {
                        this.setCustomValidity('Debes tener al menos 3 años de edad');
                    } else {
                        this.setCustomValidity('');
                    }
                });

                // Validación de campos alfanuméricos
                const alphanumericFields = [
                    'full_name',
                    'emergency_contact_name',
                    'emergency_contact_relation'
                ];

                alphanumericFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);

                    field.addEventListener('input', function(e) {
                        // Expresión regular que permite:
                        // - Letras mayúsculas y minúsculas (incluyendo acentos)
                        // - Números
                        // - Espacios
                        const regex = /^[a-zA-Z0-9ÁÉÍÓÚáéíóúñÑÜü\s]*$/;

                        // Eliminar caracteres no permitidos
                        this.value = this.value.replace(/[^a-zA-Z0-9ÁÉÍÓÚáéíóúñÑÜü\s]/g, '');
                    });

                    // Validación en tiempo real
                    field.addEventListener('keypress', function(e) {
                        const char = String.fromCharCode(e.keyCode || e.which);
                        const regex = /^[a-zA-Z0-9ÁÉÍÓÚáéíóúñÑÜü\s]$/;

                        if (!regex.test(char)) {
                            e.preventDefault();
                        }
                    });
                });

                // Validación adicional para documento de identidad
                document.getElementById('identity_document').addEventListener('input', function(e) {
                    // Permite solo números y letras
                    this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
                });
            </script>
        @endpush
    @endsection
