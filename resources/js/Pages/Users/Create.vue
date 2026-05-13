<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: 'drafter', // Ruolo di default per non sbagliare
});

const submit = () => {
    form.post(route('users.store'));
};
</script>

<template>
    <Head title="Nuovo Utente" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Aggiungi Nuovo Utente</h2>
        </template>

        <div class="py-12">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nome e Cognome</label>
                            <input v-model="form.name" type="text" class="mt-1 block w-full rounded border-gray-300 shadow-sm" required>
                            <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email Aziendale</label>
                            <input v-model="form.email" type="email" class="mt-1 block w-full rounded border-gray-300 shadow-sm" required>
                            <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password Provvisoria</label>
                            <input v-model="form.password" type="password" class="mt-1 block w-full rounded border-gray-300 shadow-sm" required minlength="8">
                            <div v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ruolo nel Sistema</label>
                            <select v-model="form.role" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                <option value="doc_controller">Document Controller </option>
                                <option value="pm">Project Manager </option>
                                <option value="focal_point">Focal Point </option>
                                <option value="drafter">Drafter / Engineer </option>
                                <option value="guest">Guest </option>
                            </select>
                            <div v-if="form.errors.role" class="text-red-500 text-xs mt-1">{{ form.errors.role }}</div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <Link :href="route('users.index')" class="mr-4 py-2 px-4 text-gray-600 hover:text-gray-900">Annulla</Link>
                            <button type="submit" :disabled="form.processing" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow">
                                Crea Utente
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>