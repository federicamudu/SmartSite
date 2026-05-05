<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check(); // Solo gli utenti loggati (col token) passano!
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            
            // Per ora tralascio il caricamento del PDF vero e proprio,
            // mi concentro sul salvare l'anagrafica del documento!
        ];
    }

    public function messages(): array
    {
        // messaggi di errore personalizzati in italiano!
        return [
            'code.required' => 'Il codice del documento è obbligatorio (es. PID-001).',
            'title.required' => 'Devi inserire un titolo per il documento.',
        ];
    }
}
