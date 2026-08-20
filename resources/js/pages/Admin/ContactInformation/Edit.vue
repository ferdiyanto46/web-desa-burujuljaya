<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import AdminLayout from '@/layouts/AdminLayout.vue';
import type { ContactInformation } from '@/types/contact-information';
import ContactInformationForm from './Components/ContactInformationForm.vue';

const props = defineProps<{
    contact: ContactInformation;
}>();
</script>

<template>
    <Head title="Edit Informasi Kontak" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link
                    :href="route('admin.contact-information.index')"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition-colors"
                >
                    <ArrowLeft class="w-5 h-5 text-gray-500" />
                </Link>
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight">Edit Informasi Kontak</h2>
                    <p class="text-sm text-muted-foreground">
                        Ubah data informasi kontak dan publik desa.
                    </p>
                </div>
            </div>
        </template>

        <div class="max-w-5xl mx-auto py-6">
            <ContactInformationForm :contact="contact" is-editing />
        </div>
    </AdminLayout>
</template>
