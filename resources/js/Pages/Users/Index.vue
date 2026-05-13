<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({ users: Array });
</script>

<template>
    <Head title="Gestione Utenti" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Utenti Aziendali</h2>
                <Link :href="route('users.create')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                    + Nuovo Utente
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ruolo</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="user in users" :key="user.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ user.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold uppercase">{{ user.role }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex flex-col items-end space-y-2">
                                        <Link :href="route('users.edit', user.id)" class="text-indigo-600 hover:text-indigo-900 font-bold">
                                            Modifica
                                        </Link>
                                        <!-- Nascondiamo il tasto elimina se l'utente della riga è lo stesso loggato -->
                                        <Link v-if="user.id !== $page.props.auth.user.id" 
                                            :href="route('users.destroy', user.id)" 
                                            method="delete" 
                                            as="button" 
                                            class="text-red-600 hover:text-red-900 font-bold text-right w-full"
                                            onclick="return confirm('Sei sicuro di voler eliminare questo utente?');">
                                            Elimina
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>