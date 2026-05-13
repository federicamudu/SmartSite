<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Models\ActionLog;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Document::query();

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('code', 'like', '%' . $searchTerm . '%')
                  ->orWhere('title', 'like', '%' . $searchTerm . '%');
            });
        }

        $documents = $query->with('revisions')
                        ->latest()
                        ->paginate(10)
                        ->withQueryString(); 

        // Se chiama Vue/Browser
        if (!$request->wantsJson()) {
            return Inertia::render('Dashboard', [
                'documents' => $documents,
                'filters' => $request->only(['search'])
            ]);
        }

        // Se chiama Postman
        return response()->json($documents);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request)
    {
        // Se il codice arriva qui, significa che i dati hanno superato la validazione!
        // E grazie al Trait, non devo specificare il tenant_id.
        $document = Document::create([
            'code' => $request->code,
            'title' => $request->title,
            'description' => $request->description,
            'created_by' => Auth::id(), // Registro chi ha creato l'anagrafica
        ]);

        ActionLog::create([
            'user_id' => Auth::id(),
            'action' => 'document_created',
            'description' => Auth::user()->name . " ha creato un nuovo documento: {$document->title} ({$document->code})."
        ]);

        // Restituisco il documento appena creato formattato bene!
        // Uso "new" invece di "collection" perché sto restituendo UN SOLO documento.
        return new DocumentResource($document);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id) 
    {
        // Tiro su il documento e le revisioni ordinate dalla più recente
        $document = Document::with(['revisions' => function ($query) {
            $query->latest();
        }])->findOrFail($id);

        // Se chiama Vue/Browser
        if (!$request->wantsJson()) {
            return Inertia::render('Documents/Show', [
                'document' => $document
            ]);
        }

        // Se chiama Postman
        return response()->json([
            'data' => $document
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
