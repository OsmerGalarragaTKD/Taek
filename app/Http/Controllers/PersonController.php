<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $people = Person::with('athlete')->paginate(10);
        return view('people.index', compact('people'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('people.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'identity_document' => 'nullable|string|max:20',
            'nationality' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:M,F',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'height' => 'nullable|numeric|between:0,300',
            'current_weight' => 'nullable|numeric|between:0,500',
            'shirt_size' => 'nullable|string|max:10',
            'pants_size' => 'nullable|string|max:10',
            'shoe_size' => 'nullable|string|max:10',
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
            
        try {
            DB::beginTransaction();
                
            // Crear persona
            $person = Person::create([
                'full_name' => $request->full_name,
                'identity_document' => $request->identity_document,
                'nationality' => $request->nationality,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            // Crear atleta
            Athlete::create([
                'athlete_id' => $person->id, // Corregido de athlete_id a person_id
                'height' => $request->height,
                'current_weight' => $request->current_weight,
                'shirt_size' => $request->shirt_size,
                'pants_size' => $request->pants_size,
                'shoe_size' => $request->shoe_size,
                'medical_conditions' => $request->medical_conditions,
                'allergies' => $request->allergies,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'emergency_contact_relation' => $request->emergency_contact_relation,
            ]);

            DB::commit();

            return redirect()->route('person.show', $person->id)
                ->with('success', 'Atleta registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear atleta: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error al registrar el atleta: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $person = Person::with('athlete')->findOrFail($id);
        return view('people.show', compact('person'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request ,string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'identity_document' => 'nullable|string|max:20',
            'nationality' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'gender' => 'required|in:M,F',
            'civil_status' => 'nullable|string|max:50',
            'profession' => 'nullable|string|max:100',
            'institution' => 'nullable|string|max:255',
            'academic_level' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'social_media' => 'nullable|string',
            'address_state' => 'nullable|string|max:100',
            'address_city' => 'nullable|string|max:100',
            'address_details' => 'nullable|string',
            'passport_number' => 'nullable|string|max:50',
            'passport_expiry' => 'nullable|date',
            // Campos del atleta
            'height' => 'nullable|numeric|between:0,300',
            'current_weight' => 'nullable|numeric|between:0,500',
            'shirt_size' => 'nullable|string|max:10',
            'pants_size' => 'nullable|string|max:10',
            'shoe_size' => 'nullable|string|max:10',
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:100',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        try {
            DB::beginTransaction();
    
            $person = Person::findOrFail($id);
            
            // Actualizar información de la persona
            $person->update($request->only([
                'full_name', 'identity_document', 'nationality', 'birth_date',
                'birth_place', 'gender', 'civil_status', 'profession',
                'institution', 'academic_level', 'email', 'phone',
                'social_media', 'address_state', 'address_city',
                'address_details', 'passport_number', 'passport_expiry'
            ]));
    
            // Actualizar información del atleta
            if ($person->athlete) {
                $person->athlete->update($request->only([
                    'height', 'current_weight', 'shirt_size', 'pants_size',
                    'shoe_size', 'medical_conditions', 'allergies',
                    'emergency_contact_name', 'emergency_contact_phone',
                    'emergency_contact_relation'
                ]));
            }
    
            DB::commit();
    
            return redirect()->route('person.show', $person->id)
                ->with('success', 'Información actualizada exitosamente.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al actualizar atleta: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error al actualizar la información: ' . $e->getMessage())
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
    
            $person = Person::findOrFail($id);
            
            // Eliminar el atleta asociado primero
            if ($person->athlete) {
                $person->athlete->delete();
            }
            
            // Eliminar la persona
            $person->delete();
    
            DB::commit();
    
            return redirect()->route('person.index')
                ->with('success', 'Atleta eliminado exitosamente.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al eliminar atleta: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error al eliminar el atleta: ' . $e->getMessage());
        }
    }
}
