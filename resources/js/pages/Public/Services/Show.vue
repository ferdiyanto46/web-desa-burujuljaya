<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeftIcon, FileTextIcon, ClockIcon, InfoIcon, DownloadIcon, BuildingIcon, PhoneIcon, UserIcon, CheckCircle2Icon } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { VillageService } from '@/types/village-service';

defineProps<{
    villageService: { data: VillageService };
}>();

const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
    }).format(new Date(dateString));
};
</script>

<template>
    <Head>
        <title>{{ villageService.data.title }}</title>
        <meta v-if="villageService.data.meta?.meta_description || villageService.data.excerpt" 
              name="description" 
              :content="villageService.data.meta?.meta_description || villageService.data.excerpt">
    </Head>

    <PublicLayout>
        <!-- Hero Section for Service Detail -->
        <div class="bg-gradient-to-b from-primary/5 to-background border-b border-border/50 pb-8 pt-12 md:pt-16">
            <div class="container mx-auto px-4 max-w-5xl">
                <Link :href="route('village-services.index')" class="inline-flex items-center text-sm font-medium text-muted-foreground hover:text-primary transition-colors mb-6 group">
                    <ArrowLeftIcon class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" />
                    Kembali ke Daftar Layanan
                </Link>
                
                <div class="flex flex-col md:flex-row gap-6 md:gap-8 items-start">
                    <div class="flex-1 space-y-4">
                        <Badge v-if="villageService.data.is_pinned" variant="outline" class="bg-amber-100 text-amber-800 border-amber-200 mb-2">
                            Layanan Unggulan
                        </Badge>
                        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight text-foreground leading-tight">
                            {{ villageService.data.title }}
                        </h1>
                        <p v-if="villageService.data.excerpt" class="text-lg text-muted-foreground max-w-3xl leading-relaxed">
                            {{ villageService.data.excerpt }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 max-w-5xl py-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content (Left Column) -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Featured Image (If any) -->
                    <div v-if="villageService.data.featured_image_url" class="rounded-xl overflow-hidden shadow-md border border-border/50">
                        <img 
                            :src="villageService.data.featured_image_url" 
                            :alt="villageService.data.title"
                            class="w-full h-auto object-cover max-h-[400px]"
                        />
                    </div>

                    <!-- Description Section -->
                    <section class="bg-card rounded-xl border border-border/50 shadow-sm p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-border/50">
                            <div class="bg-primary/10 p-2 rounded-lg">
                                <InfoIcon class="w-5 h-5 text-primary" />
                            </div>
                            <h2 class="text-xl font-bold text-foreground">Deskripsi Layanan</h2>
                        </div>
                        <div class="prose prose-slate max-w-none text-foreground/80 leading-relaxed" v-html="villageService.data.description"></div>
                    </section>

                    <!-- Requirements Section -->
                    <section v-if="villageService.data.requirements" class="bg-card rounded-xl border border-border/50 shadow-sm p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-border/50">
                            <div class="bg-amber-500/10 p-2 rounded-lg">
                                <FileTextIcon class="w-5 h-5 text-amber-600" />
                            </div>
                            <h2 class="text-xl font-bold text-foreground">Persyaratan</h2>
                        </div>
                        <div class="prose prose-slate max-w-none prose-li:marker:text-primary" v-html="villageService.data.requirements"></div>
                    </section>

                    <!-- Procedures Section -->
                    <section v-if="villageService.data.procedures" class="bg-card rounded-xl border border-border/50 shadow-sm p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-border/50">
                            <div class="bg-emerald-500/10 p-2 rounded-lg">
                                <CheckCircle2Icon class="w-5 h-5 text-emerald-600" />
                            </div>
                            <h2 class="text-xl font-bold text-foreground">Prosedur / Langkah-langkah</h2>
                        </div>
                        <div class="prose prose-slate max-w-none prose-ol:marker:text-primary prose-ol:marker:font-semibold" v-html="villageService.data.procedures"></div>
                    </section>
                </div>

                <!-- Sidebar (Right Column) -->
                <div class="space-y-6">
                    <!-- Operational Details Card -->
                    <Card class="border-border/50 shadow-sm overflow-hidden sticky top-24">
                        <div class="h-2 bg-primary w-full"></div>
                        <CardHeader class="pb-4">
                            <CardTitle class="text-lg flex items-center gap-2">
                                <BuildingIcon class="w-5 h-5 text-primary" />
                                Informasi Operasional
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-5">
                            <div class="flex items-start gap-3">
                                <div class="bg-muted p-2 rounded-full mt-0.5">
                                    <ClockIcon class="w-4 h-4 text-muted-foreground" />
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-foreground">Waktu Proses</h4>
                                    <p class="text-sm text-muted-foreground mt-1">{{ villageService.data.processing_time || 'Kondisional (Tergantung antrean)' }}</p>
                                </div>
                            </div>
                            
                            <Separator />
                            
                            <div class="flex items-start gap-3">
                                <div class="bg-muted p-2 rounded-full mt-0.5">
                                    <InfoIcon class="w-4 h-4 text-muted-foreground" />
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-foreground">Biaya Layanan</h4>
                                    <p class="text-sm font-semibold text-emerald-600 mt-1">{{ villageService.data.cost || 'Gratis / Tidak Dipungut Biaya' }}</p>
                                </div>
                            </div>

                            <Separator />

                            <div class="flex items-start gap-3">
                                <div class="bg-muted p-2 rounded-full mt-0.5">
                                    <BuildingIcon class="w-4 h-4 text-muted-foreground" />
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-foreground">Jam Pelayanan</h4>
                                    <p class="text-sm text-muted-foreground mt-1 whitespace-pre-line">{{ villageService.data.service_hours || 'Senin - Jumat\n08:00 - 15:00 WIB' }}</p>
                                </div>
                            </div>

                            <!-- Contact info if any -->
                            <template v-if="villageService.data.contact_person || villageService.data.phone">
                                <Separator />
                                <div class="space-y-3">
                                    <h4 class="text-sm font-medium text-foreground flex items-center gap-2">
                                        <UserIcon class="w-4 h-4 text-primary" />
                                        Narahubung Layanan
                                    </h4>
                                    
                                    <div v-if="villageService.data.contact_person" class="flex items-start gap-3 pl-1">
                                        <div class="w-1.5 h-1.5 rounded-full bg-primary mt-2"></div>
                                        <div>
                                            <p class="text-sm text-foreground font-medium">{{ villageService.data.contact_person }}</p>
                                        </div>
                                    </div>
                                    
                                    <div v-if="villageService.data.phone" class="flex items-center gap-3 pl-1">
                                        <PhoneIcon class="w-3.5 h-3.5 text-muted-foreground" />
                                        <p class="text-sm text-muted-foreground">{{ villageService.data.phone }}</p>
                                    </div>
                                </div>
                            </template>
                        </CardContent>
                    </Card>

                    <!-- Downloadable Form Card (If any) -->
                    <Card v-if="villageService.data.downloadable_form_url" class="border-blue-200 shadow-sm bg-blue-50/50">
                        <CardHeader class="pb-3">
                            <CardTitle class="text-lg flex items-center gap-2 text-blue-800">
                                <FileTextIcon class="w-5 h-5" />
                                Formulir Lampiran
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p class="text-sm text-blue-700/80 mb-4">
                                Unduh dan isi formulir berikut sebelum datang ke kantor desa untuk mempercepat proses layanan.
                            </p>
                            
                            <div class="bg-white rounded-lg border border-blue-100 p-3 flex flex-col gap-3 mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-blue-100 p-2 rounded shrink-0">
                                        <FileTextIcon class="w-6 h-6 text-blue-600" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-slate-800 truncate" :title="villageService.data.downloadable_form_name">
                                            {{ villageService.data.downloadable_form_name }}
                                        </p>
                                        <p class="text-xs text-slate-500 uppercase mt-0.5">
                                            {{ villageService.data.downloadable_form_extension }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <Button class="w-full bg-blue-600 hover:bg-blue-700 text-white" as-child>
                                <a :href="villageService.data.downloadable_form_url" target="_blank" download>
                                    <DownloadIcon class="w-4 h-4 mr-2" />
                                    Unduh Formulir
                                </a>
                            </Button>
                        </CardContent>
                    </Card>

                    <!-- Meta info -->
                    <div class="text-xs text-center text-muted-foreground/70">
                        Terakhir diperbarui: {{ formatDate(villageService.data.timestamps?.updated_at || villageService.data.published_at || '') }}
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
