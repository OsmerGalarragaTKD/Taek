<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Athlete;
use App\Models\EventRegistration;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class EventRegistrationController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('crear_eventos')) {
            return redirect()->back()->with('error', 'No tienes permiso para crear eventos.');
        }
        // Obtener eventos activos (aquellos que no han pasado su fecha límite de registro)
        $activeEvents = Event::where('registration_deadline', '>=', now())->get();

        // Obtener todos los registros de eventos (opcional, si quieres mostrar los registros existentes)
        $registrations = EventRegistration::with(['event', 'athlete', 'category'])->get();

        return view('event-registrations.index', compact('activeEvents', 'registrations'));
    }

    public function create() {}

    public function createEvent(Event $event)
    {
        if (!Auth::user()->can('crear_eventos')) {
            return redirect()->back()->with('error', 'No tienes permiso para crear eventos.');
        }


        // Verificar si el evento está activo
        if ($event->registration_deadline && $event->registration_deadline->isPast()) {
            return redirect()->route('event-registrations.index')
                ->with('error', 'El período de registro para este evento ha finalizado.');
        }

        // Obtener atletas con pagos aprobados
        $athletes = Athlete::select('id', 'full_name', 'identity_document')->get();

        // Obtener las categorías asociadas al evento
        $categories = $event->categories;

        // Depuración (opcional)
        // dd($categories); // Verifica el contenido de $categories

        return view('event-registrations.create', compact('event', 'athletes', 'categories'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('crear_eventos')) {
            return redirect()->back()->with('error', 'No tienes permiso para crear eventos.');
        }


        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'athlete_id' => 'required|exists:athletes,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            

            // Verificar si el atleta ya está registrado en el evento
            $existingRegistration = EventRegistration::where('event_id', $request->event_id)
                ->where('athlete_id', $request->athlete_id)
                ->exists();

            if ($existingRegistration) {
                return redirect()->back()
                    ->with('error', 'El atleta ya está registrado en este evento.');
            }
            

            // Verificar si el atleta tiene el pago aprobado
            /* $hasPaidEvent = Payment::where('athlete_id', $request->athlete_id)
                ->where('payment_type', 'Event_Registration')
                ->where('status', 'Completed')
                ->exists();

            if (!$hasPaidEvent) {
                return redirect()->back()
                    ->with('error', 'El atleta debe tener un pago aprobado para registrarse en el evento.');
            } */

           

            $registration = EventRegistration::create([
                'event_id' => $request->event_id,
                'athlete_id' => $request->athlete_id,
                'category_id' => $request->category_id,
                'payment_status' => 'Pending',
                'status' => 'Registered',
                'notes' => $request->notes,
            ]);


            SystemLogController::log(
                'crear',
                'EventRegistration',
                $registration->id,
                'Registrado atleta ID: ' . $request->athlete_id . ' para evento ID: ' . $request->event_id
            );

            DB::commit();

            return redirect()->route('events.show', $request->event_id)
                ->with('success', 'Atleta registrado exitosamente en el evento.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("No se pudo proceder error: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al registrar al atleta en el evento: ' . $e->getMessage());
        }
    }

    public function show(EventRegistration $registration)
    {
        if (!Auth::user()->can('crear_eventos')) {
            return redirect()->back()->with('error', 'No tienes permiso para crear eventos.');
        }

        // Cargar las relaciones necesarias
        $registration->load(['event', 'athlete', 'category']);

        // Pasar el registro y la categoría a la vista
        return view('event-registrations.show', compact('registration'));
    }

    public function destroy(EventRegistration $registration)
    {
        if (!Auth::user()->can('eliminar_eventos')) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar eventos.');
        }

        try {
            $registration->delete();
            SystemLogController::log(
                'eliminar',
                'EventRegistration',
                $registration->id,
                'Eliminado registro para atleta ID: ' . $registration->athlete_id . ' del evento ID: ' . $registration->event_id
            );
            return redirect()->back()->with('success', 'Registro eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el registro.');
        }
    }
}
