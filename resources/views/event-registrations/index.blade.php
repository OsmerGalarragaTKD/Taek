@extends('adminlte::page')

@section('title', 'Registros de Eventos')

@section('content_header')
    <h1>Registros de Eventos</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Eventos Activos</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre del Evento</th>
                        <th>Tipo</th>
                        <th>Fecha Límite de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeEvents as $event)
                        <tr>
                            <td>{{ $event->name }}</td>
                            <td>{{ str_replace('_', ' ', $event->type == 'Promotion Test' ? 'Examen de Promocion' : 'Competicion') }}</td>
                            <td>{{ $event->registration_deadline->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('eventregistration.createEvent', $event) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus mr-1"></i> Registrar Atleta
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay eventos activos en este momento.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Registros Existentes</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Atleta</th>
                        <th>Evento</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Estado Pago</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $registration)
                        <tr>
                            <td>{{ $registration->athlete->full_name }}</td>
                            <td>{{ $registration->event->name }}</td>
                            <td>{{ $registration->category->name }}</td>
                            <td>{{ $registration->status === 'Registered' ? 'Registrado' : 'No Registrado' }}</td>
                            <td>{{ $registration->payment_status === 'Completed' ? 'Completado' : 'Pendiente' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay registros existentes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop