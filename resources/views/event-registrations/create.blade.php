@extends('adminlte::page')

@section('title', 'Registrar en Evento')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Registrar Atleta en Evento: {{ $event->name }}</h1>
        <a href="{{ route('eventregistration.index') }}" class="btn btn-secondary">
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
                <form action="{{ route('eventregistration.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">

                    <div class="card-body">
                        <div class="form-group">
                            <label for="athlete_id">Atleta</label>
                            <select name="athlete_id" id="athlete_id" class="form-control select2" required>
                                <option value="">Seleccione un atleta</option>
                                @foreach($athletes as $athlete)
                                    <option value="{{ $athlete->id }}">{{ $athlete->full_name }} - {{ $athlete->identity_document }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="category_id">Categoría</label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">Seleccione una categoría</option>
                                @if(!empty($categories) && $categories->count() > 0)
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @else
                                    <option value="">No hay categorías disponibles</option>
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="notes">Notas</label>
                            <textarea name="notes" id="notes" rows="3" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Registrar en Evento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop