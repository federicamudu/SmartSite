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
        // 1. La "Dogana" per i file (Solo PDF, max 10MB)
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240', 
            'version_number' => 'required|string',
            'comment' => 'nullable|string',
        ]);

        // 2. Trovo il documento padre
        $document = Document::findOrFail($documentId);

        // 3. Salvo fisicamente il file nel filesystem (storage/app/public/documents)
        $path = $request->file('file')->store('documents', 'public');

        // 4. Scrivo nel database la traccia della nuova revisione
        $revision = DocumentRevision::create([
            'document_id' => $document->id,
            'version_number' => $request->version_number,
            'file_path' => $path,
            'status' => 'pending', // Lo status iniziale è "pending" finché un admin non lo approva
            'comment' => $request->comment,
            'uploaded_by' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'File caricato con successo!',
            'revision' => $revision
        ], 201);
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
