<?php

namespace App\Http\Controllers;

use App\Models\BeltGrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class BeltGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->can('ver_cinturones')) {
            return redirect()->back()->with('error', 'No tienes permiso para ver cinturones.');
        }

        $belts = BeltGrade::all();
        return view('belts.index', compact('belts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->can('crear_cinturones')) {
            return redirect()->back()->with('error', 'No tienes permiso para crear cinturones.');
        }

        return view('belts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('crear_cinturones')) {
            return redirect()->back()->with('error', 'No tienes permiso para crear cinturones.');
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:KUP,POOM,DAN', 
            'level' => 'required|integer|min:1', 
            'name' => 'required|string|max:50',
            'color' => 'nullable|string|max:30',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $belt = BeltGrade::create([
                'type' => $request->type,
                'level' => $request->level,
                'name' => $request->name,
                'color' => $request->color,
                'description' => $request->description,
            ]);

            SystemLogController::log(
                'crear',
                'BeltGrade',
                $belt->id,
                'Creado nuevo grado de cinturón: ' . $request->name
            );

            DB::commit();

            return redirect()->route('belts.index')
                ->with('success', 'Grado creado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear grado: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error al crear el grado')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!Auth::user()->can('ver_cinturones')) {
            return redirect()->back()->with('error', 'No tienes permiso para ver cinturones.');
        }

        $belts = BeltGrade::findOrFail($id);
        return view('belts.show', compact('belts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!Auth::user()->can('editar_cinturones')) {
            return redirect()->back()->with('error', 'No tienes permiso para editar cinturones.');
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!Auth::user()->can('editar_cinturones')) {
            return redirect()->back()->with('error', 'No tienes permiso para editar cinturones.');
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:KUP,POOM,DAN',
            'level' => 'required|integer|min:1',
            'name' => 'required|string|max:50',
            'color' => 'nullable|string|max:30',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $belt = BeltGrade::findOrFail($id);

            $belt->update($request->only([
                'type',
                'level',
                'name',
                'color',
                'description'
            ]));

            SystemLogController::log(
                'actualizar',
                'BeltGrade',
                $belt->id,
                'Actualizado grado de cinturón: ' . $belt->name
            );

            DB::commit();

            return redirect()->route('belts.index')
                ->with('success', 'Grado actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al actualizar grado: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error al crear el grado')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Auth::user()->can('eliminar_cinturones')) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar cinturones.');
        }

        try {
            DB::beginTransaction();

            $belt = BeltGrade::findOrFail($id);

            $belt->delete();

            SystemLogController::log(
                'eliminar',
                'BeltGrade',
                $belt->id,
                'Eliminado grado de cinturón: ' . $belt->name
            );

            DB::commit();

            return redirect()->route('belts.index')
                ->with('success', 'Cinturon eliminada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error al eliminar Cinturon: ". $e->getMessage());

            return redirect()->back()
                ->with('success', 'Error al eliminar la Cinturon.');
        }
    }
}
