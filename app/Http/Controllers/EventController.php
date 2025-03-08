<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::all();
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $venues = Venue::all();
        $categories = Category::all();
        return view('events.create', compact('venues', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'type' => 'required|string|in:Competition,Promotion_Test,Training,Other',
            'venue_id' => 'nullable',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'registration_deadline' => 'nullable|date',
            'description' => 'nullable|string',
            'categories' => 'nullable|array', // Nuevo campo para las categorías
            'categories.*.category_id' => 'required|exists:categories,id', // Validar cada categoría
            'categories.*.registration_fee' => 'nullable|numeric', // Validar la tarifa de registro
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        try {
            DB::beginTransaction();

            // Crear el evento
            $event = Event::create([
                'name' => $request->name,
                'type' => $request->type,
                'venue_id' => $request->venue_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'registration_deadline' => $request->registration_deadline,
                'description' => $request->description,
                'status' => 'Planned',
            ]);

            // Crear las categorías asociadas al evento
            if ($request->has('categories')) {
                foreach ($request->categories as $categoryData) {
                    $event->eventCategories()->create([
                        'category_id' => $categoryData['category_id'],
                        'registration_fee' => $categoryData['registration_fee'],
                    ]);
                }
            }

            SystemLogController::log(
                'crear',
                'Event',
                $event->id,
                'Creado nuevo evento: ' . $event->name
            );

            DB::commit();

            return redirect()->route('events.index')
                ->with('success', 'Evento creado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear el evento: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Ocurrió un error al crear el evento');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::findOrFail($id);
        $venues = Venue::all();
        $categories = Category::all();
        return view('events.show', compact(['event', 'venues', 'categories']));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'type' => 'required|string|in:Competition,Promotion_Test,Training,Other',
            'venue_id' => 'nullable',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'registration_deadline' => 'nullable|date',
            'description' => 'nullable|string',
            'status' => 'required|string|in:Planned,Active,Completed,Cancelled',
            'categories' => 'nullable|array', // Nuevo campo para las categorías
            'categories.*.category_id' => 'required|exists:categories,id', // Validar cada categoría
            'categories.*.registration_fee' => 'nullable|numeric', // Validar la tarifa de registro
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Actualizar el evento
            $event = Event::findOrFail($id);
            $event->update([
                'name' => $request->name,
                'type' => $request->type,
                'venue_id' => $request->venue_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'registration_deadline' => $request->registration_deadline,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            // Eliminar las categorías antiguas
            $event->eventCategories()->delete();

            // Crear las nuevas categorías asociadas al evento
            if ($request->has('categories')) {
                foreach ($request->categories as $categoryData) {
                    $event->eventCategories()->create([
                        'category_id' => $categoryData['category_id'],
                        'registration_fee' => $categoryData['registration_fee'],
                    ]);
                }
            }

            SystemLogController::log(
                'actualizar',
                'Event',
                $event->id,
                'Actualizado evento: ' . $event->name
            );

            DB::commit();

            return redirect()->route('events.index')
                ->with('success', 'Evento actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al actualizar el evento: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Ocurrió un error al actualizar el evento');
        }
    }

    public function updateCategories(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'categories' => 'nullable|array',
            'categories.*.category_id' => 'required|exists:categories,id',
            'categories.*.registration_fee' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Eliminar las categorías antiguas
            $event->eventCategories()->delete();

            // Crear las nuevas categorías asociadas al evento
            if ($request->has('categories')) {
                foreach ($request->categories as $categoryData) {
                    $event->eventCategories()->create([
                        'category_id' => $categoryData['category_id'],
                        'registration_fee' => $categoryData['registration_fee'],
                    ]);
                }
            }

            SystemLogController::log(
                'actualizar',
                'Event',
                $event->id,
                'Actualizadas categorías para evento: ' . $event->name
            );

            DB::commit();

            return redirect()->route('events.show', $event->id)
                ->with('success', 'Categorías actualizadas exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al actualizar las categorías: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Ocurrió un error al actualizar las categorías');
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
