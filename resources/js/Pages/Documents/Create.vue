<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    code: '',
    title: '',
    description: '',
});

// Funzione scatenata al submit
const submit = () => {
    form.post(route('documents.store'));
};
</script>

<template>
    <Head title="Nuovo Documento" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crea Nuovo Documento</h2>
                <Link :href="route('dashboard')" class="text-sm text-gray-500 hover:text-gray-700">
                    &larr; Annulla e Torna indietro
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <form @submit.prevent="submit" class="space-y-6">
                        
                        <!-- Codice -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Codice Documento (es. MAN-01)</label>
                            <input v-model="form.code" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <div v-if="form.errors.code" class="text-red-500 text-sm mt-1">{{ form.errors.code }}</div>
                        </div>

                        <!-- Titolo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Titolo</label>
                            <input v-model="form.title" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
                        </div>

                        <!-- Descrizione -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descrizione (Opzionale)</label>
                            <textarea v-model="form.description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</div>
                        </div>

                        <!-- Bottone Salva -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                    :disabled="form.processing"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow disabled:opacity-50">
                                <span v-if="form.processing">Salvataggio...</span>
                                <span v-else>Salva Documento</span>
                            </button>
                        </div>
                        
                    </form>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>