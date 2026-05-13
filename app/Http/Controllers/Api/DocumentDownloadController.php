<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActionLog;
use App\Models\DocumentRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentDownloadController extends Controller
{
    public function download(Request $request, string $revisionId)
    {
        $revision = DocumentRevision::findOrFail($revisionId);
        
        $fullPath = Storage::disk('local')->path($revision->file_path);

        // Controllo se esiste fisicamente
        if (!file_exists($fullPath)) {
            abort(404, 'File non trovato fisicamente nel server. Percorso cercato: ' . $fullPath);
        }

        ActionLog::create([
            'user_id' => $request->user()->id,
            'action' => 'file_downloaded',
            'description' => "{$request->user()->name} ha scaricato il file della revisione {$revision->version_number}."
        ]);

        // Scarichiamo il file, forzando un nome pulito per l'utente (es. R03.pdf)
        return response()->download($fullPath, $revision->version_number . '.pdf');
    }

    public function preview(Request $request, string $revisionId)
    {
        $revision = DocumentRevision::findOrFail($revisionId);
        
        $fullPath = Storage::disk('local')->path($revision->file_path);

        if (!file_exists($fullPath)) {
            abort(404, 'File non trovato fisicamente nel server.');
        }

        // Registriamo una NUOVA azione: file_viewed (Visualizzato)
        ActionLog::create([
            'user_id' => $request->user()->id,
            'action' => 'file_viewed',
            'description' => "{$request->user()->name} ha visualizzato il file della revisione {$revision->version_number} a schermo."
        ]);

        return response()->file($fullPath);
    }
}