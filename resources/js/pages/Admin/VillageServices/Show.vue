<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeftIcon, PencilIcon, TrashIcon, FileTextIcon } from 'lucide-vue-next';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import type { VillageService } from '@/types/village-service';

const props = defineProps<{
    villageService: { data: VillageService };
}>();

const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
    }).format(new Date(dateString));
};

const handleDelete = () => {
    if (confirm(`Apakah Anda yakin ingin menghapus layanan "${props.villageService.data.title}"?`)) {
        router.delete(route('admin.village-services.destroy', props.villageService.data.ulid));
    }
};
</script>

<template>
    <Head :title="villageService.data.title" />

    <DashboardContainer>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="outline" size="icon" as-child>
                        <Link :href="route('admin.village-services.index')">
                            <ArrowLeftIcon class="w-4 h-4" />
                        </Link>
                    </Button>
                    <PageTitle 
                        title="Detail Layanan" 
                        description="Lihat informasi lengkap layanan."
                    />
                </div>
                <div class="flex items-center gap-2">
                    <Button variant="outline" as-child>
                        <Link :href="route('admin.village-services.edit', villageService.data.ulid)">
                            <PencilIcon class="w-4 h-4 mr-2" />
                            Edit
                        </Link>
                    </Button>
                    <Button variant="destructive" @click="handleDelete">
                        <TrashIcon class="w-4 h-4 mr-2" />
                        Hapus
                    </Button>
                </div>
            </div>
        </template>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div class="md:col-span-2 space-y-6">
                <Card>
                    <CardHeader>
                        <div class="flex justify-between items-start">
                            <div>
                                <CardTitle class="text-2xl">{{ villageService.data.title }}</CardTitle>
                                <p class="text-muted-foreground mt-2" v-if="villageService.data.excerpt">
                                    {{ villageService.data.excerpt }}
                                </p>
                            </div>
                            <Badge :variant="villageService.data.status === 'published' ? 'default' : 'secondary'">
                                {{ villageService.data.status === 'published' ? 'Diterbitkan' : 'Draf' }}
                            </Badge>
                        </div>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div v-if="villageService.data.featured_image_url" class="aspect-video relative rounded-lg overflow-hidden bg-muted">
                            <img 
                                :src="villageService.data.featured_image_url" 
                                :alt="villageService.data.title"
                                class="object-cover w-full h-full"
                            />
                        </div>

                        <div>
                            <h3 class="font-semibold text-lg mb-2">Deskripsi Layanan</h3>
                            <div class="prose prose-sm max-w-none" v-html="villageService.data.description"></div>
                        </div>

                        <Separator v-if="villageService.data.requirements || villageService.data.procedures" />

                        <div v-if="villageService.data.requirements">
                            <h3 class="font-semibold text-lg mb-2">Persyaratan</h3>
                            <div class="prose prose-sm max-w-none" v-html="villageService.data.requirements"></div>
                        </div>

                        <div v-if="villageService.data.procedures">
                            <h3 class="font-semibold text-lg mb-2">Prosedur / Langkah-langkah</h3>
                            <div class="prose prose-sm max-w-none" v-html="villageService.data.procedures"></div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <div class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Informasi Layanan</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <span class="text-sm text-muted-foreground block">Dibuat Oleh</span>
                            <span class="font-medium">{{ villageService.data.author?.name || '-' }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-muted-foreground block">Tanggal Dibuat</span>
                            <span class="font-medium">{{ formatDate(villageService.data.timestamps?.created_at || '') }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-muted-foreground block">Tanggal Terbit</span>
                            <span class="font-medium">{{ villageService.data.published_at ? formatDate(villageService.data.published_at) : 'Belum Diterbitkan' }}</span>
                        </div>
                        <div v-if="villageService.data.is_pinned">
                            <Badge variant="outline" class="bg-amber-100 text-amber-800 border-amber-200">
                                Disematkan di Beranda
                            </Badge>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Operasional & Kontak</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <span class="text-sm text-muted-foreground block">Waktu Proses</span>
                            <span class="font-medium">{{ villageService.data.processing_time || '-' }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-muted-foreground block">Biaya Layanan</span>
                            <span class="font-medium">{{ villageService.data.cost || '-' }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-muted-foreground block">Jam Layanan</span>
                            <span class="font-medium">{{ villageService.data.service_hours || '-' }}</span>
                        </div>
                        <Separator />
                        <div>
                            <span class="text-sm text-muted-foreground block">Kontak / Bidang</span>
                            <span class="font-medium">{{ villageService.data.contact_person || '-' }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-muted-foreground block">No. Telepon/WA</span>
                            <span class="font-medium">{{ villageService.data.phone || '-' }}</span>
                        </div>
                    </CardContent>
                </Card>

                <Card v-if="villageService.data.downloadable_form_url">
                    <CardHeader>
                        <CardTitle>Lampiran Formulir</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-col gap-2 p-3 bg-muted/50 rounded-lg border">
                            <div class="flex items-center gap-3">
                                <div class="bg-blue-100 p-2 rounded-md">
                                    <FileTextIcon class="w-5 h-5 text-blue-600" />
                                </div>
                                <div class="flex-1 overflow-hidden">
                                    <p class="text-sm font-medium truncate" :title="villageService.data.downloadable_form_name">
                                        {{ villageService.data.downloadable_form_name }}
                                    </p>
                                    <p class="text-xs text-muted-foreground uppercase">
                                        {{ villageService.data.downloadable_form_extension }}
                                    </p>
                                </div>
                            </div>
                            <Button variant="default" class="w-full mt-2" as-child>
                                <a :href="villageService.data.downloadable_form_url" target="_blank" download>
                                    Unduh Formulir
                                </a>
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </DashboardContainer>
</template>
