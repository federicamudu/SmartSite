<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // Solo il Document Controller può vedere questa pagina
        if ($user->role !== 'owner') {
            abort(403, 'Accesso negato. Solo l\'amministratore può gestire gli utenti.');
        }

        // Il TenantScope globale (se lo avevamo messo su User) filtrerà in automatico,
        // ma per sicurezza prendiamo esplicitamente solo quelli della sua azienda
        $users = User::where('tenant_id', $user->tenant_id)->latest()->get();

        return Inertia::render('Users/Index', [
            'users' => $users
        ]);
    }

    public function create(Request $request)
    {
        if ($request->user()->role !== 'owner') {
            abort(403, 'Accesso negato.');
        }

        return Inertia::render('Users/Create');
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $admin */
        $admin = $request->user();

        if ($admin->role !== 'owner') {
            abort(403, 'Accesso negato.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:doc_controller,pm,focal_point,drafter,guest',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'tenant_id' => $admin->tenant_id, 
        ]);

        return redirect()->route('users.index')->with('success', 'Utente creato con successo.');
    }

    public function edit(Request $request, string $id)
    {
        /** @var \App\Models\User $admin */
        $admin = $request->user();

        if (!in_array($admin->role, ['owner'])) {
            abort(403, 'Accesso negato.');
        }

        // Troviamo l'utente, assicurandoci che faccia parte dello stesso Tenant!
        $userToEdit = User::where('tenant_id', $admin->tenant_id)->findOrFail($id);

        return Inertia::render('Users/Edit', [
            'userToEdit' => $userToEdit
        ]);
    }

    public function update(Request $request, string $id)
    {
        /** @var \App\Models\User $admin */
        $admin = $request->user();

        if (!in_array($admin->role, ['owner'])) {
            abort(403, 'Accesso negato.');
        }

        $userToEdit = User::where('tenant_id', $admin->tenant_id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userToEdit->id,
            'password' => 'nullable|string|min:8', 
            'role' => 'required|in:doc_controller,pm,focal_point,drafter,guest',
        ]);

        $userToEdit->name = $validated['name'];
        $userToEdit->email = $validated['email'];
        $userToEdit->role = $validated['role'];
        
        if (!empty($validated['password'])) {
            $userToEdit->password = Hash::make($validated['password']);
        }

        $userToEdit->save();

        return redirect()->route('users.index')->with('success', 'Utente aggiornato con successo.');
    }

    public function destroy(Request $request, string $id)
    {
        /** @var \App\Models\User $admin */
        $admin = $request->user();

        if (!in_array($admin->role, ['owner'])) {
            abort(403, 'Accesso negato.');
        }

        // Troviamo l'utente (sempre nello stesso tenant!)
        $userToDelete = User::where('tenant_id', $admin->tenant_id)->findOrFail($id);

        // Preveniamo che l'owner si auto-elimini!
        if ($userToDelete->id === $admin->id) {
            return back()->withErrors(['error' => 'Non puoi eliminare il tuo stesso account!']);
        }

        $userToDelete->delete(); 

        return redirect()->route('users.index')->with('success', 'Utente eliminato.');
    }
}