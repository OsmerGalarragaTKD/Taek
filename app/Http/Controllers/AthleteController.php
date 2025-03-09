<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\AthleteGrade;
use App\Models\AthleteRepresentatives;
use App\Models\BeltGrade;
use App\Models\Representative;
use App\Models\User;
use App\Models\Venue;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Http\Controllers\SystemLogController; // Added import



class AthleteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $athletes = Athlete::with(['currentGrade.grade', 'primaryRepresentative.representative'])
                ->get()
                ->each(function ($athlete) {
                    // Calcular edad dinámicamente
                    $athlete->age = $athlete->birth_date ? $athlete->birth_date->age : 'N/A';
                });

            return view('athlete.index', compact('athletes'));
        } catch (\Exception $e) {
            Log::error('Error en index de atletas: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar la lista de atletas.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $beltGrades = BeltGrade::orderBy('type')->orderBy('level')->get();
            $venues = Venue::where('status', 'active')->get();
            return view('athlete.create', compact('beltGrades', 'venues'));
        } catch (\Exception $e) {
            Log::error('Error en create de atletas: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar el formulario de creación.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $minDate = Carbon::now()->subYears(3)->format('Y-m-d');
        $maxDate = Carbon::now()->subYears(100)->format('Y-m-d');


        $rules = [
            'venue_id' => 'required|exists:venues,id', // Añade exists para validar integridad
            'full_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\s]+$/u',
            ],
            'identity_document' => [
                'nullable',
                'string',
                'regex:/^[VEJ]-?\d{6,8}$/i',
                'unique:athletes,identity_document'
            ],
            'nationality' => 'nullable|string|in:Venezolano,Extranjero',
            'birth_date' => [
                'required',
                'date',
                'before_or_equal:' . $minDate,
                'after_or_equal:' . $maxDate,
            ],
            'gender' => 'required|in:M,F',
            'email' => 'nullable|email|max:255|unique:users,email',
            'phone' => [
                'nullable',
                'string',
                'max:15',
                'regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/'
            ],
            'height' => 'nullable|numeric|between:50,300',
            'current_weight' => 'nullable|numeric|between:10,500',
            'shirt_size' => 'nullable|string|in:XS,S,M,L,XL,XXL',
            'pants_size' => 'nullable|string|max:10',
            'shoe_size' => 'nullable|string|max:10',
            'medical_conditions' => 'nullable|string|max:1000',
            'allergies' => 'nullable|string|max:1000',
            'emergency_contact_name' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[\pL\s]+$/u'
            ],
            'emergency_contact_phone' => [
                'nullable',
                'string',
                'max:15',
                'regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/'
            ],
            'emergency_contact_relation' => 'nullable|string|max:50',
        ];

        $messages = [
            'full_name.regex' => 'El nombre solo puede contener letras y espacios.',
            'identity_document.regex' => 'El formato del documento de identidad no es válido (ej: V-12345678).',
            'identity_document.unique' => 'Este documento de identidad ya está registrado.',
            'birth_date.before_or_equal' => 'El atleta debe tener al menos 3 años de edad.',
            'birth_date.after_or_equal' => 'El atleta debe tener maximo 100 años de edad.',
            'grade_date_achieved.before_or_equal' => 'La fecha de obtención del grado no puede ser futura.',
            'phone.regex' => 'El formato del teléfono no es válido.',
            'emergency_contact_name.regex' => 'El nombre del contacto solo puede contener letras y espacios.',
            'emergency_contact_phone.regex' => 'El formato del teléfono de emergencia no es válido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }



        try {
            DB::beginTransaction();

            $athlete = Athlete::create($request->all());
            $grade_date_achieved = now();

            AthleteGrade::create([
                'athlete_id' => $athlete->id,
                'grade_id' => 1, // ID del grado blanco
                'date_achieved' => $grade_date_achieved,
                'certificate_number' => $request->grade_certificate_number,
            ]);

            if ($request->filled('email')) {
                $temporaryPassword = 'password';
                $user = User::create([
                    'name' => $request->full_name,
                    'email' => $request->email,
                    'password' => Hash::make($temporaryPassword)
                ]);

                $user->assignRole('athlete');

                // Aquí se podría enviar un email con la contraseña temporal
            }

            SystemLogController::log(
                'crear',
                'Athlete',
                $athlete->id,
                'Creado nuevo atleta: ' . $athlete->full_name
            );

            DB::commit();

            return redirect()->route('athlete.index')
                ->with('success', 'Atleta registrado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar atleta: ' . $e->getMessage());
            return back()
                ->with('error', 'Error al registrar al atleta. Por favor, intente nuevamente.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $athlete = Athlete::with([
                'currentGrade.grade',
                'primaryRepresentative.representative',
                'grades.grade',
                'athletesRepresenting.athlete'
            ])->findOrFail($id);

            $venues = Venue::all();

            $beltGrades = BeltGrade::orderBy('type')->orderBy('level')->get();
            $isMinor = $athlete->birth_date ? Carbon::parse($athlete->birth_date)->age < 18 : false;

            return view('athlete.show', compact('athlete', 'beltGrades', 'isMinor', 'venues'));
        } catch (\Exception $e) {
            Log::error('Error al mostrar atleta: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar la información del atleta.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $athlete = Athlete::findOrFail($id);
        $minDateAthlete = Carbon::now()->subYears(3)->format('Y-m-d'); // Mínimo 3 años
        $maxDateAthlete = Carbon::now()->subYears(100)->format('Y-m-d'); // Máximo 100 años
        $isMinor = $athlete->birth_date ? Carbon::parse($athlete->birth_date)->age < 18 : false;

        $rules = [
            'venue_id' => 'required|exists:venues,id',
            'full_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\s]+$/u',
            ],
            'identity_document' => [
                'nullable',
                'string',
                'regex:/^[VEJ]-?\d{6,8}$/i',
                Rule::unique('athletes')->ignore($athlete->id),
            ],
            'nationality' => 'nullable|string|in:Venezolano,Extranjero',
            'birth_date' => [
                'required',
                'date',
                'before_or_equal:' . $minDateAthlete, // Máximo 3 años atrás
                'after_or_equal:' . $maxDateAthlete, // Mínimo 100 años atrás
            ],
            'gender' => 'required|in:M,F',
            'email' => [
                'nullable',
                'email',
                'max:255',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:15',
                'regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/'
            ],
            'height' => 'nullable|numeric|between:50,300',
            'current_weight' => 'nullable|numeric|between:10,500',
            'shirt_size' => 'nullable|string|in:XS,S,M,L,XL,XXL',
            'pants_size' => 'nullable|string|max:10',
            'shoe_size' => 'nullable|string|max:10',
            'medical_conditions' => 'nullable|string|max:1000',
            'allergies' => 'nullable|string|max:1000',
            'emergency_contact_name' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[\pL\s]+$/u'
            ],
            'emergency_contact_phone' => [
                'nullable',
                'string',
                'max:15',
                'regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/'
            ],
            'emergency_contact_relation' => 'nullable|string|max:50',
        ];

        // Validación adicional para el representante si el atleta es menor de edad
        if ($isMinor) {
            $minDateRepresentative = Carbon::now()->subYears(200)->format('Y-m-d'); // Máximo 200 años
            $rules['representative_birth_date'] = [
                'required',
                'date',
                'after_or_equal:' . $minDateRepresentative, // Máximo 200 años
                'before_or_equal:' . $request->birth_date, // No puede ser menor que el atleta
            ];
        }


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Actualizar información básica del atleta
            $athlete->update($request->all());

            // Actualizar información del representante si es menor de edad
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
            }

            if ($request->filled('belt_grade_id')) {
                Log::info('Actualizando grado del atleta:', [
                    'athlete_id' => $athlete->id,
                    'belt_grade_id' => $request->belt_grade_id,
                    'date_achieved' => $request->grade_date_achieved,
                    'certificate_number' => $request->grade_certificate_number,
                ]);

                // Create a new grade record
                AthleteGrade::create([
                    'athlete_id' => $athlete->id,
                    'grade_id' => $request->belt_grade_id,
                    'date_achieved' => $request->grade_date_achieved,
                    'certificate_number' => $request->grade_certificate_number,
                ]);
            }

            if ($isMinor && isset($representative)) {
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

            SystemLogController::log(
                'actualizar',
                'Athlete',
                $athlete->id,
                'Actualizada información del atleta: ' . $athlete->full_name
            );

            DB::commit();

            return redirect()->route('athlete.show', $athlete->id)
                ->with('success', 'Información actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error actualizando atleta: ' . $e->getMessage());
            return back()
                ->with('error', 'Error al actualizar la información. Por favor, intente nuevamente.')
                ->withInput();
        }
    }

    /**
     * Toggle the status of the specified athlete.
     */
    public function toggleStatus(string $id)
    {
        try {
            DB::beginTransaction();

            $athlete = Athlete::findOrFail($id);

            // Lógica mejorada para rotar entre estados
            $newStatus = match ($athlete->status) {
                'Active' => 'Inactive',
                'Inactive' => 'Active',
                default => 'Active' // Para otros estados no contemplados
            };

            $athlete->update(['status' => $newStatus]);

            SystemLogController::log(
                'actualizar',
                'Athlete',
                $athlete->id,
                'Cambiado estado del atleta a: ' . $newStatus
            );

            DB::commit();

            return redirect()->back()
                ->with('success', "Estado actualizado a {$newStatus} correctamente");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error cambiando estado: ' . $e->getMessage());
            return back()->with('error', 'Error al actualizar el estado');
        }
    }

    /**
     * Generate and return a PDF constancy for the specified athlete.
     */
    public function printConstancy($id)
    {
        try {
            $athlete = Athlete::with(['currentGrade.grade'])->findOrFail($id);

            if (!$athlete->status) {
                return back()->with('error', 'No se puede generar constancia para un atleta inactivo.');
            }

            $age = $athlete->birth_date ? Carbon::parse($athlete->birth_date)->age : null;

            $pdf = PDF::loadView('athlete.constancy', [
                'athlete' => $athlete,
                'age' => $age,
                'printDate' => Carbon::now()->format('d/m/Y')
            ]);

            $pdf->setPaper('letter');
            $filename = 'constancia_' . Str::slug($athlete->full_name) . '_' . date('Y-m-d') . '.pdf';

            return $pdf->stream($filename);
        } catch (\Exception $e) {
            Log::error('Error generando constancia: ' . $e->getMessage());
            return back()->with('error', 'Error al generar la constancia. Por favor, intente nuevamente.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $athlete = Athlete::findOrFail($id);

            // Verificar si hay pagos pendientes
            if ($athlete->payments()->where('status', 'Pending')->exists()) {
                return back()->with('error', 'No se puede eliminar el atleta porque tiene pagos pendientes.');
            }

            // Eliminar registros relacionados
            $athlete->grades()->delete();
            $athlete->representatives()->delete();

            // Eliminar usuario asociado si existe
            if ($athlete->email) {
                User::where('email', $athlete->email)->delete();
            }

            // Eliminar el atleta
            $athlete->delete();

            SystemLogController::log(
                'eliminar',
                'Athlete',
                $id,
                'Eliminado atleta: ' . $athlete->full_name
            );

            DB::commit();

            return redirect()->route('athlete.index')
                ->with('success', 'Atleta eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar atleta: ' . $e->getMessage());
            return back()->with('error', 'Error al eliminar el atleta. Por favor, intente nuevamente.');
        }
    }
}