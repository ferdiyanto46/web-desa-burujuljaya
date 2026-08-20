<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { CalendarIcon, UserIcon } from 'lucide-vue-next';
import PageBanner from '@/components/public/PageBanner.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { VillagePage } from '@/types/village-page';

const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
    }).format(new Date(dateString));
};

defineProps<{
    page: { data: VillagePage };
}>();
</script>

<template>
    <Head>
        <title>{{ page.data.meta_title || page.data.title }} - Profil Desa</title>
        <meta name="description" :content="page.data.meta_description || page.data.excerpt || ''" />
    </Head>

    <PublicLayout>
        <template #banner>
            <PageBanner 
                :title="page.data.title" 
                :subtitle="page.data.page_type_label"
            />
        </template>
        
        <div class="container mx-auto py-12">
            <div class="max-w-4xl mx-auto">
                <article class="bg-card rounded-2xl shadow-sm border overflow-hidden">
                    <!-- Featured Image -->
                    <div v-if="page.data.featured_image_url" class="aspect-[21/9] w-full relative bg-muted">
                        <img 
                            :src="page.data.featured_image_url" 
                            :alt="page.data.title"
                            class="object-cover w-full h-full"
                        />
                    </div>
                    
                    <div class="p-8 md:p-12">
                        <!-- Meta Info -->
                        <div class="flex flex-wrap items-center gap-4 text-sm text-muted-foreground mb-8 pb-8 border-b">
                            <div class="flex items-center gap-2">
                                <CalendarIcon class="w-4 h-4" />
                                <span>{{ page.data.published_at ? formatDate(page.data.published_at) : '' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <UserIcon class="w-4 h-4" />
                                <span>Oleh {{ page.data.author?.name || 'Admin Desa' }}</span>
                            </div>
                        </div>

                        <!-- Excerpt -->
                        <div v-if="page.data.excerpt" class="text-xl leading-relaxed text-muted-foreground italic mb-10 border-l-4 border-primary pl-6 py-2">
                            {{ page.data.excerpt }}
                        </div>
                        
                        <!-- Content -->
                        <div class="prose prose-lg dark:prose-invert max-w-none">
                            <div v-html="page.data.content"></div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </PublicLayout>
</template>
