@extends('adminlte::page')

@section('title', 'Registrar en Evento')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Registrar Atleta en Evento</h1>
        <a href="{{ route('events.show', $event->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
@stop

@section('content')
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle mr-2"></i>
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
                        <div class="col-sm-6">
                            <dl>
                                <dt>Nombre del Evento</dt>
                                <dd>{{ $event->name }}</dd>

                                <dt>Tipo</dt>
                                <dd>{{ str_replace('_', ' ', $event->type) }}</dd>

                                <dt>Sede</dt>
                                <dd>{{ $event->venue->name ?? 'No especificada' }}</dd>
                            </dl>
                        </div>
                        <div class="col-sm-6">
                            <dl>
                                <dt>Fecha de Inicio</dt>
                                <dd>{{ $event->start_date ? $event->start_date->format('d/m/Y') : 'No definida' }}</dd>

                                <dt>Fecha de Fin</dt>
                                <dd>{{ $event->end_date ? $event->end_date->format('d/m/Y') : 'No definida' }}</dd>

                                <dt>Fecha Límite de Registro</dt>
                                <dd>{{ $event->registration_deadline ? $event->registration_deadline->format('d/m/Y') : 'No definida' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Formulario de Registro</h3>
                </div>
                <form action="{{ route('event-registrations.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">

                    <div class="card-body">
                        @if(!$event->registration_deadline || $event->registration_deadline->isPast())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                El período de registro para este evento ha finalizado.
                            </div>
                        @else
                            <div class="form-group">
                                <label for="athlete_id">Atleta</label>
                                <select name="athlete_id" id="athlete_id" class="form-control select2 @error('athlete_id') is-invalid @enderror" required>
                                    <option value="">Seleccione un atleta</option>
                                    @foreach($athletes as $athlete)
                                        <option value="{{ $athlete->id }}" {{ old('athlete_id') == $athlete->id ? 'selected' : '' }}>
                                            {{ $athlete->full_name }} - {{ $athlete->identity_document }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('athlete_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="category_id">Categoría</label>
                                <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                    <option value="">Seleccione una categoría</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="notes">Notas</label>
                                <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" 
                                {{ (!$event->registration_deadline || $event->registration_deadline->isPast()) ? 'disabled' : '' }}>
                            <i class="fas fa-save mr-1"></i> Registrar en Evento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link href="{{asset('css/select2.min.css')}}" rel="stylesheet" />
    <link href="{{asset('css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@stop

@section('js')
    <script src="{{asset('js/select2.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: 'Seleccione un atleta'
            });
        });
    </script>
@stop