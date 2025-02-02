<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::all();
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $athletes = Athlete::all();
        $events = Event::where('status', 'Planned')->get();
        return view('payments.create', compact('athletes', 'events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'athlete_id' => 'required|exists:athletes,id',
            'payment_type' => 'required|string',
            'event_id' => 'required_if:payment_type,Event_Registration|exists:events,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Verificar si es un pago de evento
            if ($request->payment_type === 'Event_Registration') {
                // Verificar si el atleta está registrado en el evento
                $eventRegistration = EventRegistration::where([
                    'event_id' => $request->event_id,
                    'athlete_id' => $request->athlete_id,
                ])->first();

                if (!$eventRegistration) {
                    return redirect()->back()
                        ->with('error', 'El atleta no está registrado en este evento.')
                        ->withInput();
                }

                // Verificar si el atleta ya pagó este evento
                $existingPayment = Payment::where([
                    'athlete_id' => $request->athlete_id,
                    'event_id' => $request->event_id,
                    'status' => 'Completed'
                ])->exists();

                if ($existingPayment) {
                    return redirect()->back()
                        ->with('error', 'El atleta ya tiene un pago completado para este evento.')
                        ->withInput();
                }
            }

            // Crear el pago
            $payment = Payment::create([
                'athlete_id' => $request->athlete_id,
                'event_id' => $request->payment_type === 'Event_Registration' ? $request->event_id : null,
                'payment_type' => $request->payment_type,
                'amount' => $request->amount,
                'status' => 'Completed',
                'payment_date' => $request->payment_date,
            ]);

            // Solo actualizar el estado del registro de evento si el pago está completado
            if ($request->payment_type === 'Event_Registration') {
                $eventRegistration->update([
                    'payment_status' => 'Completed'
                ]);
            }

            DB::commit();

            return redirect()->route('payments.index')
                ->with('success', 'Pago registrado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al registrar el pago: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Ocurrió un error al registrar el pago.')
                ->withInput();
        }
    }



    // Agregar este nuevo método para obtener eventos disponibles
    public function getAvailableEvents($athleteId)
    {
        try {
            $athlete = Athlete::findOrFail($athleteId);

            // Obtener eventos con sus costos de registro
            $events = Event::select('events.*', 'event_categories.registration_fee')
                ->join('event_registrations', 'events.id', '=', 'event_registrations.event_id')
                ->join('event_categories', function ($join) {
                    $join->on('events.id', '=', 'event_categories.event_id')
                        ->on('event_registrations.category_id', '=', 'event_categories.category_id');
                })
                ->where('events.status', 'Planned')
                ->where('event_registrations.athlete_id', $athleteId)
                ->where('event_registrations.payment_status', '!=', 'Completed')
                ->whereDoesntHave('payments', function ($query) use ($athleteId) {
                    $query->where('athlete_id', $athleteId)
                        ->where('status', 'Completed');
                })
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $events
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al obtener eventos: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener eventos: ' . $e->getMessage()
            ], 500);
        }
    }





    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pago = Payment::findOrFail($id);
        return view('payments.show', compact('pago'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Mostrar pagos pendientes.
     */
    public function pending()
    {
        $pendingPayments = Payment::where('status', 'Pending')
            ->with('athlete')
            ->get();
        return view('payments.pending', compact('pendingPayments'));
    }

    /**
     * Aprobar un pago individual.
     */
    public function approveSingle(string $id)
    {
        try {
            DB::beginTransaction();

            $payment = Payment::findOrFail($id);

            // Verificar si es un pago de evento
            if ($payment->payment_type === 'Event_Registration' && $payment->event_id) {
                // Buscar el registro del evento
                $eventRegistration = EventRegistration::where([
                    'event_id' => $payment->event_id,
                    'athlete_id' => $payment->athlete_id
                ])->first();

                if (!$eventRegistration) {
                    throw new \Exception('El atleta no está registrado en este evento.');
                }

                // Actualizar el estado del registro del evento
                $eventRegistration->update([
                    'payment_status' => 'Completed'
                ]);
            }

            // Actualizar el estado del pago
            $payment->update([
                'status' => 'Completed',
                'updated_at' => now()
            ]);

            DB::commit();
            return redirect()->back()
                ->with('success', 'Pago aprobado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error al aprobar pago: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ocurrió un error al aprobar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Aprobar múltiples pagos.
     */
    public function approveBulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'exists:payments,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Por favor, seleccione al menos un pago para aprobar');
        }

        try {
            DB::beginTransaction();

            // Obtener todos los pagos seleccionados
            $payments = Payment::whereIn('id', $request->payment_ids)->get();

            foreach ($payments as $payment) {
                // Verificar si es un pago de evento
                if ($payment->payment_type === 'Event_Registration' && $payment->event_id) {
                    // Buscar el registro del evento
                    $eventRegistration = EventRegistration::where([
                        'event_id' => $payment->event_id,
                        'athlete_id' => $payment->athlete_id
                    ])->first();

                    if ($eventRegistration) {
                        // Actualizar el estado del registro del evento
                        $eventRegistration->update([
                            'payment_status' => 'Completed'
                        ]);
                    } else {
                        \Log::warning("Pago ID {$payment->id}: El atleta no está registrado en el evento ID {$payment->event_id}");
                    }
                }

                // Actualizar el estado del pago
                $payment->update([
                    'status' => 'Completed',
                    'updated_at' => now()
                ]);
            }

            DB::commit();
            return redirect()->back()
                ->with('success', 'Pagos aprobados exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error al aprobar pagos masivamente: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ocurrió un error al aprobar los pagos: ' . $e->getMessage());
        }
    }

    // ... actualizar el método update existente ...
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'payment_date' => 'required|date',
            'payment_type' => 'required',
            'payment_method' => 'required',
            'reference_number' => 'nullable',
            'receipt_url' => 'nullable|image|mimes:jpeg,png,jpg|max:20480',
            'notes' => 'nullable',
            'status' => 'required|in:Pending,Completed,Cancelled'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $payment = Payment::findOrFail($id);
            $oldStatus = $payment->status;
            $receipt_path = $payment->receipt_url;

            if ($request->hasFile('receipt_url')) {
                // Manejar la subida del archivo...
                // [Código existente para manejar el archivo...]
            }

            // Actualizar el pago
            $payment->update([
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'payment_type' => $request->payment_type,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'receipt_url' => $receipt_path,
                'notes' => $request->notes,
                'status' => $request->status
            ]);

            // Si el estado cambió a Completed y es un pago de evento
            if (
                $oldStatus !== 'Completed' && $request->status === 'Completed' &&
                $payment->payment_type === 'Event_Registration' && $payment->event_id
            ) {

                // Buscar y actualizar el registro del evento
                $eventRegistration = EventRegistration::where([
                    'event_id' => $payment->event_id,
                    'athlete_id' => $payment->athlete_id
                ])->first();

                if ($eventRegistration) {
                    $eventRegistration->update([
                        'payment_status' => 'Completed'
                    ]);
                } else {
                    \Log::warning("Pago ID {$payment->id}: El atleta no está registrado en el evento ID {$payment->event_id}");
                }
            }

            DB::commit();
            return redirect()->back()
                ->with('success', 'Pago actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error al actualizar pago: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ocurrió un error al actualizar el pago: ' . $e->getMessage());
        }
    }

    public function userPayment()
    {
        return view('payments.user-payment');
    }

    public function userStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'athlete_id' => 'required',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_type' => 'required',
            'payment_method' => 'required|in:Transfer,Card',
            'reference_number' => 'required',
            'receipt_url' => 'required|image|mimes:jpeg,png,jpg|max:20480',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $receipt_path = null;

            if ($request->hasFile('receipt_url')) {
                $file = $request->file('receipt_url');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());

                // Asegurarse de que el directorio existe
                $storage_path = storage_path('app/public/receipts');
                if (!file_exists($storage_path)) {
                    mkdir($storage_path, 0755, true);
                }

                // Mover el archivo directamente
                $file->move($storage_path, $filename);
                $receipt_path = 'receipts/' . $filename;

                // Verificar que el archivo se movió correctamente
                if (!file_exists($storage_path . '/' . $filename)) {
                    throw new \Exception('Error al guardar el archivo');
                }
            }

            Payment::create([
                'athlete_id' => $request->athlete_id,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'payment_type' => $request->payment_type,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'receipt_url' => $receipt_path,
                'notes' => $request->notes,
                'status' => 'Pending', // Los pagos de usuarios comienzan como pendientes
            ]);

            DB::commit();
            return redirect()->route('dashboard')
                ->with('success', 'Pago registrado exitosamente. Será revisado por un administrador.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error al registrar pago de usuario: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ocurrió un error al registrar el pago: ' . $e->getMessage());
        }
    }

    private function checkStorageStructure()
    {
        $paths = [
            storage_path('app/public'),
            storage_path('app/public/receipts'),
            public_path('storage'),
            public_path('storage/receipts')
        ];

        foreach ($paths as $path) {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
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
