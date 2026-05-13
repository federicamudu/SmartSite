<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    documents: Object, 
    filters: Object,
});

// Variabile collegata alla barra di ricerca
const search = ref(props.filters?.search || '');

// Appena l'utente scrive, facciamo la chiamata
watch(search, (value) => {
    router.get(route('dashboard'), { search: value }, {
        preserveState: true,
        replace: true
    });
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Documenti</h2>
                
                <!-- Nuovo bottone per l'Audit Log -->
                <Link :href="route('audit.index')" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded text-sm shadow">
                    Audit Log
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Barra di ricerca e Bottone Nuovo -->
                <div class="mb-4 flex justify-between items-center">
                    <input v-model="search" 
                        type="text" 
                        placeholder="Cerca per codice o titolo..." 
                        class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        
                    <Link :href="route('documents.create')" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                        + Nuovo Documento
                    </Link>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <h3 class="text-lg font-bold mb-4">I tuoi Documenti Aziendali</h3>

                    <!-- Tabella -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Codice</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titolo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revisioni</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Creato il</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Se ci sono documenti, facciamo un ciclo (v-for) -->
                                <tr v-for="doc in documents.data" :key="doc.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link :href="route('documents.show', { document: doc.id })" class="text-indigo-600 hover:text-indigo-900 font-bold">
                                            Vedi Dettaglio &rarr;
                                        </Link>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ doc.code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ doc.title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ doc.revisions.length }} 
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <!-- Formattiamo la data alla buona per ora -->
                                        {{ new Date(doc.created_at).toLocaleDateString() }}
                                    </td>
                                </tr>
                                
                                <!-- Messaggio se la tabella è vuota -->
                                <tr v-if="documents.length === 0">
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 text-sm">
                                        Nessun documento trovato.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 flex justify-center" v-if="documents.links && documents.links.length > 3">
                        <nav class="inline-flex rounded-md shadow-sm">
                            <template v-for="link in documents.links" :key="link.label">
                                <Link 
                                    :href="link.url || '#'" 
                                    v-html="link.label"
                                    class="px-4 py-2 border text-sm font-medium"
                                    :class="{
                                        'bg-white text-gray-500 border-gray-300 hover:bg-gray-50': !link.active,
                                        'bg-indigo-600 text-white border-indigo-600': link.active,
                                        'opacity-50 cursor-not-allowed': !link.url
                                    }"
                                />
                            </template>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>