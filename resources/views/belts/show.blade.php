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
                                <i class="bi bi-award me-2"></i>
                                Detalles del Grado
                            </h5>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('belts.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tipo de Grado</label>
                            <p>{{ $belts->type }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nivel</label>
                            <p>{{ $belts->level }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nombre</label>
                            <p>{{ $belts->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Color</label>
                            <p>{{ $belts->color ?? 'No especificado' }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Descripción</label>
                            <p>{{ $belts->description ?? 'Sin descripción' }}</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="mb-3">Atletas con este grado</h6>
                        @if($belts->athletes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Fecha de obtención</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($belts->athletes as $athlete)
                                            <tr>
                                                <td>{{ $athlete->athlete->full_name }}</td>
                                                <td>{{ $athlete->created_at->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No hay atletas con este grado actualmente.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection