<?php

namespace App\Http\Controllers;

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
        return view('events.create', compact('venues'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'type' => 'required|string|in:Competition,Promotion_Test,Training,Other',
            'venue_id' => 'nullable',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'registration_deadline' => 'nullable|date',
            'description' => 'nullable|string',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }



        try {
            DB::beginTransaction();

            Event::create([
                'name' => $request->name,
                'type' => $request->type,
                'venue_id' => $request->venue_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'registration_deadline' => $request->registration_deadline,
                'description' => $request->description,
                'status' => 'Planned',
            ]);

            DB::commit();

            return redirect()->route('events.index')
                ->with('success', 'Evento creado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al ingresar Evento: ' .$e->getMessage());

            return redirect()->back()
                ->with('error', 'Ocurrió un error al ingresar el evento');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::findOrFail($id);
        $venues = Venue::all();
        return view('events.show', compact(['event', 'venues']));
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
            'type' => 'required|string|in:Competition,Promotion_Test,Training,Other',
            'venue_id' => 'nullable',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'registration_deadline' => 'nullable|date',
            'description' => 'nullable|string',
            'status' => 'required|string|in:Planned,Active,Completed,Cancelled',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

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

            DB::commit();

            return redirect()->route('events.index')
                ->with('success', 'Evento actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al actualizar Evento: ' .$e->getMessage());

            return redirect()->back()
                ->with('error', 'Ocurrió un error al actualizar el evento');
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
