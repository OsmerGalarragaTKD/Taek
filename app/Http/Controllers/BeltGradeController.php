<?php

namespace App\Http\Controllers;

use App\Models\BeltGrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BeltGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $belts = BeltGrade::all();
        return view('belts.index', compact('belts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('belts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

            BeltGrade::create([
                'type' => $request->type,
                'level' => $request->level,
                'name' => $request->name,
                'color' => $request->color,
                'description' => $request->description,
            ]);


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
        $belts = BeltGrade::findOrFail($id);
        return view('belts.show', compact('belts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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
        try {
            DB::beginTransaction();

            $belt = BeltGrade::findOrFail($id);

            $belt->delete();

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
