<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\AthleteGrade;
use App\Models\AthleteRepresentatives;
use App\Models\BeltGrade;
use App\Models\Representative;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AthleteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $athletes = Athlete::all();
        return view('athlete.index', compact('athletes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('athlete.create');
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

            Athlete::create([
                'full_name' => $request->full_name,
                'identity_document' => $request->identity_document,
                'nationality' => $request->nationality,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'email' => $request->email,
                'phone' => $request->phone,
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

            return redirect()->route('athlete.index')
                ->with('success', 'Atleta registrado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al ingresar atleta: ' . $e->getMessage());

            return back()
                ->with('error', 'Error al registrar al atleta: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $athlete = Athlete::with([
            'currentGrade.grade',
            'primaryRepresentative.representative',
            'grades.grade'
        ])->findOrFail($id);

        $beltGrades = BeltGrade::orderBy('type')->orderBy('level')->get();
        $isMinor = $athlete->birth_date ? Carbon::parse($athlete->birth_date)->age < 18 : false;

        return view('athlete.show', compact('athlete', 'beltGrades', 'isMinor'));
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
        $athlete = Athlete::findOrFail($id);

        // Determine if athlete is minor
        $isMinor = $athlete->birth_date ? Carbon::parse($athlete->birth_date)->age < 18 : false;

        // Build validation rules
        $rules = [
            'full_name' => 'required|string|max:255',
            'identity_document' => 'nullable|string|max:20',
            'nationality' => 'nullable|string|in:Venezolano,Extranjero',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'gender' => 'required|in:M,F',
            'civil_status' => 'nullable|string|in:Soltero,Casado,Divorciado,Viudo',
            'profession' => 'nullable|string|max:255',
            'academic_level' => 'nullable|string|in:Primaria,Secundaria,Técnico,Universitario,Postgrado',
            'institution' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'social_media' => 'nullable|string|max:255',
            'address_state' => 'nullable|string|max:100',
            'address_city' => 'nullable|string|max:100',
            'address_details' => 'nullable|string|max:255',
            'passport_number' => 'nullable|string|max:50',
            'passport_expiry' => 'nullable|date',
            'height' => 'nullable|numeric|between:0,300',
            'current_weight' => 'nullable|numeric|between:0,500',
            'shirt_size' => 'nullable|string|in:XS,S,M,L,XL,XXL',
            'pants_size' => 'nullable|string|max:10',
            'shoe_size' => 'nullable|string|max:10',
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',

            // Belt grade validation
            'belt_grade_id' => 'nullable|exists:belt_grades,id',
            'grade_date_achieved' => 'required_with:belt_grade_id|date',
            'grade_certificate_number' => 'required_with:belt_grade_id|string|max:50',
        ];

        // Add representative validation rules if athlete is minor
        if ($isMinor) {
            $representativeRules = [
                'representative_name' => 'required|string|max:255',
                'representative_identity_document' => 'required|string|max:20',
                'representative_relationship' => 'required|string|in:Padre,Madre,Tutor',
                'representative_nationality' => 'required|string|in:Venezolano,Extranjero',
                'representative_birth_date' => 'required|date',
                'representative_profession' => 'nullable|string|max:255',
                'representative_phone' => 'required|string|max:20',
                'representative_email' => 'nullable|email|max:255',
            ];
            $rules = array_merge($rules, $representativeRules);
        }

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            DB::beginTransaction();
            
            // Update athlete basic information
            $athlete->update($request->only([
                'full_name', 'identity_document', 'nationality', 'birth_date',
                'birth_place', 'gender', 'civil_status', 'profession',
                'academic_level', 'institution', 'email', 'phone',
                'social_media', 'address_state', 'address_city', 'address_details',
                'passport_number', 'passport_expiry', 'height', 'current_weight',
                'shirt_size', 'pants_size', 'shoe_size', 'medical_conditions',
                'allergies'
            ]));

           /*  dd([
                'athlete_id' => $athlete->id,
                'grade_id' => $request->belt_grade_id,
                'date_achieved' => $request->grade_date_achieved,
                'certificate_number' => $request->grade_certificate_number,
            ]); */
            
            // Create new grade
            AthleteGrade::create([
                'athlete_id' => $athlete->id,
                'grade_id' => $request->belt_grade_id,
                'date_achieved' => $request->grade_date_achieved,
                'certificate_number' => $request->grade_certificate_number,
            ]);
            

            // Handle representative information for minors
            if ($isMinor && $request->filled('representative_name')) {
                $representative = Representative::updateOrCreate(
                    ['identity_document' => $request->representative_identity_document],
                    [
                        'full_name' => $request->representative_name,
                        'nationality' => $request->representative_nationality,
                        'birth_date' => $request->representative_birth_date,
                        'profession' => $request->representative_profession,
                        'phone' => $request->representative_phone,
                        'email' => $request->representative_email,
                    ]
                );



                // Update or create primary representative relationship
                AthleteRepresentatives::updateOrCreate(
                    [
                        'athlete_id' => $athlete->id,
                        'is_primary' => true
                    ],
                    [
                        'representative_id' => $representative->id,
                        'relationship' => $request->representative_relationship,
                    ]
                );
            }

            DB::commit();
            return redirect()->route('athlete.show', $athlete->id)
                ->with('success', 'Información actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating athlete: ' . $e->getMessage());
            return back()
                ->with('error', 'Error al actualizar la información. Por favor, intente nuevamente.')
                ->withInput();
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
