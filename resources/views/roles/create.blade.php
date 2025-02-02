@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-0">
                                    <i class="bi bi-shield-plus me-2"></i>
                                    Crear Nuevo Rol
                                </h5>
                            </div>
                            <div class="col text-end">
                                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('roles.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="name" class="form-label">Nombre del Rol</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label d-block">Permisos</label>
                                @foreach ($groupedPermissions as $module => $permissions)
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <div class="form-check">
                                                <input class="form-check-input module-checkbox" type="checkbox"
                                                    id="module_{{ $module }}" data-module="{{ $module }}">
                                                <label class="form-check-label" for="module_{{ $module }}">
                                                    <strong>{{ ucfirst($module) }}</strong>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach ($permissions as $permission)
                                                    <div class="col-md-3 mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input permission-checkbox"
                                                                type="checkbox" name="permissions[]"
                                                                value="{{ $permission->id }}"
                                                                id="permission_{{ $permission->id }}"
                                                                data-module="{{ $module }}"
                                                                {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="permission_{{ $permission->id }}">
                                                                {{ $permission->description ?? $permission->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @error('permissions')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>
                                    Guardar Rol
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Manejar checkbox de módulos
            document.querySelectorAll('.module-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const module = this.dataset.module;
                    const modulePermissions = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
                    modulePermissions.forEach(permission => {```tsx file="resources/views/admin/roles/create.blade.php" continued...
                    modulePermissions.forEach(permission => {
                        permission.checked = this.checked;
                    });
                });
            });
        
            // Actualizar checkbox de módulo basado en permisos individuales
            document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const module = this.dataset.module;
                    const moduleCheckbox = document.querySelector(`#module_${module}`);
                    const modulePermissions = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
                    const allChecked = Array.from(modulePermissions).every(permission => permission.checked);
                    moduleCheckbox.checked = allChecked;
                });
            });
        
            // Verificar estado inicial de los módulos
            document.querySelectorAll('.module-checkbox').forEach(function(moduleCheckbox) {
                const module = moduleCheckbox.dataset.module;
                const modulePermissions = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
                const allChecked = Array.from(modulePermissions).every(permission => permission.checked);
                moduleCheckbox.checked = allChecked;
            });
        });
        </script>
    });

    // Actualizar checkbox de módulo basado en permisos individuales
    document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const module = this.dataset.module;
            const moduleCheckbox = document.querySelector(`
                                                            #module_$ {
                                                                module
                                                            }
                                                            `);
            const modulePermissions = document.querySelectorAll(`.permission - checkbox[data - module = "${module}"] `);
            const allChecked = Array.from(modulePermissions).every(permission => permission.checked);
            moduleCheckbox.checked = allChecked;
        });
    });

    // Verificar estado inicial de los módulos
    document.querySelectorAll('.module-checkbox').forEach(function(moduleCheckbox) {
        const module = moduleCheckbox.dataset.module;
        const modulePermissions = document.querySelectorAll(`.permission - checkbox[data - module = "${module}"] `);
                const allChecked = Array.from(modulePermissions).every(permission => permission.checked);
                moduleCheckbox.checked = allChecked;
            });
        });
        </script>
    @endpush
@endsection
