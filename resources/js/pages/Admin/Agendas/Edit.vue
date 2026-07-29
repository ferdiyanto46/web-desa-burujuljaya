<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ChevronRightIcon } from 'lucide-vue-next';
import AgendaForm from '@/components/agendas/AgendaForm.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import type { Agenda } from '@/types';

defineOptions({ layout: AdminLayout });

defineProps<{
    agenda: { data: Agenda };
}>();
</script>

<template>
    <Head title="Edit Agenda" />
    
    <div class="mb-6 flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Agenda</h1>
            <nav class="mt-1 flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <li>
                        <a :href="route('admin.dashboard')" class="hover:text-gray-900 dark:hover:text-white">Dashboard</a>
                    </li>
                    <li>
                        <ChevronRightIcon class="h-4 w-4" />
                    </li>
                    <li>
                        <a :href="route('admin.agendas.index')" class="hover:text-gray-900 dark:hover:text-white">Agenda</a>
                    </li>
                    <li>
                        <ChevronRightIcon class="h-4 w-4" />
                    </li>
                    <li class="font-medium text-gray-900 dark:text-gray-200">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="mx-auto max-w-7xl">
        <AgendaForm
            :agenda="agenda.data"
            :submit-route="route('admin.agendas.update', agenda.data.ulid)"
            :cancel-route="route('admin.agendas.index')"
            is-edit
        />
    </div>
</template>
