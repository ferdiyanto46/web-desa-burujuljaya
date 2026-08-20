<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft as ArrowLeftIcon } from 'lucide-vue-next';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { Faq } from '@/types/faq';

defineProps<{
    faq: { data: Faq };
}>();
</script>

<template>
    <Head :title="faq.data.meta_title || faq.data.question">
        <meta v-if="faq.data.meta_description" name="description" :content="faq.data.meta_description">
        <link v-if="faq.data.canonical_url" rel="canonical" :href="faq.data.canonical_url">
    </Head>

    <PublicLayout>
        <!-- Header -->
        <section class="bg-primary-900 text-white pt-12 pb-20">
            <div class="container mx-auto px-4 max-w-4xl">
                <Link :href="route('faq.index')" class="inline-flex items-center text-primary-200 hover:text-white transition-colors mb-6 text-sm font-medium">
                    <ArrowLeftIcon class="w-4 h-4 mr-1" />
                    Kembali ke Pusat Bantuan
                </Link>
                
                <div class="flex items-center gap-3 mb-4">
                    <span v-if="faq.data.category" class="px-3 py-1 bg-primary-800 text-primary-100 rounded-full text-xs font-semibold uppercase tracking-wider">
                        {{ faq.data.category }}
                    </span>
                    <span class="text-primary-300 text-sm">
                        Diperbarui {{ new Date(faq.data.updated_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                    </span>
                </div>
                
                <h1 class="text-3xl md:text-4xl font-bold leading-tight">
                    {{ faq.data.question }}
                </h1>
            </div>
        </section>

        <!-- Content -->
        <section class="py-12 bg-gray-50 -mt-10 min-h-[50vh]">
            <div class="container mx-auto px-4 max-w-4xl">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-12 prose max-w-none prose-lg prose-p:leading-relaxed prose-a:text-primary-600 hover:prose-a:text-primary-700">
                    <div v-html="faq.data.answer"></div>
                </div>
                
                <div class="mt-8 text-center text-gray-500 text-sm flex justify-center items-center gap-4">
                    <span>Dilihat: {{ faq.data.view_count.toLocaleString('id-ID') }} kali</span>
                    <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                    <Link :href="route('contact-information.index')" class="text-primary-600 hover:underline">
                        Masih butuh bantuan? Hubungi Kami
                    </Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
