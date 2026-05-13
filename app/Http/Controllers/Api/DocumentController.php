<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Http\Resources\DocumentResource;
use App\Http\Requests\StoreDocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActionLog;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Inizio a preparare la query (il Trait aggiunge già il filtro tenant_id in automatico)
        $query = Document::query();

        // 2. Se l'utente ha cercato una parola chiave...
        if ($request->has('search')) {
            $searchTerm = $request->search;
            
            // Cercho sia nel codice che nel titolo
            $query->where(function($q) use ($searchTerm) {
                $q->where('code', 'like', '%' . $searchTerm . '%')
                ->orWhere('title', 'like', '%' . $searchTerm . '%');
            });
        }

        // 3. Paginazione: invece di ->get() (che prende TUTTO), uso ->paginate()
        // Aggiungo anche le revisioni e ordino dai più recenti
        $documents = $query->with('revisions')
                        ->latest()
                        ->paginate(10); // 10 risultati per pagina

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
    public function show(string $id)
    {
        // Cerco il documento e "tiro su" anche le sue revisioni
        $document = Document::with('revisions')->findOrFail($id);

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
