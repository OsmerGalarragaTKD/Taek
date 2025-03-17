<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index()
    {
        
        try {
            $roles = Role::with('permissions')->get();
            $users = User::all();
            return view('roles.index', compact('roles', 'users'));
        } catch (\Exception $e) {
            Log::error('Error al obtener roles: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar la lista de roles');
        }
    }

    public function create()
    {
        try {
            $permissions = Permission::all();
            $groupedPermissions = $permissions->groupBy(function ($permission) {
                return explode('_', $permission->name)[1];
            });
            return view('roles.create', compact('permissions', 'groupedPermissions'));
        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de creación: ' . $e->getMessage());
            return redirect()->route('roles.index')->with('error', 'Error al cargar el formulario');
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
        ]);



        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();


            $role = Role::create(['name' => $request->name]);
            $role->syncPermissions($request->permissions);

            SystemLogController::log(
                'crear',
                'Role',
                $role->id,
                'Creado nuevo rol: ' . $role->name
            );

            DB::commit();
            Log::info("Rol creado: {$role->name}");
            return redirect()->route('roles.index')
                ->with('success', 'Rol creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear rol: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al crear el rol: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            // Buscar el rol por su ID
            $role = Role::with('permissions')->findOrFail($id);
    
            // Obtener todos los permisos y agruparlos por módulo
            $permissions = Permission::orderBy('name')->get();
            $groupedPermissions = $permissions->groupBy(function ($permission) {
                // Dividir el nombre del permiso por '_'
                $parts = explode('_', $permission->name);
    
                // Si no hay suficientes partes, usar 'otros' como grupo
                return count($parts) > 1 ? $parts[1] : 'otros';
            });
    
            // Retornar la vista con los datos
            return view('roles.edit', compact('role', 'permissions', 'groupedPermissions'));
        } catch (\Exception $e) {
            // Registrar el error y redirigir con un mensaje
            Log::error("Error al editar rol ID {$id}: " . $e->getMessage());
            return redirect()->route('roles.index')->with('error', 'Rol no encontrado');
        }
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        if ($role->name === 'Super Admin') {
            return redirect()->route('roles.index')
                ->with('error', 'El rol Super Admin no puede ser modificado.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'required|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Actualizar el nombre del rol
            $role->update(['name' => $request->name]);

            // Obtener los nombres de los permisos en lugar de los IDs
            $permissions = Permission::whereIn('id', $request->permissions)
                ->pluck('name')
                ->toArray();

            // Sincronizar los permisos usando nombres
            $role->syncPermissions($permissions);

            SystemLogController::log(
                'actualizar',
                'Role',
                $role->id,
                'Actualizado rol: ' . $role->name
            );

            DB::commit();
            Log::info("Rol actualizado: {$role->name}");

            return redirect()->route('roles.index')
                ->with('success', 'Rol actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar rol ID {$id}: " . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error al actualizar el rol: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $role = Role::findOrFail($id);
            $roleName = $role->name;
            $role->delete();

            SystemLogController::log(
                'eliminar',
                'Role',
                $id,
                'Eliminado rol: ' . $roleName
            );

            DB::commit();
            Log::info("Rol eliminado: {$roleName}");
            return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al eliminar rol ID {$id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar el rol');
        }
    }

    public function assignRole(Request $request, User $user)
    {
        try {
            $request->validate([
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id',
            ]);

            // Verificar si se está quitando el rol Super Admin al único super admin
            if ($user->hasRole('Super Admin') && !in_array(Role::where('name', 'Super Admin')->first()->id, $request->roles)) {
                $superAdminCount = User::role('Super Admin')->count();
                if ($superAdminCount <= 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Debe existir al menos un usuario con rol Super Admin.'
                    ], 422);
                }
            }

            $user->syncRoles(Role::whereIn('id', $request->roles)->get());

            SystemLogController::log(
                'actualizar',
                'User',
                $user->id,
                'Actualizados roles para usuario: ' . $user->name
            );

            return response()->json([
                'success' => true,
                'message' => 'Roles asignados correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar roles: ' . $e->getMessage()
            ], 500);
        }
    }
}
