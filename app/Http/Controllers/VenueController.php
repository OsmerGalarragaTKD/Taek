<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $venues = Venue::all();
        return view('venues.index', compact('venues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('venues.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'address_state' => 'nullable|string|max:255',
            'address_city' => 'nullable|string|max:255',
            'address_parish' => 'nullable|string|max:255',
            'founding_date' => [
                'nullable',
                'date',
                'before_or_equal:' . now()->subYears(2)->format('Y-m-d'), // Máximo 200 años atrás
                'after_or_equal:' . now()->subYears(100)->format('Y-m-d'), // Máximo 200 años atrás
            ],
            'address_details' => 'nullable|string|max:1000',
            'director_name' => 'nullable|string|max:255',
            'phone' => [
                'nullable',
                'string',
                'regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/',
            ],
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:Active,Inactive',
        ];
    
        // Mensajes personalizados para las validaciones
        $messages = [
            'founding_date.before_or_equal' => 'La fecha de fundación no puede ser mayor a 200 años atrás.',
            'founding_date.after_or_equal' => 'La fecha de fundación no puede ser mayor a 200 años atrás.',
            'phone.regex' => 'El formato del teléfono no es válido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        try {
            DB::beginTransaction();

            $venue = Venue::create([
                'name' => $request->name,
                'address_state' => $request->address_state,
                'address_city' => $request->address_city,
                'address_parish' => $request->address_parish,
                'address_details' => $request->address_details,
                'founding_date' => $request->founding_date,
                'director_name' => $request->director_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'status' => $request->status,
            ]);

            SystemLogController::log(
                'crear',
                'Venue',
                $venue->id,
                'Creada nueva sede: ' . $request->name
            );

            DB::commit();

            return redirect()->route('venues.index')
                ->with('success', 'Sede creada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error al ingresar : ". $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create venue');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $venue = Venue::findOrFail($id);
        return view('venues.show', compact('venue'));
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
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'address_state' => 'nullable|string',
            'address_city' => 'nullable|string',
            'address_parish' => 'nullable|string',
            'address_details' => 'nullable|string',
            'founding_date' => 'nullable|date',
            'director_name' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'status' => 'required|in:Active,Inactive',
        ]);



        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        try {
            DB::beginTransaction();

            $venue = Venue::findOrFail($id);

            $venue->update([
                'name' => $request->name,
                'address_state' => $request->address_state,
                'address_city' => $request->address_city,
                'address_parish' => $request->address_parish,
                'address_details' => $request->address_details,
                'founding_date' => $request->founding_date,
                'director_name' => $request->director_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'status' => $request->status,
            ]);

            SystemLogController::log(
                'actualizar',
                'Venue',
                $venue->id,
                'Actualizada sede: ' . $venue->name
            );

            DB::commit();

            return redirect()->route('venues.index')
                ->with('success', 'Sede actualizada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error al actualizar : ". $e->getMessage());

            return redirect()->back()
                ->with('error', 'Fallo al actualizar Sede');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
