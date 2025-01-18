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
                                <i class="bi bi-file-text me-2"></i>
                                Nueva Solicitud de Documento
                            </h5>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('documents.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="template_id" class="form-label">Tipo de Documento</label>
                            <select class="form-select @error('template_id') is-invalid @enderror" 
                                    id="template_id" 
                                    name="template_id" 
                                    required>
                                <option value="">Seleccionar tipo...</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}" 
                                            {{ old('template_id') == $template->id ? 'selected' : '' }}>
                                        {{ $template->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('template_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="athlete_id" class="form-label">Atleta</label>
                            <select class="form-select @error('athlete_id') is-invalid @enderror" 
                                    id="athlete_id" 
                                    name="athlete_id" 
                                    required>
                                <option value="">Seleccionar atleta...</option>
                                @foreach($athletes as $athlete)
                                    <option value="{{ $athlete->id }}" 
                                            {{ old('athlete_id') == $athlete->id ? 'selected' : '' }}>
                                        {{ $athlete->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('athlete_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="event_id" class="form-label">Evento (Opcional)</label>
                            <select class="form-select @error('event_id') is-invalid @enderror" 
                                    id="event_id" 
                                    name="event_id">
                                <option value="">Seleccionar evento...</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" 
                                            {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('event_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>
                                Crear Solicitud
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection