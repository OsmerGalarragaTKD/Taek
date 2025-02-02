@extends('layouts.app')

@section('title', 'Crear Nueva Categoría')

@section('content_header')
    <h1 class="m-0 text-dark">Crear Nueva Categoría</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Complete el formulario</h3>
                </div>
                <!-- Formulario -->
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <!-- Nombre -->
                        <div class="form-group">
                            <label for="name">Nombre de la Categoría</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Ej: Categoría Juvenil" required>
                        </div>
                        <!-- Tipo -->
                        <div class="form-group">
                            <label for="type">Tipo de Categoría</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="Kyorugui">Kyorugui</option>
                                <option value="Poomsae">Poomsae</option>
                                <option value="Para-Kyorugui">Para-Kyorugui</option>
                                <option value="Para-Poomsae">Para-Poomsae</option>
                            </select>
                        </div>
                        <!-- Edad -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="min_age">Edad Mínima</label>
                                    <input type="number" name="min_age" id="min_age" class="form-control" placeholder="Ej: 12" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_age">Edad Máxima</label>
                                    <input type="number" name="max_age" id="max_age" class="form-control" placeholder="Ej: 18" required>
                                </div>
                            </div>
                        </div>
                        <!-- Peso -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="min_weight">Peso Mínimo (kg)</label>
                                    <input type="number" step="0.01" name="min_weight" id="min_weight" class="form-control" placeholder="Ej: 50.5">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_weight">Peso Máximo (kg)</label>
                                    <input type="number" step="0.01" name="max_weight" id="max_weight" class="form-control" placeholder="Ej: 70.0">
                                </div>
                            </div>
                        </div>
                        <!-- Género -->
                        <div class="form-group">
                            <label for="gender">Género</label>
                            <select name="gender" id="gender" class="form-control">
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                                <option value="Mixed">Mixto</option>
                            </select>
                        </div>
                        <!-- Discapacidad -->
                        <div class="form-group">
                            <label for="disability_type">Tipo de Discapacidad</label>
                            <input type="text" name="disability_type" id="disability_type" class="form-control" placeholder="Ej: Física">
                        </div>
                        <!-- Descripción -->
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Agregue una descripción opcional"></textarea>
                        </div>
                    </div>
                    <!-- Botones -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn btn-default">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
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