<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    logs: {
        type: Array,
        default: () => [],
    },
    filters: Object, 
});

// Variabili per i calendari
const startDate = ref(props.filters?.start_date || '');
const endDate = ref(props.filters?.end_date || '');

// Filtra automaticamente quando cambi una data
const filterLogs = () => {
    router.get(route('audit.index'), { 
        start_date: startDate.value, 
        end_date: endDate.value 
    }, { preserveState: true, replace: true });
};

watch([startDate, endDate], filterLogs);

// Funzione per rendere la data leggibile
const formattaData = (data) => {
    return new Date(data).toLocaleString('it-IT', {
        day: '2-digit', month: '2-digit', year: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    });
};
</script>

<template>
    <Head title="Audit Log" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registro di Sistema (Audit Log)</h2>
                <Link :href="route('dashboard')" class="text-sm text-gray-500 hover:text-gray-700">
                    &larr; Torna alla Dashboard
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 uppercase font-bold mb-1">Da data</label>
                                <input type="date" v-model="startDate" class="rounded border-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 uppercase font-bold mb-1">A data</label>
                                <input type="date" v-model="endDate" class="rounded border-gray-300 shadow-sm text-sm">
                            </div>
                            <button @click="startDate = ''; endDate = ''" class="mt-5 text-sm text-gray-500 hover:text-gray-800 underline">
                                Resetta
                            </button>
                        </div>

                        <!-- Il Bottone d'Esportazione Magico (passa le date all'URL!) -->
                        <a :href="`/audit/export-pdf?start_date=${startDate}&end_date=${endDate}`" 
                        target="_blank"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow inline-flex items-center gap-2">
                            📄 Esporta in PDF
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data e Ora</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo Azione</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descrizione</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="log in logs" :key="log.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ formattaData(log.created_at) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ log.user ? log.user.name : 'Sistema' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-indigo-100 text-indigo-800">
                                            {{ log.action }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ log.description }}
                                    </td>
                                </tr>
                                
                                <tr v-if="logs.length === 0">
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Nessun log registrato.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>