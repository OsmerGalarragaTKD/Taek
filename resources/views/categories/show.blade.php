@extends('layouts.app')

@section('title', 'Detalles de la Categoría')

@section('content_header')
    <h1 class="m-0 text-dark">Detalles de la Categoría</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Información de la Categoría</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="mb-3"><strong>Nombre:</strong> {{ $category->name }}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tipo:</strong> {{ $category->type }}</p>
                            <p><strong>Edad Mínima:</strong> {{ $category->min_age }}</p>
                            <p><strong>Edad Máxima:</strong> {{ $category->max_age }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Peso Mínimo:</strong> {{ $category->min_weight ?? 'N/A' }} kg</p>
                            <p><strong>Peso Máximo:</strong> {{ $category->max_weight ?? 'N/A' }} kg</p>
                            <p><strong>Género:</strong> {{ $category->gender ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <p><strong>Tipo de Discapacidad:</strong> {{ $category->disability_type ?? 'N/A' }}</p>
                    <p><strong>Descripción:</strong> {{ $category->description ?? 'N/A' }}</p>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editCategoryModal">
                        <i class="fas fa-edit"></i> Editar Categoría
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar la categoría -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Editar Categoría</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <!-- Nombre -->
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $category->name }}" required>
                        </div>
                        <!-- Tipo -->
                        <div class="form-group">
                            <label for="type">Tipo</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="Kyorugui" {{ $category->type == 'Kyorugui' ? 'selected' : '' }}>Kyorugui</option>
                                <option value="Poomsae" {{ $category->type == 'Poomsae' ? 'selected' : '' }}>Poomsae</option>
                                <option value="Para-Kyorugui" {{ $category->type == 'Para-Kyorugui' ? 'selected' : '' }}>Para-Kyorugui</option>
                                <option value="Para-Poomsae" {{ $category->type == 'Para-Poomsae' ? 'selected' : '' }}>Para-Poomsae</option>
                            </select>
                        </div>
                        <!-- Edad -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="min_age">Edad Mínima</label>
                                    <input type="number" name="min_age" id="min_age" class="form-control" value="{{ $category->min_age }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_age">Edad Máxima</label>
                                    <input type="number" name="max_age" id="max_age" class="form-control" value="{{ $category->max_age }}" required>
                                </div>
                            </div>
                        </div>
                        <!-- Peso -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="min_weight">Peso Mínimo (kg)</label>
                                    <input type="number" step="0.01" name="min_weight" id="min_weight" class="form-control" value="{{ $category->min_weight }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_weight">Peso Máximo (kg)</label>
                                    <input type="number" step="0.01" name="max_weight" id="max_weight" class="form-control" value="{{ $category->max_weight }}">
                                </div>
                            </div>
                        </div>
                        <!-- Género -->
                        <div class="form-group">
                            <label for="gender">Género</label>
                            <select name="gender" id="gender" class="form-control">
                                <option value="M" {{ $category->gender == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ $category->gender == 'F' ? 'selected' : '' }}>Femenino</option>
                                <option value="Mixed" {{ $category->gender == 'Mixed' ? 'selected' : '' }}>Mixto</option>
                            </select>
                        </div>
                        <!-- Discapacidad -->
                        <div class="form-group">
                            <label for="disability_type">Tipo de Discapacidad</label>
                            <input type="text" name="disability_type" id="disability_type" class="form-control" value="{{ $category->disability_type }}">
                        </div>
                        <!-- Descripción -->
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ $category->description }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cerrar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@push('css')
@endpush

@push('js')
@endpush