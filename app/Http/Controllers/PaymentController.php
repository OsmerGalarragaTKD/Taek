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
            'receipt_url' => 'nullable',
            'notes' => 'nullable',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }



       try {
        DB::beginTransaction();


        Payment::create([
            'athlete_id' => $request->athlete_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_type' => $request->payment_type,
            'payment_method' => $request->payment_method,
            'reference_number' => $request->reference_number,
            'receipt_url' => $request->receipt_url,
            'notes' => $request->notes,
        ]);

        DB::commit();
        return redirect()->route('payments.index')
            ->with('success', 'Pago registrado exitosamente');
       } catch (\Exception $e) {
        DB::rollBack();

        \Log::error("Error al registrar pago: " . $e->getMessage());

        return redirect()->back()
            ->with('error', 'Ocurrió un error al registrar el pago');

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
            'receipt_url' => 'nullable',
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
            $payment->update($request->all());

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
                ->with('error', 'Ocurrió un error al actualizar el pago');
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
