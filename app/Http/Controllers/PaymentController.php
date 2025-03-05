<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['athlete', 'event'])->latest()->paginate(15);
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $athletes = Athlete::all();
        $events = Event::where('status', 'Planned')->get();
        return view('payments.create', compact('athletes', 'events'));
    }

    public function getAvailableEvents($athleteId)
    {
        $athlete = Athlete::findOrFail($athleteId);
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
    }

    public function store(Request $request)
    {
        $rules = [
            'athlete_id' => 'required|exists:athletes,id',
            'payment_type' => 'required|in:Monthly_Fee,Event_Registration,Equipment',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ];

        // Reglas condicionales basadas en el tipo de pago
        if ($request->payment_type === 'Monthly_Fee') {
            $rules['month'] = 'required|date_format:Y-m';
        } elseif ($request->payment_type === 'Event_Registration') {
            $rules['event_id'] = 'required|exists:events,id';
        }

        // Validar el request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $payment = Payment::create([
                'athlete_id' => $request->athlete_id,
                'event_id' => $request->payment_type === 'Event_Registration' ? $request->event_id : null,
                'payment_type' => $request->payment_type,
                'month' => $request->payment_type === 'Monthly' ? $request->month . '-01' : null,
                'amount' => $request->amount,
                'status' => 'Completed',
                'payment_date' => $request->payment_date,
            ]);


            if ($payment->payment_type === 'Event_Registration') {
                EventRegistration::updateOrCreate(
                    ['event_id' => $request->event_id, 'athlete_id' => $request->athlete_id],
                    ['payment_status' => 'Completed']
                );
            }



            DB::commit();

            return redirect()->back()
                ->with('success', 'Pago aprobado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Ocurrió un error al registrar el pago: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function pending()
    {
        $payments = Payment::where('status', 'Pending')
            ->with('athlete')
            ->get();
        return view('payments.pending', compact('payments'));
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


    public function show(string $id)
    {
        $pago = Payment::with(['athlete', 'event'])->findOrFail($id);
        return view('payments.show', compact('pago'));
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_type' => 'required|in:Monthly_Fee,Event_Registration,Equipment,Other',
            'payment_method' => 'required|in:Cash,Transfer,Card',
            'reference_number' => 'nullable|string',
            'status' => 'required|in:Pending,Completed,Cancelled',
            'notes' => 'nullable|string',
            'receipt_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

            $this->handleFileUpload($request, $payment);
            $this->updatePaymentDetails($request, $payment, $oldStatus);

            if ($payment->wasChanged(['amount', 'payment_date', 'payment_type', 'payment_method', 'reference_number'])) {
                $receiptPath = $this->generateAndStoreReceipt($payment);
                $payment->update(['receipt_pdf' => $receiptPath]);
            }

            DB::commit();
            return redirect()->route('payments.show', $payment->id)
                ->with('success', 'Pago actualizado exitosamente y recibo regenerado si fue necesario.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Ocurrió un error al actualizar el pago: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function handleFileUpload(Request $request, Payment $payment)
    {
        if ($request->hasFile('receipt_url')) {
            if ($payment->receipt_url) {
                Storage::delete($payment->receipt_url);
            }
            $path = $request->file('receipt_url')->store('receipts', 'public');
            $payment->receipt_url = $path;
        }
    }

    private function updatePaymentDetails(Request $request, Payment $payment, $oldStatus)
    {
        $payment->fill($request->only([
            'amount',
            'payment_date',
            'payment_type',
            'payment_method',
            'reference_number',
            'status',
            'notes'
        ]));

        if ($oldStatus !== 'Completed' && $request->status === 'Completed') {
            $payment->completed_at = now();
            $this->updateEventRegistration($payment);
        }

        $payment->save();
    }

    private function updateEventRegistration(Payment $payment)
    {
        if ($payment->payment_type === 'Event_Registration') {
            $eventRegistration = EventRegistration::where([
                'event_id' => $payment->event_id,
                'athlete_id' => $payment->athlete_id
            ])->first();

            if ($eventRegistration) {
                $eventRegistration->update(['payment_status' => 'Completed']);
            }
        }
    }

    public function generateReceipt($id)
    {
        $payment = Payment::with(['athlete', 'event'])->findOrFail($id);

        if (!in_array($payment->payment_type, ['Monthly_Fee', 'Event_Registration'])) {
            return redirect()->back()->with('error', 'Solo se pueden generar recibos para pagos de mensualidades y eventos.');
        }

        if ($payment->receipt_pdf) {
            return response()->file(storage_path('app/public/' . $payment->receipt_pdf));
        }

        $pdf = Pdf::loadView('payments.receipt', compact('payment'));
        $receiptPath = $this->generateAndStoreReceipt($payment);

        return response()->file(storage_path('app/public/' . $receiptPath));
    }

    private function generateReceiptFileName(Payment $payment)
    {
        $prefix = $payment->payment_type === 'Monthly_Fee' ? 'mensualidad' : 'evento';
        $date = $payment->payment_date->format('Y-m-d');
        return "recibo-{$prefix}-{$payment->id}-{$date}.pdf";
    }

    private function generateAndStoreReceipt(Payment $payment)
    {
        $pdf = Pdf::loadView('payments.receipt', compact('payment'));
        $fileName = $this->generateReceiptFileName($payment);
        $path = 'receipts/' . $fileName;

        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

    private function generateAndDownloadReceipt(Payment $payment)
    {
        $payment->load(['athlete', 'event']); // Asegurarse de que las relaciones estén cargadas

        $pdf = Pdf::loadView('payments.receipt', compact('payment'));

        $fileName = "recibo-" . ($payment->payment_type === 'Monthly_Fee' ? 'mensualidad' : 'evento') . "-{$payment->id}.pdf";

        return $pdf->download($fileName);
    }

    public function userPayment()
    {
        $events = Event::where('status', 'Planned')->get();
        return view('payments.user-payment', compact('events'));
    }

    public function userStore(Request $request)
    {
        // Validación de los datos del formulario
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_type' => 'required|in:Monthly_Fee,Event_Registration,Equipment,Other',
            'payment_method' => 'required|in:Transfer,Card',
            'reference_number' => 'required|string|max:255',
            'receipt_url' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Tamaño máximo de 2MB
            'notes' => 'nullable|string',
        ]);

        // Si la validación falla, redirigir con errores
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Obtener el athlete_id del usuario autenticado
            $athlete_id = Auth::user()->id;

            // Procesar la subida del archivo (comprobante de pago)
            $receipt_path = null;
            if ($request->hasFile('receipt_url')) {
                $receipt_path = $request->file('receipt_url')->store('receipts', 'public');
            }

            /* dd(
              [ 'athlete_id' => $athlete_id, 
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'payment_type' => $request->payment_type,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'receipt_url' => $receipt_path,
                'notes' => $request->notes,
                'status' => 'Pending' ]
            ); */

            // Crear el pago en la base de datos
            Payment::create([
                'athlete_id' => $athlete_id, 
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

            // Redirigir con mensaje de éxito
            return redirect()->route('home')
                ->with('success', 'Pago registrado exitosamente. Será revisado por un administrador.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error al registrar pago de usuario: " . $e->getMessage());

            // Redirigir con mensaje de error
            return redirect()->back()
                ->with('error', 'Ocurrió un error al registrar el pago: ' . $e->getMessage())
                ->withInput();
        }
    }
}
