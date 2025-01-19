<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
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
        return view('payments.create', compact('athletes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'athlete_id' => 'required',
            'amount' => 'required',
            'payment_date' => 'required|date',
            'payment_type' => 'required',
            'payment_method' => 'required',
            'reference_number' => 'nullable',
            'receipt_url' => 'nullable|image|mimes:jpeg,png,jpg|max:20480',
            'notes' => 'nullable',
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
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName()); // Reemplazar espacios con guiones bajos

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
            ]);

            DB::commit();
            return redirect()->route('payments.index')
                ->with('success', 'Pago registrado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error al registrar pago: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ocurrió un error al registrar el pago: ' . $e->getMessage());
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
                ->with('error', 'Ocurrió un error al aprobar el pago');
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

            Payment::whereIn('id', $request->payment_ids)
                ->update([
                    'status' => 'Completed',
                    'updated_at' => now()
                ]);

            DB::commit();
            return redirect()->back()
                ->with('success', 'Pagos aprobados exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error al aprobar pagos masivamente: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ocurrió un error al aprobar los pagos');
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
            $receipt_path = $payment->receipt_url; // Mantener la imagen actual por defecto

            if ($request->hasFile('receipt_url')) {
                // Eliminar la imagen anterior si existe
                if ($payment->receipt_url && file_exists(storage_path('app/public/' . $payment->receipt_url))) {
                    unlink(storage_path('app/public/' . $payment->receipt_url));
                }

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

            if ($request->status === 'Completed' && !$payment->completed_at) {
                $payment->update(['completed_at' => now()]);
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
