@extends('adminlte::page')

@section('title', 'Detalles del Atleta')

@section('content_header')
    <h1>Detalles del Atleta</h1>
@stop


@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Tarjeta Principal -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-lg">
                                    @if ($person->gender == 'M')
                                        <i class="bi bi-person-circle text-primary" style="font-size: 4rem;"></i>
                                    @else
                                        <i class="bi bi-person-circle text-primary" style="font-size: 4rem;"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="col">
                                <h2 class="mb-1">{{ $person->full_name }}</h2>
                                <p class="mb-0 text-muted">
                                    <i class="bi bi-envelope me-2"></i>{{ $person->email ?? 'No registrado' }}
                                    @if ($person->phone)
                                        <span class="mx-2">|</span>
                                        <i class="bi bi-telephone me-2"></i>{{ $person->phone }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
                                    <i class="bi bi-pencil-square me-2"></i>Editar Información
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pestañas de Información -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <ul class="nav nav-tabs card-header-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#personal" role="tab">
                                    <i class="bi bi-person me-2"></i>Información Personal
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#academic" role="tab">
                                    <i class="bi bi-book me-2"></i>Información Académica
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#physical" role="tab">
                                    <i class="bi bi-heart me-2"></i>Información Física
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#contact" role="tab">
                                    <i class="bi bi-geo-alt me-2"></i>Contacto y Ubicación
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#documents" role="tab">
                                    <i class="bi bi-file-text me-2"></i>Documentos
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Información Personal -->
                            <div class="tab-pane fade show active" id="personal">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <h5 class="border-bottom pb-2">Datos Personales</h5>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Nombre Completo</label>
                                            <span class="fs-6">{{ $person->full_name }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Documento de Identidad</label>
                                            <span class="fs-6">{{ $person->identity_document ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Nacionalidad</label>
                                            <span class="fs-6">{{ $person->nationality ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Fecha de Nacimiento</label>
                                            <span
                                                class="fs-6">{{ $person->birth_date ? $person->birth_date->format('d/m/Y') : 'No registrado' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="border-bottom pb-2">Información Adicional</h5>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Lugar de Nacimiento</label>
                                            <span class="fs-6">{{ $person->birth_place ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Género</label>
                                            <span
                                                class="fs-6">{{ $person->gender == 'M' ? 'Masculino' : 'Femenino' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Estado Civil</label>
                                            <span class="fs-6">{{ $person->civil_status ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Profesión</label>
                                            <span class="fs-6">{{ $person->profession ?? 'No registrado' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Información Académica -->
                            <div class="tab-pane fade" id="academic">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="border-bottom pb-2">Formación Académica</h5>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Nivel Académico</label>
                                            <span class="fs-6">{{ $person->academic_level ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Institución</label>
                                            <span class="fs-6">{{ $person->institution ?? 'No registrado' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Información Física -->
                            <div class="tab-pane fade" id="physical">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <h5 class="border-bottom pb-2">Medidas Físicas</h5>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Altura</label>
                                            <span class="fs-6">{{ $person->athlete->height ?? 'No registrado' }}
                                                cm</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Peso Actual</label>
                                            <span class="fs-6">{{ $person->athlete->current_weight ?? 'No registrado' }}
                                                kg</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Talla de Camisa</label>
                                            <span
                                                class="fs-6">{{ $person->athlete->shirt_size ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Talla de Pantalón</label>
                                            <span
                                                class="fs-6">{{ $person->athlete->pants_size ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Talla de Calzado</label>
                                            <span
                                                class="fs-6">{{ $person->athlete->shoe_size ?? 'No registrado' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="border-bottom pb-2">Información Médica</h5>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Condiciones Médicas</label>
                                            <span
                                                class="fs-6">{{ $person->athlete->medical_conditions ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Alergias</label>
                                            <span
                                                class="fs-6">{{ $person->athlete->allergies ?? 'No registrado' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contacto y Ubicación -->
                            <div class="tab-pane fade" id="contact">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <h5 class="border-bottom pb-2">Información de Contacto</h5>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Email</label>
                                            <span class="fs-6">{{ $person->email ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Teléfono</label>
                                            <span class="fs-6">{{ $person->phone ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Redes Sociales</label>
                                            <span class="fs-6">{{ $person->social_media ?? 'No registrado' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="border-bottom pb-2">Dirección</h5>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Estado</label>
                                            <span class="fs-6">{{ $person->address_state ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Ciudad</label>
                                            <span class="fs-6">{{ $person->address_city ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Dirección Detallada</label>
                                            <span class="fs-6">{{ $person->address_details ?? 'No registrado' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Documentos -->
                            <div class="tab-pane fade" id="documents">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="border-bottom pb-2">Documentos de Viaje</h5>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Número de Pasaporte</label>
                                            <span class="fs-6">{{ $person->passport_number ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Fecha de Vencimiento del Pasaporte</label>
                                            <span
                                                class="fs-6">{{ $person->passport_expiry ? date('d/m/Y', strtotime($person->passport_expiry)) : 'No registrado' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Información</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('person.update', $person->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Información Personal -->
                            <div class="col-12">
                                <h6 class="border-bottom pb-2">Información Personal</h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" name="full_name"
                                    value="{{ $person->full_name }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Documento de Identidad</label>
                                <input type="text" class="form-control" name="identity_document"
                                    value="{{ $person->identity_document }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nacionalidad</label>
                                <input type="text" class="form-control" name="nationality"
                                    value="{{ $person->nationality }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" name="birth_date"
                                    value="{{ $person->birth_date?->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lugar de Nacimiento</label>
                                <input type="text" class="form-control" name="birth_place"
                                    value="{{ $person->birth_place }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Género</label>
                                <select class="form-select" name="gender">
                                    <option value="M" {{ $person->gender == 'M' ? 'selected' : '' }}>Masculino
                                    </option>
                                    <option value="F" {{ $person->gender == 'F' ? 'selected' : '' }}>Femenino
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Estado Civil</label>
                                <select class="form-select" name="civil_status">
                                    <option value="">Seleccionar</option>
                                    <option value="Soltero" {{ $person->civil_status == 'Soltero' ? 'selected' : '' }}>
                                        Soltero</option>
                                    <option value="Casado" {{ $person->civil_status == 'Casado' ? 'selected' : '' }}>
                                        Casado</option>
                                    <option value="Divorciado"
                                        {{ $person->civil_status == 'Divorciado' ? 'selected' : '' }}>Divorciado</option>
                                    <option value="Viudo" {{ $person->civil_status == 'Viudo' ? 'selected' : '' }}>Viudo
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Profesión</label>
                                <input type="text" class="form-control" name="profession"
                                    value="{{ $person->profession }}">
                            </div>

                            <!-- Información Académica -->
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mt-2">Información Académica</h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nivel Académico</label>
                                <select class="form-select" name="academic_level">
                                    <option value="">Seleccionar</option>
                                    <option value="Primaria"
                                        {{ $person->academic_level == 'Primaria' ? 'selected' : '' }}>Primaria</option>
                                    <option value="Secundaria"
                                        {{ $person->academic_level == 'Secundaria' ? 'selected' : '' }}>Secundaria</option>
                                    <option value="Técnico" {{ $person->academic_level == 'Técnico' ? 'selected' : '' }}>
                                        Técnico</option>
                                    <option value="Universitario"
                                        {{ $person->academic_level == 'Universitario' ? 'selected' : '' }}>Universitario
                                    </option>
                                    <option value="Postgrado"
                                        {{ $person->academic_level == 'Postgrado' ? 'selected' : '' }}>Postgrado</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Institución</label>
                                <input type="text" class="form-control" name="institution"
                                    value="{{ $person->institution }}">
                            </div>

                            <!-- Información de Contacto -->
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mt-2">Información de Contacto</h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ $person->email }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control" name="phone" value="{{ $person->phone }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Redes Sociales</label>
                                <input type="text" class="form-control" name="social_media"
                                    value="{{ $person->social_media }}">
                            </div>

                            <!-- Dirección -->
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mt-2">Dirección</h6>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Estado</label>
                                <input type="text" class="form-control" name="address_state"
                                    value="{{ $person->address_state }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ciudad</label>
                                <input type="text" class="form-control" name="address_city"
                                    value="{{ $person->address_city }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Dirección Detallada</label>
                                <input type="text" class="form-control" name="address_details"
                                    value="{{ $person->address_details }}">
                            </div>

                            <!-- Documentos -->
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mt-2">Documentos</h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Número de Pasaporte</label>
                                <input type="text" class="form-control" name="passport_number"
                                    value="{{ $person->passport_number }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Vencimiento del Pasaporte</label>
                                <input type="date" class="form-control" name="passport_expiry"
                                    value="{{ $person->passport_expiry }}">
                            </div>

                            <!-- Información Física -->
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mt-2">Información Física</h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Altura (cm)</label>
                                <input type="number" step="0.01" class="form-control" name="height"
                                    value="{{ $person->athlete->height }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Peso Actual (kg)</label>
                                <input type="number" step="0.01" class="form-control" name="current_weight"
                                    value="{{ $person->athlete->current_weight }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Talla de Camisa</label>
                                <select class="form-select" name="shirt_size">
                                    <option value="">Seleccionar</option>
                                    <option value="XS" {{ $person->athlete->shirt_size == 'XS' ? 'selected' : '' }}>XS
                                    </option>
                                    <option value="S" {{ $person->athlete->shirt_size == 'S' ? 'selected' : '' }}>S
                                    </option>
                                    <option value="M" {{ $person->athlete->shirt_size == 'M' ? 'selected' : '' }}>M
                                    </option>
                                    <option value="L" {{ $person->athlete->shirt_size == 'L' ? 'selected' : '' }}>L
                                    </option>
                                    <option value="XL" {{ $person->athlete->shirt_size == 'XL' ? 'selected' : '' }}>XL
                                    </option>
                                    <option value="XXL" {{ $person->athlete->shirt_size == 'XXL' ? 'selected' : '' }}>
                                        XXL</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Talla de Pantalón</label>
                                <input type="text" class="form-control" name="pants_size"
                                    value="{{ $person->athlete->pants_size }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Talla de Calzado</label>
                                <input type="text" class="form-control" name="shoe_size"
                                    value="{{ $person->athlete->shoe_size }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Condiciones Médicas</label>
                                <textarea class="form-control" name="medical_conditions" rows="3">{{ $person->athlete->medical_conditions }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Alergias</label>
                                <textarea class="form-control" name="allergies" rows="3">{{ $person->athlete->allergies }}</textarea>
                            </div>
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


@section('css')
    {{-- Bootstrap Icons --}}

    <style>
        /* Estilos para el modal */
        .modal-dialog {
            max-width: 800px;
        }

        .modal-content {
            border-radius: 0.5rem;
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }

        /* Estilos para los tabs */
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            padding: 1rem 1.5rem;
            font-weight: 500;
            border-bottom: 2px solid transparent;
        }

        .nav-tabs .nav-link:hover {
            border-color: transparent;
            color: #0d6efd;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
            background-color: transparent;
        }

        /* Utilidades */
        .me-1 {
            margin-right: 0.25rem !important;
        }

        .me-2 {
            margin-right: 0.5rem !important;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .mb-1 {
            margin-bottom: 0.25rem !important;
        }

        .mb-2 {
            margin-bottom: 0.5rem !important;
        }

        .mb-3 {
            margin-bottom: 1rem !important;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        /* Estilos para form-select */
        .form-select {
            display: block;
            width: 100%;
            padding: 0.375rem 2.25rem 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            appearance: none;
        }

        /* Estilos para el modal scrollable */
        .modal-dialog-scrollable {
            height: calc(100% - 1rem);
            max-height: calc(100vh - 2rem);
        }

        .modal-dialog-scrollable .modal-content {
            max-height: 100%;
            overflow: hidden;
        }

        .modal-dialog-scrollable .modal-body {
            overflow-y: auto;
            max-height: calc(100vh - 210px);
            /* Ajusta según el tamaño del header y footer */
        }
    </style>
@stop


@section('js')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Modal
            const editModal = new bootstrap.Modal(document.getElementById('editModal'), {
                keyboard: false
            });

            // Manejador para el botón de editar
            document.querySelector('[data-bs-toggle="modal"]').addEventListener('click', function() {
                editModal.show();
            });

            // Inicializar Tabs
            const triggerTabList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tab"]'));
            triggerTabList.forEach(function(triggerEl) {
                const tabTrigger = new bootstrap.Tab(triggerEl);

                triggerEl.addEventListener('click', function(event) {
                    event.preventDefault();
                    tabTrigger.show();
                });
            });

            // Mantener tab activo después de recargar
            const hash = window.location.hash;
            if (hash) {
                const triggerEl = document.querySelector(`[data-bs-toggle="tab"][href="${hash}"]`);
                if (triggerEl) {
                    bootstrap.Tab.getInstance(triggerEl).show();
                }
            }

            // Actualizar URL al cambiar de tab
            triggerTabList.forEach(function(triggerEl) {
                triggerEl.addEventListener('shown.bs.tab', function(event) {
                    window.location.hash = event.target.getAttribute('href');
                });
            });

            // Auto-cerrar alertas
            const alertList = document.querySelectorAll('.alert');
            alertList.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
@stop
