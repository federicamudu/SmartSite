<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentRevisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $documentId)
    {
        // 1. Validazione (tolgo version_number perché lo calcolo in automatico)
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240', // Max 10MB
            'comment' => 'nullable|string'
        ]);

        // 2. Trovo il documento
        $document = Document::findOrFail($documentId);

        // Conto quante revisioni esistono già per questo documento
        $count = $document->revisions()->count();
        
        // Genero la stringa: R00, R01, R02... 
        // str_pad serve a mettere lo zero davanti se il numero è < 10
        $nextVersion = 'R' . str_pad($count, 2, '0', STR_PAD_LEFT);

        // 3. Gestione File
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documents', $fileName, 'local');

        // 4. Salvataggio
        $revision = DocumentRevision::create([
            'document_id' => $document->id,
            'version_number' => $nextVersion, // Usiamo la versione calcolata!
            'file_path' => $path,
            'status' => 'pending',
            'comment' => $request->comment,
            'uploaded_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => "Revisione {$nextVersion} caricata con successo!",
            'revision' => $revision
        ], 201);
    }

    public function updateStatus(Request $request, string $revisionId)
    {
        // 1. Validazione: accetto solo "approved" o "rejected"
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        // 2. Trovo la revisione
        $revision = DocumentRevision::findOrFail($revisionId);

        // 3. Aggiorno lo stato
        $revision->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Stato della revisione aggiornato con successo!',
            'revision' => $revision
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
