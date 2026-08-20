<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { SearchIcon, BuildingIcon, ClockIcon, FileTextIcon, InfoIcon } from 'lucide-vue-next';
import { ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { PaginatedData } from '@/types/pagination';
import type { VillageService } from '@/types/village-service';

const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
    }).format(new Date(dateString));
};

const props = defineProps<{
    villageServices: PaginatedData<VillageService>;
    filters: {
        search?: string;
    };
}>();

const searchQuery = ref(props.filters.search ?? '');

const handleSearch = () => {
    router.get(
        route('village-services.index'),
        { search: searchQuery.value },
        { preserveState: true, preserveScroll: true }
    );
};
</script>

<template>
    <Head title="Layanan Desa" />

    <PublicLayout>
        <!-- Hero Section -->
        <section class="relative py-16 md:py-24 overflow-hidden bg-gradient-to-b from-primary/10 to-background border-b border-border/40">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-3xl mx-auto text-center space-y-6">
                    <Badge variant="outline" class="px-4 py-1.5 rounded-full border-primary/30 text-primary bg-primary/5 uppercase tracking-wider text-xs font-semibold mb-4">
                        Pusat Informasi
                    </Badge>
                    <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-foreground">
                        Layanan Administrasi Desa
                    </h1>
                    <p class="text-lg md:text-xl text-muted-foreground leading-relaxed">
                        Temukan informasi lengkap terkait persyaratan, prosedur, dan biaya untuk berbagai layanan administrasi kependudukan di desa kami.
                    </p>
                    
                    <div class="relative max-w-xl mx-auto mt-8 group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-muted-foreground group-focus-within:text-primary transition-colors">
                            <SearchIcon class="h-5 w-5" />
                        </div>
                        <Input
                            v-model="searchQuery"
                            type="search"
                            placeholder="Cari layanan (cth: Surat Pengantar Nikah)..."
                            class="pl-11 pr-24 py-6 text-base rounded-full shadow-sm border-primary/20 focus-visible:ring-primary"
                            @keyup.enter="handleSearch"
                        />
                        <Button 
                            @click="handleSearch" 
                            class="absolute right-1.5 top-1.5 bottom-1.5 rounded-full px-6"
                        >
                            Cari
                        </Button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services List Section -->
        <section class="py-16 bg-background">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <template v-if="villageServices.data.length > 0">
                        <Card v-for="service in villageServices.data" :key="service.id" class="group flex flex-col overflow-hidden hover:shadow-xl hover:shadow-primary/5 transition-all duration-300 border-border/50 hover:border-primary/30 relative">
                            <!-- Pinned Badge -->
                            <div v-if="service.is_pinned" class="absolute top-0 right-0 z-20 overflow-hidden w-[100px] h-[100px] pointer-events-none">
                                <div class="absolute top-[20px] -right-[25px] w-[130px] bg-amber-500 text-white text-[10px] font-bold text-center py-1 uppercase tracking-wider shadow-sm transform rotate-45 pointer-events-auto shadow-md border-b border-amber-600/50">
                                    Unggulan
                                </div>
                            </div>

                            <Link :href="route('village-services.show', service.slug)" class="flex flex-col h-full z-10">
                                <!-- Image Header -->
                                <div class="h-48 relative bg-muted overflow-hidden">
                                    <img
                                        v-if="service.featured_image_url"
                                        :src="service.featured_image_url"
                                        :alt="service.title"
                                        class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-500"
                                    />
                                    <div v-else class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-primary/5 to-primary/10 text-primary/40">
                                        <BuildingIcon class="w-16 h-16 mb-2 opacity-50" />
                                        <span class="text-sm font-medium tracking-wide uppercase">Layanan Desa</span>
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>
                                
                                <CardContent class="p-6 flex-1 flex flex-col bg-card relative z-10">
                                    <h3 class="font-bold text-xl mb-3 group-hover:text-primary transition-colors line-clamp-2 leading-tight">
                                        {{ service.title }}
                                    </h3>
                                    
                                    <p class="text-muted-foreground text-sm line-clamp-3 mb-6 flex-1">
                                        {{ service.excerpt || 'Klik untuk melihat detail persyaratan dan prosedur layanan administrasi ini.' }}
                                    </p>
                                    
                                    <div class="grid grid-cols-2 gap-3 mb-6">
                                        <div class="flex items-center gap-2 text-xs text-muted-foreground bg-muted/50 p-2 rounded-md">
                                            <ClockIcon class="w-4 h-4 text-primary/70 shrink-0" />
                                            <span class="truncate font-medium">{{ service.processing_time || 'Kondisional' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-muted-foreground bg-muted/50 p-2 rounded-md">
                                            <InfoIcon class="w-4 h-4 text-emerald-500 shrink-0" />
                                            <span class="truncate font-medium">{{ service.cost || 'Gratis' }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between pt-4 border-t border-border/50 mt-auto">
                                        <span class="text-xs font-medium text-muted-foreground">
                                            Diperbarui: {{ formatDate(service.timestamps?.updated_at || service.published_at || '') }}
                                        </span>
                                        <div v-if="service.downloadable_form_url" class="flex items-center gap-1 text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full border border-blue-100" title="Tersedia formulir yang dapat diunduh">
                                            <FileTextIcon class="w-3.5 h-3.5" />
                                            <span>Form</span>
                                        </div>
                                    </div>
                                </CardContent>
                            </Link>
                        </Card>
                    </template>
                    <template v-else>
                        <div class="col-span-full py-24 text-center bg-muted/30 rounded-2xl border border-dashed border-border/60">
                            <div class="bg-background w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-border">
                                <SearchIcon class="w-10 h-10 text-muted-foreground opacity-50" />
                            </div>
                            <h3 class="text-xl font-bold text-foreground mb-2">Informasi Tidak Ditemukan</h3>
                            <p class="text-muted-foreground max-w-md mx-auto">
                                Kami tidak dapat menemukan layanan yang cocok dengan pencarian Anda. Silakan coba kata kunci lain.
                            </p>
                            <Button variant="outline" class="mt-6 rounded-full" @click="() => { searchQuery = ''; handleSearch(); }">
                                Tampilkan Semua Layanan
                            </Button>
                        </div>
                    </template>
                </div>

                <!-- Pagination -->
                <div v-if="villageServices.meta && villageServices.meta.last_page > 1" class="mt-16 flex justify-center">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <template v-for="(link, i) in villageServices.meta.links" :key="i">
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <Link 
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    link.active ? 'z-10 bg-primary text-primary-foreground font-semibold shadow-md border-primary' : 'bg-card border-border text-foreground hover:bg-muted/80',
                                    'relative inline-flex items-center px-4 py-2 border text-sm',
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === villageServices.meta.links.length - 1 ? 'rounded-r-md' : '',
                                    'transition-all duration-200'
                                ]"
                            />
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <span 
                                v-else 
                                v-html="link.label" 
                                class="relative inline-flex items-center px-4 py-2 border border-border bg-muted/50 text-muted-foreground text-sm font-medium"
                                :class="[
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === villageServices.meta.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                        </template>
                    </nav>
                </div>
            </div>
        </section>
        
        <!-- Info Banner -->
        <section class="border-t border-border bg-muted/30 py-12">
            <div class="container mx-auto px-4 text-center max-w-3xl">
                <div class="inline-flex items-center justify-center p-3 bg-primary/10 text-primary rounded-full mb-4">
                    <InfoIcon class="w-6 h-6" />
                </div>
                <h2 class="text-2xl font-bold mb-3">Butuh Bantuan Lebih Lanjut?</h2>
                <p class="text-muted-foreground mb-6">
                    Jika Anda memiliki pertanyaan mengenai persyaratan administrasi atau tidak menemukan layanan yang Anda cari, silakan datang langsung ke kantor desa pada jam kerja.
                </p>
                <div class="flex flex-wrap justify-center gap-4 text-sm font-medium">
                    <div class="bg-background px-4 py-2 rounded-full border border-border shadow-sm flex items-center gap-2 text-foreground">
                        <ClockIcon class="w-4 h-4 text-primary" />
                        Senin - Jumat, 08:00 - 15:00 WIB
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
