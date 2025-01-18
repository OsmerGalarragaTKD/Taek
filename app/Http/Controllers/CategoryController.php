<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Return_;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'min_age' => 'required|integer|min:0',
            'max_age' => 'required|integer|min:0|gt:min_age',
            'min_weight' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0|gt:min_weight',
            'gender' => 'nullable|string',
            'disability_type' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $category = Category::create([
                'name' => $request->name,
                'type' => $request->type,
                'min_age' => $request->min_age,
                'max_age' => $request->max_age,
                'min_weight' => $request->min_weight,
                'max_weight' => $request->max_weight,
                'gender' => $request->gender,
                'disability_type' => $request->disability_type,
                'description' => $request->description,
            ]);

            DB::commit();

            return redirect()->route('categories.index')
                ->with('success', 'Categoria registrado exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error al crear categoria: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error al registrar la categoria: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::find($id);
        return view('categories.update', compact('person'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'min_age' => 'required|integer|min:0',
            'max_age' => 'required|integer|min:0|gt:min_age',
            'min_weight' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0|gt:min_weight',
            'gender' => 'nullable|string',
            'disability_type' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {

            DB::beginTransaction();

            $category = Category::findOrFail($id);


            $category->update($request->only([
                'name', 'type', 'min_age','max_age',
                'min_weight','max_weight','gender','disability_type',
                'description',]));


            DB::commit();

            return redirect()->route('categories.index')
                ->with('success', 'Categoria registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error al crear categoria: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error al actualizar la categoria: ' . $e->getMessage())
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

            $category = Category::findOrFail($id);

            $category->delete();

            DB::commit();

            return redirect()->route('categories.index')
                ->with('success', 'Categoria eliminada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error al eliminar categoria: ". $e->getMessage());

            return redirect()->back()
                ->with('success', 'Error al eliminar la Categoria.');
        }
    }
}
