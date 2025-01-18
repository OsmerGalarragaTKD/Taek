<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Document;
use App\Models\DocumentTemplate;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with(['athlete', 'template', 'event'])->get();
        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        $templates = DocumentTemplate::all();
        $athletes = Athlete::all();
        $events = Event::all();
        return view('documents.create', compact('templates', 'athletes', 'events'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'template_id' => 'required|exists:document_templates,id',
            'athlete_id' => 'required|exists:athletes,id',
            'event_id' => 'nullable|exists:events,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $document = Document::create([
                'template_id' => $request->template_id,
                'athlete_id' => $request->athlete_id,
                'event_id' => $request->event_id,
                'status' => 'Pending',
                'generated_date' => now(),
            ]);

            DB::commit();
            return redirect()->route('documents.index')
                ->with('success', 'Solicitud de documento creada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error al crear documento: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ocurrió un error al crear la solicitud');
        }
    }

    public function show(string $id)
    {
        $document = Document::with(['athlete', 'template', 'event'])->findOrFail($id);
        return view('documents.show', compact('document'));
    }

    public function pending()
    {
        $pendingDocuments = Document::where('status', 'Pending')
            ->with(['athlete', 'template', 'event'])
            ->get();
        return view('documents.pending', compact('pendingDocuments'));
    }

    public function approveSingle(string $id)
    {
        try {
            DB::beginTransaction();

            $document = Document::findOrFail($id);
            $document->update([
                'status' => 'Approved',
                'approved_at' => now()
            ]);

            DB::commit();
            return redirect()->back()
                ->with('success', 'Documento aprobado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error al aprobar documento: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ocurrió un error al aprobar el documento');
        }
    }

    public function approveBulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:documents,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Por favor, seleccione al menos un documento para aprobar');
        }

        try {
            DB::beginTransaction();

            Document::whereIn('id', $request->document_ids)
                ->update([
                    'status' => 'Approved',
                    'approved_at' => now()
                ]);

            DB::commit();
            return redirect()->back()
                ->with('success', 'Documentos aprobados exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error al aprobar documentos masivamente: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ocurrió un error al aprobar los documentos');
        }
    }

    public function reject(string $id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $document = Document::findOrFail($id);
            $document->update([
                'status' => 'Rejected',
                'rejection_reason' => $request->rejection_reason,
                'rejected_at' => now()
            ]);

            DB::commit();
            return redirect()->back()
                ->with('success', 'Documento rechazado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error al rechazar documento: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ocurrió un error al rechazar el documento');
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
