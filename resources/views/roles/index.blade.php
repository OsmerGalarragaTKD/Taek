@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Roles Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-0">
                                    <i class="bi bi-shield-lock me-2"></i>
                                    Roles y Permisos
                                </h5>
                            </div>
                            <div class="col text-end">
                                <a href="{{ route('roles.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Nuevo Rol
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Rol</th>
                                        <th>Permisos</th>
                                        <th>Usuarios</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td class="align-middle">
                                                <strong>{{ $role->name }}</strong>
                                            </td>
                                            <td>
                                                <div style="max-height: 100px; overflow-y: auto;">
                                                    @foreach ($role->permissions as $permission)
                                                        <span class="badge bg-info me-1 mb-1">
                                                            {{ $permission->description ?? $permission->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                {{ $role->users->count() }} usuarios
                                            </td>
                                            <td class="text-end">
                                                @if ($role->name !== 'Super Admin')
                                                    <a href="{{ route('roles.edit', $role->id) }}"
                                                        class="btn btn-sm btn-warning me-2">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('¿Está seguro de eliminar este rol?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Users Section -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-people me-2"></i>
                            Usuarios y sus Roles
                        </h5>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Email</th>
                                        <th>Roles</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td class="align-middle">{{ $user->name }}</td>
                                            <td class="align-middle">{{ $user->email }}</td>
                                            <td>
                                                @foreach ($user->roles as $role)
                                                    <span class="badge bg-primary me-1">{{ $role->name }}</span>
                                                @endforeach
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#assignRoleModal" data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}"
                                                    data-user-roles="{{ $user->roles->pluck('id') }}">
                                                    <i class="bi bi-pencil"></i>
                                                    Asignar Roles
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para asignar roles -->
    <div class="modal fade" id="assignRoleModal" tabindex="-1" aria-labelledby="assignRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="assignRoleForm" method="POST">
                    @csrf
                    <meta name="csrf-token" content="{{ csrf_token() }}">

                    <div class="modal-header">
                        <h5 class="modal-title" id="assignRoleModalLabel">Asignar Roles</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>Asignando roles para: <strong id="modalUserName"></strong></p>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Roles disponibles:</label>
                            <div class="role-checkboxes">
                                @foreach ($roles as $role)
                                    <div class="form-check">
                                        <input class="form-check-input role-checkbox" type="checkbox" name="roles[]"
                                            value="{{ $role->id }}" id="role{{ $role->id }}">
                                        <label class="form-check-label" for="role{{ $role->id }}">
                                            {{ $role->name }}
                                            {{-- <small class="hidden d-block text-muted">
                                                @foreach ($role->permissions as $permission)
                                                    <span class="badge bg-light text-dark me-1">
                                                        {{ $permission->description ?? $permission->name }}
                                                    </span>
                                                @endforeach
                                            </small> --}}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="saveChangesButton">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const assignRoleModal = document.getElementById('assignRoleModal');
            const assignRoleForm = document.getElementById('assignRoleForm');

            assignRoleModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const userId = button.getAttribute('data-user-id');
                const userName = button.getAttribute('data-user-name');
                const userRoles = JSON.parse(button.getAttribute('data-user-roles'));

                // Actualizar el formulario y contenido del modal
                assignRoleForm.action = `/roles/assign/${userId}`;
                document.getElementById('modalUserName').textContent = userName;

                // Resetear y marcar los checkboxes correspondientes
                document.querySelectorAll('.role-checkbox').forEach(checkbox => {
                    checkbox.checked = userRoles.includes(parseInt(checkbox.value));
                });
            });

            // Manejar el envío del formulario
            assignRoleForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const selectedRoles = document.querySelectorAll('.role-checkbox:checked');
                if (selectedRoles.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Por favor, seleccione al menos un rol'
                    });
                    return;
                }

                // Enviar el formulario usando fetch
                fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            roles: Array.from(selectedRoles).map(cb => cb.value)
                        })
                    })
                    .then(response => {
                        console.log(response);
                        if (!response.ok) {
                            throw new Error('Error en la solicitud');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: data.message,
                                timer: 5000, // Duración del mensaje en milisegundos (5 segundos)
                                timerProgressBar: true // Muestra una barra de progreso mientras el mensaje está visible
                            }).then(() => {
                                window.location.reload(); // Recargar la página después de guardar los cambios
                            });
                        } else {
                            throw new Error(data.message || 'Error al asignar roles');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message
                        });
                    });
            });
        });
    </script>
@endpush
@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const assignRoleModal = document.getElementById('assignRoleModal');
        const assignRoleForm = document.getElementById('assignRoleForm');

        assignRoleModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            const userRoles = JSON.parse(button.getAttribute('data-user-roles'));

            // Actualizar el formulario y contenido del modal
            assignRoleForm.action = `/roles/assign/${userId}`;
            document.getElementById('modalUserName').textContent = userName;

            // Resetear y marcar los checkboxes correspondientes
            document.querySelectorAll('.role-checkbox').forEach(checkbox => {
                checkbox.checked = userRoles.includes(parseInt(checkbox.value));
            });
        });

        // Manejar el envío del formulario
        assignRoleForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const selectedRoles = document.querySelectorAll('.role-checkbox:checked');
            if (selectedRoles.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor, seleccione al menos un rol'
                });
                return;
            }

            // Enviar el formulario usando fetch
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    roles: Array.from(selectedRoles).map(cb => cb.value)
                })
            })
            .then(response => {
                console.log(response);
                if (!response.ok) {
                    throw new Error('Error en la solicitud');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: data.message,
                        timer: 5000, // Duración del mensaje en milisegundos (5 segundos)
                        timerProgressBar: true // Muestra una barra de progreso mientras el mensaje está visible
                    }).then(() => {
                        window.location.reload(); // Recargar la página después de guardar los cambios
                    });
                } else {
                    throw new Error(data.message || 'Error al asignar roles');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message
                });
            });
        });
    });
</script>
@endpush
