<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Controllo che l'utente abbia inviato email e password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Controllo se le credenziali sono nel database
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenziali non valide. Accesso negato.'
            ], 401); 
        }

        // 3. Se arriva qui, l'utente esiste e la password è giusta!
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 4. Creo il Token API per questo utente
        $token = $user->createToken('api-token')->plainTextToken;

        // 5. Consegno il token all'utente
        return response()->json([
            'message' => 'Login effettuato con successo!',
            'user' => $user->name,
            'token' => $token 
        ]);
    }
}