<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocumentRevision;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\ActionLog;

class DocumentDownloadController extends Controller
{
    public function download(Request $request, string $revisionId)
    {
        // 1. Cerco la revisione nel database (Laravel filtrerà in automatico per azienda!)
        $revision = DocumentRevision::findOrFail($revisionId);

        // 2. Controllo se il file esiste davvero nel disco "public"
        // (potrebbe essere stato cancellato per sbaglio dal server)
        if (!Storage::disk('public')->exists($revision->file_path)) {
            return response()->json(['message' => 'File non trovato sul server.'], 404);
        }

        // 3. Costruisco un nome "bello" per l'utente che scarica
        // Es: "PID-2026-003_R01.pdf" invece dell'hash del file salvato
        $document = $revision->document; // Recupero il documento padre
        $downloadName = $document->code . '_' . $revision->version_number . '.pdf';

        // 4. Servo il file all'utente (forza il download nel browser)
        $fullPath = storage_path('app/public/' . $revision->file_path);

        ActionLog::create([
            'user_id' => $request->user()->id,
            'action' => 'file_downloaded',
            'description' => "{$request->user()->name} ha scaricato il file della revisione {$revision->version_number}."
        ]);

        return response()->download($fullPath, $downloadName);
    }
}