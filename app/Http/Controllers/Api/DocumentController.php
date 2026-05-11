<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Http\Resources\DocumentResource;
use App\Http\Requests\StoreDocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Chiedo tutti i documenti... 
        // ma il Trait dovrà filtrare automaticamente solo quelli della "Acme Engineering"!
        $documents = Document::with('revisions')->get();

        return DocumentResource::collection($documents);
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
