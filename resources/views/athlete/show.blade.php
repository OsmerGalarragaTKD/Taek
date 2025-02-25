@extends('layouts.app')

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
                                    @if ($athlete->gender == 'M')
                                        <i class="bi bi-person-circle text-primary" style="font-size: 4rem;"></i>
                                    @else
                                        <i class="bi bi-person-circle text-pink" style="font-size: 4rem;"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="col">
                                <h2 class="mb-1">{{ $athlete->full_name }}</h2>
                                <p class="mb-0 text-muted">
                                    <i class="bi bi-envelope me-2"></i>{{ $athlete->email ?? 'No registrado' }}
                                    @if ($athlete->phone)
                                        <span class="mx-2">|</span>
                                        <i class="bi bi-telephone me-2"></i>{{ $athlete->phone }}
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
                            @if($isMinor)
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#representatives" role="tab">
                                    <i class="bi bi-person-vcard me-2"></i>Representantes
                                </a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#martial-arts" role="tab">
                                    <i class="bi bi-award me-2"></i>Artes Marciales
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
                                            <span class="fs-6">{{ $athlete->full_name }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Documento de Identidad</label>
                                            <span class="fs-6">{{ $athlete->identity_document ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Nacionalidad</label>
                                            <span class="fs-6">{{ $athlete->nationality ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Fecha de Nacimiento</label>
                                            <span class="fs-6">
                                                {{ $athlete->birth_date ? $athlete->birth_date->format('d/m/Y') : 'No registrado' }}
                                                @if($athlete->birth_date)
                                                    (Edad: {{ $athlete->birth_date->age }} años)
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="border-bottom pb-2">Información Adicional</h5>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Lugar de Nacimiento</label>
                                            <span class="fs-6">{{ $athlete->birth_place ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Género</label>
                                            <span class="fs-6">{{ $athlete->gender == 'M' ? 'Masculino' : 'Femenino' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Estado Civil</label>
                                            <span class="fs-6">{{ $athlete->civil_status ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Profesión</label>
                                            <span class="fs-6">{{ $athlete->profession ?? 'No registrado' }}</span>
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
                                            <span class="fs-6">{{ $athlete->academic_level ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Institución</label>
                                            <span class="fs-6">{{ $athlete->institution ?? 'No registrado' }}</span>
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
                                            <span class="fs-6">{{ $athlete->height ?? 'No registrado' }} cm</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Peso Actual</label>
                                            <span class="fs-6">{{ $athlete->current_weight ?? 'No registrado' }} kg</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Talla de Camisa</label>
                                            <span class="fs-6">{{ $athlete->shirt_size ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Talla de Pantalón</label>
                                            <span class="fs-6">{{ $athlete->pants_size ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Talla de Calzado</label>
                                            <span class="fs-6">{{ $athlete->shoe_size ?? 'No registrado' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="border-bottom pb-2">Información Médica</h5>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Condiciones Médicas</label>
                                            <span class="fs-6">{{ $athlete->medical_conditions ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Alergias</label>
                                            <span class="fs-6">{{ $athlete->allergies ?? 'No registrado' }}</span>
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
                                            <span class="fs-6">{{ $athlete->email ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Teléfono</label>
                                            <span class="fs-6">{{ $athlete->phone ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Redes Sociales</label>
                                            <span class="fs-6">{{ $athlete->social_media ?? 'No registrado' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="border-bottom pb-2">Dirección</h5>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Estado</label>
                                            <span class="fs-6">{{ $athlete->address_state ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Ciudad</label>
                                            <span class="fs-6">{{ $athlete->address_city ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Dirección Detallada</label>
                                            <span class="fs-6">{{ $athlete->address_details ?? 'No registrado' }}</span>
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
                                            <span class="fs-6">{{ $athlete->passport_number ?? 'No registrado' }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted d-block">Fecha de Vencimiento del Pasaporte</label>
                                            <span class="fs-6">{{ $athlete->passport_expiry ? date('d/m/Y', strtotime($athlete->passport_expiry)) : 'No registrado' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Representantes -->
                            @if($isMinor)
                            <div class="tab-pane fade" id="representatives">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="border-bottom pb-2">Información del Representante</h5>
                                        @if ($athlete->primaryRepresentative)
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-3">
                                                        {{ $athlete->primaryRepresentative->representative->full_name }}
                                                    </h6>
                                                    <div class="mb-2">
                                                        <small class="text-muted d-block">Documento de Identidad</small>
                                                        <span>{{ $athlete->primaryRepresentative->representative->identity_document }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <small class="text-muted d-block">Relación</small>
                                                        <span>{{ $athlete->primaryRepresentative->relationship }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <small class="text-muted d-block">Teléfono</small>
                                                        <span>{{ $athlete->primaryRepresentative->representative->phone }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                El atleta es menor de edad y requiere un representante
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Información de Artes Marciales -->
                            <div class="tab-pane fade" id="martial-arts">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="border-bottom pb-2">Grado y Categoría</h5>
                                        </div>

                                        <div class="mb-3">
                                            <label class="text-muted d-block">Sede</label>
                                            <span class="fs-6">
                                                @if($athlete->venue)
                                                    {{ $athlete->venue->name }} 
                                                    <small class="text-muted">({{ $athlete->venue->address_city }})</small>
                                                @else
                                                    No asignada
                                                @endif
                                            </span>
                                        </div>

                                        <!-- Grado Actual -->
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <h6 class="mb-0">Grado Actual</h6>
                                            </div>
                                            <div class="card-body">
                                                @if ($athlete->currentGrade)
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="rounded-circle p-3" style="background-color: {{ $athlete->currentGrade->grade->color }}"></div>
                                                        <div>
                                                            <h6 class="mb-1">{{ $athlete->currentGrade->grade->name . ' - ' . $athlete->currentGrade->grade->color }}</h6>
                                                            <small class="text-muted">
                                                                Obtenido el {{ $athlete->currentGrade->date_achieved?->format('d/m/Y') ?? 'No registrado' }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <p class="text-muted mb-0">No hay grado registrado</p>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Historial de Grados -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Historial de Grados</h6>
                                            </div>
                                            <div class="card-body">
                                                @if ($athlete->grades->isNotEmpty())
                                                    <div class="timeline">
                                                        @foreach ($athlete->grades->sortByDesc('date_achieved') as $grade)
                                                            <div class="timeline-item">
                                                                <div class="timeline-marker" style="background-color: {{ $grade->grade->color }}"></div>
                                                                <div class="timeline-content">
                                                                    <h6 class="mb-1">{{ $grade->grade->name . ' - ' . $grade->grade->color }}</h6>
                                                                    <small class="text-muted">{{ $grade->date_achieved?->format('d/m/Y') ?? 'No registrado' }}</small>
                                                                    @if ($grade->certificate_number)
                                                                        <br>
                                                                        <small class="text-muted">Certificado: {{ $grade->certificate_number }}</small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-muted mb-0">No hay historial de grados</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        @if(!$isMinor)
                                        <h5 class="border-bottom pb-2">Atletas que Representa</h5>
                                        @if ($athlete->athletesRepresenting->isNotEmpty())
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6 class="mb-0">Atletas que Representa</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="list-group list-group-flush">
                                                        @foreach ($athlete->athletesRepresenting as $representation)
                                                            <div class="list-group-item">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <h6 class="mb-1">{{ $representation->athlete->full_name }}</h6>
                                                                        <small class="text-muted">{{ $representation->relationship }}</small>
                                                                    </div>
                                                                    <a href="{{ route('athlete.show', $representation->athlete->id) }}" class="btn btn-sm btn-outline-primary">
                                                                        <i class="bi bi-eye me-1"></i>Ver
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-muted">El atleta no es representante de ningún otro atleta</p>
                                        @endif
                                        @endif
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
                <form action="{{ route('athlete.update', $athlete->id) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                                <h5 class="mb-0">Errores en el formulario:</h5>
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
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Información Personal -->
                            <div class="col-12">
                                <h6 class="border-bottom pb-2">Información Personal</h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('full_name') is-invalid @enderror" 
                                       name="full_name"
                                       id="full_name"
                                       value="{{ old('full_name', $athlete->full_name) }}"
                                       required
                                       pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                                       title="Solo letras y espacios">
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Documento de Identidad</label>
                                <input type="text" 
                                       class="form-control @error('identity_document') is-invalid @enderror" 
                                       name="identity_document"
                                       id="identity_document"
                                       value="{{ old('identity_document', $athlete->identity_document) }}"
                                       pattern="^[VEJ]-?\d{6,8}$"
                                       title="Formato válido: V-12345678">
                                @error('identity_document')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nacionalidad</label>
                                <select class="form-select @error('nationality') is-invalid @enderror" 
                                        name="nationality"
                                        id="nationality">
                                    <option value="">Seleccionar</option>
                                    <option value="Venezolano" {{ old('nationality', $athlete->nationality) == 'Venezolano' ? 'selected' : '' }}>
                                        Venezolano
                                    </option>
                                    <option value="Extranjero" {{ old('nationality', $athlete->nationality) == 'Extranjero' ? 'selected' : '' }}>
                                        Extranjero
                                    </option>
                                </select>
                                @error('nationality')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('birth_date') is-invalid @enderror" 
                                       name="birth_date"
                                       id="birth_date"
                                       value="{{ old('birth_date', $athlete->birth_date?->format('Y-m-d')) }}"
                                       max="{{ date('Y-m-d', strtotime('-3 years')) }}"
                                       required>
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lugar de Nacimiento</label>
                                <input type="text" 
                                       class="form-control @error('birth_place') is-invalid @enderror" 
                                       name="birth_place"
                                       value="{{ old('birth_place', $athlete->birth_place) }}">
                                @error('birth_place')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Género <span class="text-danger">*</span></label>
                                <select class="form-select @error('gender') is-invalid @enderror" 
                                        name="gender"
                                        required>
                                    <option value="M" {{ old('gender', $athlete->gender) == 'M' ? 'selected' : '' }}>
                                        Masculino
                                    </option>
                                    <option value="F" {{ old('gender', $athlete->gender) == 'F' ? 'selected' : '' }}>
                                        Femenino
                                    </option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Estado Civil</label>
                                <select class="form-select @error('civil_status') is-invalid @enderror" 
                                        name="civil_status">
                                    <option value="">Seleccionar</option>
                                    <option value="Soltero" {{ old('civil_status', $athlete->civil_status) == 'Soltero' ? 'selected' : '' }}>
                                        Soltero
                                    </option>
                                    <option value="Casado" {{ old('civil_status', $athlete->civil_status) == 'Casado' ? 'selected' : '' }}>
                                        Casado
                                    </option>
                                    <option value="Divorciado" {{ old('civil_status', $athlete->civil_status) == 'Divorciado' ? 'selected' : '' }}>
                                        Divorciado
                                    </option>
                                    <option value="Viudo" {{ old('civil_status', $athlete->civil_status) == 'Viudo' ? 'selected' : '' }}>
                                        Viudo
                                    </option>
                                </select>
                                @error('civil_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Profesión</label>
                                <input type="text" 
                                       class="form-control @error('profession') is-invalid @enderror" 
                                       name="profession"
                                       value="{{ old('profession', $athlete->profession) }}">
                                @error('profession')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Información Académica -->
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mt-2">Información Académica</h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nivel Académico</label>
                                <select class="form-select @error('academic_level') is-invalid @enderror" 
                                        name="academic_level">
                                    <option ```blade
                                    <option value="">Seleccionar</option>
                                    <option value="Primaria" {{ old('academic_level', $athlete->academic_level) == 'Primaria' ? 'selected' : '' }}>
                                        Primaria
                                    </option>
                                    <option value="Secundaria" {{ old('academic_level', $athlete->academic_level) == 'Secundaria' ? 'selected' : '' }}>
                                        Secundaria
                                    </option>
                                    <option value="Técnico" {{ old('academic_level', $athlete->academic_level) == 'Técnico' ? 'selected' : '' }}>
                                        Técnico
                                    </option>
                                    <option value="Universitario" {{ old('academic_level', $athlete->academic_level) == 'Universitario' ? 'selected' : '' }}>
                                        Universitario
                                    </option>
                                    <option value="Postgrado" {{ old('academic_level', $athlete->academic_level) == 'Postgrado' ? 'selected' : '' }}>
                                        Postgrado
                                    </option>
                                </select>
                                @error('academic_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Institución</label>
                                <input type="text" 
                                       class="form-control @error('institution') is-invalid @enderror" 
                                       name="institution"
                                       value="{{ old('institution', $athlete->institution) }}">
                                @error('institution')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Información de Contacto -->
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mt-2">Información de Contacto</h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           name="email"
                                           id="email"
                                           value="{{ old('email', $athlete->email) }}"
                                           pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
                                           placeholder="ejemplo@dominio.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           name="phone"
                                           id="phone"
                                           value="{{ old('phone', $athlete->phone) }}"
                                           pattern="[0-9+\-()\s]{8,15}"
                                           title="Formato válido: +58 412-1234567">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Redes Sociales</label>
                                <input type="text" 
                                       class="form-control @error('social_media') is-invalid @enderror" 
                                       name="social_media"
                                       value="{{ old('social_media', $athlete->social_media) }}">
                                @error('social_media')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dirección -->
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mt-2">Dirección</h6>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Estado</label>
                                <input type="text" 
                                       class="form-control @error('address_state') is-invalid @enderror" 
                                       name="address_state"
                                       value="{{ old('address_state', $athlete->address_state) }}">
                                @error('address_state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ciudad</label>
                                <input type="text" 
                                       class="form-control @error('address_city') is-invalid @enderror" 
                                       name="address_city"
                                       value="{{ old('address_city', $athlete->address_city) }}">
                                @error('address_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Dirección Detallada</label>
                                <input type="text" 
                                       class="form-control @error('address_details') is-invalid @enderror" 
                                       name="address_details"
                                       value="{{ old('address_details', $athlete->address_details) }}">
                                @error('address_details')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Documentos -->
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mt-2">Documentos</h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Número de Pasaporte</label>
                                <input type="text" 
                                       class="form-control @error('passport_number') is-invalid @enderror" 
                                       name="passport_number"
                                       value="{{ old('passport_number', $athlete->passport_number) }}"
                                       pattern="[A-Z0-9]{6,9}"
                                       title="Formato válido: Letras mayúsculas y números">
                                @error('passport_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Vencimiento del Pasaporte</label>
                                <input type="date" 
                                       class="form-control @error('passport_expiry') is-invalid @enderror" 
                                       name="passport_expiry"
                                       value="{{ old('passport_expiry', $athlete->passport_expiry) }}"
                                       min="{{ date('Y-m-d') }}">
                                @error('passport_expiry')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Información Física -->
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mt-2">Información Física</h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Altura (cm)</label>
                                <input type="number" 
                                       step="0.01" 
                                       class="form-control @error('height') is-invalid @enderror" 
                                       name="height"
                                       id="height"
                                       value="{{ old('height', $athlete->height) }}"
                                       min="50"
                                       max="300">
                                @error('height')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Peso Actual (kg)</label>
                                <input type="number" 
                                       step="0.01" 
                                       class="form-control @error('current_weight') is-invalid @enderror" 
                                       name="current_weight"
                                       id="current_weight"
                                       value="{{ old('current_weight', $athlete->current_weight) }}"
                                       min="10"
                                       max="500">
                                @error('current_weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Talla de Camisa</label>
                                <select class="form-select @error('shirt_size') is-invalid @enderror" 
                                        name="shirt_size">
                                    <option value="">Seleccionar</option>
                                    <option value="XS" {{ old('shirt_size', $athlete->shirt_size) == 'XS' ? 'selected' : '' }}>XS</option>
                                    <option value="S" {{ old('shirt_size', $athlete->shirt_size) == 'S' ? 'selected' : '' }}>S</option>
                                    <option value="M" {{ old('shirt_size', $athlete->shirt_size) == 'M' ? 'selected' : '' }}>M</option>
                                    <option value="L" {{ old('shirt_size', $athlete->shirt_size) == 'L' ? 'selected' : '' }}>L</option>
                                    <option value="XL" {{ old('shirt_size', $athlete->shirt_size) == 'XL' ? 'selected' : '' }}>XL</option>
                                    <option value="XXL" {{ old('shirt_size', $athlete->shirt_size) == 'XXL' ? 'selected' : '' }}>XXL</option>
                                </select>
                                @error('shirt_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Talla de Pantalón</label>
                                <input type="text" 
                                       class="form-control @error('pants_size') is-invalid @enderror" 
                                       name="pants_size"
                                       id="pants_size"
                                       value="{{ old('pants_size', $athlete->pants_size) }}"
                                       maxlength="10">
                                @error('pants_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Talla de Calzado</label>
                                <input type="text" 
                                       class="form-control @error('shoe_size') is-invalid @enderror" 
                                       name="shoe_size"
                                       id="shoe_size"
                                       value="{{ old('shoe_size', $athlete->shoe_size) }}"
                                       maxlength="10">
                                @error('shoe_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Condiciones Médicas</label>
                                <textarea class="form-control @error('medical_conditions') is-invalid @enderror" 
                                          name="medical_conditions" 
                                          rows="3">{{ old('medical_conditions', $athlete->medical_conditions) }}</textarea>
                                @error('medical_conditions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Alergias</label>
                                <textarea class="form-control @error('allergies') is-invalid @enderror" 
                                          name="allergies" 
                                          rows="3">{{ old('allergies', $athlete->allergies) }}</textarea>
                                @error('allergies')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Información de Artes Marciales -->
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mt-2">Información de Artes Marciales</h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cinturón <span class="text-danger">*</span></label>
                                <select class="form-select @error('belt_grade_id') is-invalid @enderror" 
                                        name="belt_grade_id" 
                                        required>
                                    <option value="">Seleccionar</option>
                                    @foreach ($beltGrades as $grade)
                                        <option value="{{ $grade->id }}"
                                            {{ old('belt_grade_id', $athlete->currentGrade?->grade_id) == $grade->id ? 'selected' : '' }}>
                                            {{ $grade->name . ' - ' . $grade->color . ' - ' . $grade->level }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('belt_grade_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sede <span class="text-danger">*</span></label>
                                <select class="form-select @error('venue_id') is-invalid @enderror" 
                                        name="venue_id" 
                                        required>
                                    <option value="">Seleccionar sede</option>
                                    @foreach($venues as $venue)
                                        <option value="{{ $venue->id }}" 
                                            {{ old('venue_id', $athlete->venue_id) == $venue->id ? 'selected' : '' }}>
                                            {{ $venue->name }} - {{ $venue->address_city }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('venue_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Obtención <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('grade_date_achieved') is-invalid @enderror" 
                                       name="grade_date_achieved" 
                                       required
                                       value="{{ old('grade_date_achieved', $athlete->currentGrade?->date_achieved?->format('Y-m-d')) }}"
                                       max="{{ date('Y-m-d') }}">
                                @error('grade_date_achieved')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Número de Certificado <span class="text-danger"></span></label>
                                <input type="text" 
                                       class="form-control @error('grade_certificate_number') is-invalid @enderror" 
                                       name="grade_certificate_number" 
                                       value="{{ old('grade_certificate_number', $athlete->currentGrade?->certificate_number) }}"
                                       >
                                @error('grade_certificate_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($athlete->birth_date && \Carbon\Carbon::parse($athlete->birth_date)->age < 18)
                                <!-- Información del Representante -->
                                <div class="col-12">
                                    <h6 class="border-bottom pb-2 mt-2">Información del Representante</h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nombre del Representante <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('representative_name') is-invalid @enderror" 
                                           name="representative_name"
                                           id="representative_name"
                                           value="{{ old('representative_name', $athlete->primaryRepresentative?->representative->full_name) }}"
                                           pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                                           title="Solo letras y espacios"
                                           required>
                                    @error('representative_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Documento de Identidad <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('representative_identity_document') is-invalid @enderror" 
                                           name="representative_identity_document"
                                           id="representative_identity_document"
                                           value="{{ old('representative_identity_document', $athlete->primaryRepresentative?->representative->identity_document) }}"
                                           pattern="^[VEJ]-?\d{6,8}$"
                                           title="Formato válido: V-12345678"
                                           required>
                                    @error('representative_identity_document')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Relación <span class="text-danger">*</span></label>
                                    <select class="form-select @error('representative_relationship') is-invalid @enderror" 
                                            name="representative_relationship"
                                            required>
                                        <option value="">Seleccionar</option>
                                        <option value="Padre"
                                            {{ old('representative_relationship', $athlete->primaryRepresentative?->relationship) == 'Padre' ? 'selected' : '' }}>
                                            Padre
                                        </option>
                                        <option value="Madre"
                                            {{ old('representative_relationship', $athlete->primaryRepresentative?->relationship) == 'Madre' ? 'selected' : '' }}>
                                            Madre
                                        </option>
                                        <option value="Tutor"
                                            {{ old('representative_relationship', $athlete->primaryRepresentative?->relationship) == 'Tutor' ? 'selected' : '' }}>
                                            Tutor Legal
                                        </option>
                                    </select>
                                    @error('representative_relationship')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email del Representante</label>
                                    <input type="email" 
                                           class="form-control @error('representative_email') is-invalid @enderror" 
                                           name="representative_email"
                                           value="{{ old('representative_email', $athlete->primaryRepresentative?->representative->email) }}"
                                           pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                                    @error('representative_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Teléfono del Representante <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('representative_phone') is-invalid @enderror" 
                                           name="representative_phone"
                                           id="representative_phone"
                                           value="{{ old('representative_phone', $athlete->primaryRepresentative?->representative->phone) }}"
                                           pattern="[0-9+\-()\s]{8,15}"
                                           title="Formato válido: +58 412-1234567"
                                           required>
                                    @error('representative_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nacionalidad <span class="text-danger">*</span></label>
                                    <select class="form-select @error('representative_nationality') is-invalid @enderror" 
                                            name="representative_nationality"
                                            required>
                                        <option value="">Seleccionar</option>
                                        <option value="Venezolano"
                                            {{ old('representative_nationality', $athlete->primaryRepresentative?->representative->nationality) == 'Venezolano' ? 'selected' : '' }}>
                                            Venezolano
                                        </option>
                                        <option value="Extranjero"
                                            {{ old('representative_nationality', $athlete->primaryRepresentative?->representative->nationality) == 'Extranjero' ? 'selected' : '' }}>
                                            Extranjero
                                        </option>
                                    </select>
                                    @error('representative_nationality')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('representative_birth_date') is-invalid @enderror" 
                                           name="representative_birth_date"
                                           value="{{ old('representative_birth_date', $athlete->primaryRepresentative?->representative->birth_date?->format('Y-m-d')) }}"
                                           max="{{ date('Y-m-d') }}"
                                           required>
                                    @error('representative_birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Profesión</label>
                                    <input type="text" 
                                           class="form-control @error('representative_profession') is-invalid @enderror" 
                                           name="representative_profession"
                                           value="{{ old('representative_profession', $athlete->primaryRepresentative?->representative->profession) }}">
                                    @error('representative_profession')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

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
        }

        .timeline {
            position: relative;
            padding: 1rem 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0.75rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            padding-left: 2.5rem;
            padding-bottom: 1.5rem;
        }

        .timeline-marker {
            position: absolute;
            left: 0;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #e9ecef;
        }

        .timeline-content {
            padding: 0.5rem 0;
        }

        /* Estilos para validación */
        .was-validated .form-control:invalid,
        .form-control.is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .was-validated .form-control:valid,
        .form-control.is-valid {
            border-color: #198754;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        .was-validated :invalid ~ .invalid-feedback,
        .is-invalid ~ .invalid-feedback {
            display: block;
        }
    </style>
@stop


@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Código existente...

        // Validación del formulario
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })

        // Validación en tiempo real
        const validations = {
            full_name: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/,
            identity_document: /^[VEJ]-?\d{6,8}$/i,
            phone: /^[0-9+\-()\s]*$/,
            emergency_contact_name: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/,
            emergency_contact_phone: /^[0-9+\-()\s]*$/,
            emergency_contact_relation: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/,
            representative_name: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/,
            representative_identity_document: /^[VEJ]-?\d{6,8}$/i,
            representative_phone: /^[0-9+\-()\s]*$/
        }

        Object.keys(validations).forEach(fieldId => {
            const field = document.getElementById(fieldId)
            if (field) {
                field.addEventListener('input', function() {
                    this.value = this.value.replace(new RegExp(`[^${validations[fieldId].source}]`, 'g'), '')
                })
            }
        })

        // Validación de fecha de nacimiento
        const birthDateInput = document.getElementById('birth_date')
        if (birthDateInput) {
            birthDateInput.addEventListener('change', function() {
                const minDate = new Date()
                minDate.setFullYear(minDate.getFullYear() - 3)
                
                if (new Date(this.value) > minDate) {
                    this.setCustomValidity('El atleta debe tener al menos 3 años')
                } else {
                    this.setCustomValidity('')
                }
            })
        }

        // Validación de campos numéricos
        const numericValidations = {
            height: { min: 50, max: 300 },
            current_weight: { min: 10, max: 500 }
        }

        Object.entries(numericValidations).forEach(([fieldId, { min, max }]) => {
            const field = document.getElementById(fieldId)
            if (field) {
                field.addEventListener('input', function() {
                    const value = parseFloat(this.value)
                    if (value < min || value > max) {
                        this.setCustomValidity(`El valor debe estar entre ${min} y ${max}`)
                    } else {
                        this.setCustomValidity('')
                    }
                })
            }
        })
    })
</script>
@stop


