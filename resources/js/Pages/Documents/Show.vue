<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    document: Object,
});

// Creo il form vuoto
const form = useForm({
    file: null,
    comment: '',
});

// Funzione per inviare il file al backend
const submitRevision = () => {
    form.post(route('revisions.store', props.document.id), {
        onSuccess: () => {
            form.reset();
            alert('Nuova revisione caricata con successo!');
        },
    });
};

const rifiuta = (id) => {
    // Usiamo un semplice prompt nativo del browser per fare in fretta e pulito
    const reason = window.prompt("Inserisci il motivo del rifiuto. Questo campo è obbligatorio:");
    
    if (reason === null) {
        return; // L'utente ha cliccato "Annulla"
    }
    
    if (reason.trim() === '') {
        alert("Devi inserire una motivazione per poter rifiutare il documento!");
        return;
    }

    // Se ha scritto qualcosa, mandiamo al server!
    router.patch(route('revisions.reject', id), { reason: reason });
};

// Funzione carina per colorare lo status
const getStatusColor = (status) => {
    if (status === 'approved') return 'bg-green-100 text-green-800';
    if (status === 'rejected') return 'bg-red-100 text-red-800';
    return 'bg-yellow-100 text-yellow-800';
};
</script>

<template>
    <Head :title="document.code" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ document.code }} - {{ document.title }}
                </h2>
                <Link :href="route('dashboard')" class="text-sm text-gray-500 hover:text-gray-700">
                    &larr; Torna alla Dashboard
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <!-- Card Info Anagrafica -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-2">Descrizione</h3>
                    <p class="text-gray-600">{{ document.description || 'Nessuna descrizione disponibile.' }}</p>
                </div>

                <!-- Card Revisioni -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Storico Revisioni</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Versione</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stato</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Commento</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">File</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="rev in document.revisions" :key="rev.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ rev.version_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="{
                                                'bg-yellow-100 text-yellow-800': rev.status === 'pending',
                                                'bg-green-100 text-green-800': rev.status === 'approved',
                                                'bg-red-100 text-red-800': rev.status === 'rejected'
                                            }">
                                            {{ rev.status.toUpperCase() }}
                                        </span>
                                        
                                        <!-- Mostriamo il motivo in rosso corsivo sotto la pillola se è rifiutato! -->
                                        <div v-if="rev.status === 'rejected' && rev.rejection_reason" class="text-xs text-red-600 mt-2 font-medium italic whitespace-normal w-48">
                                            Motivo: {{ rev.rejection_reason }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ rev.comment || '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                        <!-- Bottoni visibili SOLO se lo status è pending -->
                                        <template v-if="rev.status === 'pending' && ($page.props.auth.user.role === 'doc_controller' || $page.props.auth.user.role === 'focal_point')">
                                            <Link :href="route('revisions.approve', rev.id)" 
                                                method="patch" 
                                                as="button"
                                                preserve-scroll
                                                class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-1 px-3 rounded shadow-md transition">
                                                APPROVA
                                            </Link>

                                            <button @click="rifiuta(rev.id)" 
                                                class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-1 px-3 rounded shadow-md transition ml-2">
                                                RIFIUTA
                                            </button>
                                        </template>

                                        <a :href="route('revisions.preview', rev.id)" 
                                        target="_blank" 
                                        class="text-indigo-600 hover:text-indigo-900 font-bold underline ml-4">
                                            Visualizza
                                        </a>

                                        <a :href="route('revisions.download', rev.id)" 
                                        target="_blank" 
                                        class="text-blue-600 hover:text-blue-900 font-bold underline ml-4">
                                            Scarica
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
                    <h3 class="text-lg font-bold mb-4">Carica Nuova Revisione</h3>
                    
                    <form @submit.prevent="submitRevision" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File PDF</label>
                            <input type="file" 
                                   @input="form.file = $event.target.files[0]" 
                                   accept=".pdf"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                            <div v-if="form.errors.file" class="text-red-500 text-sm mt-1">{{ form.errors.file }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Commento (Opzionale)</label>
                            <textarea v-model="form.comment" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>

                        <button type="submit" 
                                :disabled="form.processing"
                                class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 disabled:opacity-50">
                            {{ form.processing ? 'Caricamento in corso...' : 'CARICA NUOVA REVISIONE' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>