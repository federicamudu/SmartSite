<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Document;
use App\Models\DocumentRevision;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Creiamo l'azienda APLANT
        $demoCompany = Tenant::create([
            'name' => 'Acme Engineering Corp',
            'slug' => 'acme-engineering',
            'vat_number' => 'IT00000000000'
        ]);

        // 2. Creiamo un utente 'Owner'
        $admin = User::create([
            'name' => 'Mario Rossi', 
            'email' => 'admin@acme.com',
            'password' => Hash::make('password123'),
            'tenant_id' => $demoCompany->id,
            'role' => 'owner'
        ]);

        User::create([
            'name' => 'Luigi Verdi',
            'email' => 'luigi@acme.com',
            'password' => bcrypt('password123'),
            'tenant_id' => $demoCompany->id,
            'role' => 'user'
        ]);

        // 3. Creiamo un documento "Finto" per testare
        // (Nota: non metto il tenant_id qui, vedo se il Trait funziona!)
        
        // Logghiamo fittiziamente l'utente per far scattare la magia del Trait
        Auth::login($admin);

        $document = new Document([
            'code' => 'PID-2024-001',
            'title' => 'Schema P&ID Reattore Principale',
            'description' => 'Schema tubazioni e strumentazione per il progetto Alpha.',
            'created_by' => $admin->id
        ]);

        $document->tenant_id = $demoCompany->id; 
        $document->save();

        // 4. Creiamo la prima revisione del documento
        DocumentRevision::create([
            'document_id' => $document->id,
            'version_number' => 'R00',
            'file_path' => 'documents/pid-2024-001-R00.pdf', // Finto path
            'status' => 'approved',
            'comment' => 'Prima emissione per costruzione.',
            'uploaded_by' => $admin->id,
            'approved_at' => now()
        ]);

        // Slogghiamo l'utente
        Auth::logout();
    }
}