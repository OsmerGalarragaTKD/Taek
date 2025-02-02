@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Asignar Rol a Usuario</h1>
    
    <form method="POST" action="{{ route('users.assign-role') }}">
        @csrf
        
        <div class="mb-3">
            <label for="user_id" class="form-label">Seleccionar Usuario</label>
            <select class="form-select" id="user_id" name="user_id" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Roles Disponibles:</label>
            @foreach($roles as $role)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}">
                <label class="form-check-label">{{ $role->name }}</label>
            </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Asignar Roles</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection