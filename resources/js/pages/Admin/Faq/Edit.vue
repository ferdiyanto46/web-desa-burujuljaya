<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { Button } from '@/components/ui/button';
import AdminLayout from '@/layouts/AdminLayout.vue';
import type { Faq } from '@/types/faq';
import FaqForm from './Components/FaqForm.vue';

defineProps<{
    faq: { data: Faq };
}>();
</script>

<template>
    <AdminLayout>
        <Head title="Edit FAQ" />

        <div class="space-y-6 max-w-4xl mx-auto pb-12">
            <div class="flex items-center gap-3">
                <Link :href="route('admin.faqs.index')">
                    <Button variant="ghost" size="sm" class="h-9 w-9 p-0 text-gray-500 hover:text-gray-900 dark:hover:text-white">
                        <ArrowLeft class="w-5 h-5" />
                    </Button>
                </Link>
                <PageTitle title="Edit FAQ" description="Ubah data dan jawaban FAQ." />
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                <FaqForm
                    :faq="faq.data"
                    is-edit
                />
            </div>
        </div>
    </AdminLayout>
</template>
