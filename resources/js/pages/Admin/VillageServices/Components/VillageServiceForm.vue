<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { SaveIcon, XIcon, ArrowLeftIcon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import RichTextEditor from '@/components/ui/editor/RichTextEditor.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import FileUploader from '@/components/ui/upload/FileUploader.vue';
import ImageUploader from '@/components/ui/upload/ImageUploader.vue';
import type { VillageService } from '@/types/village-service';

const props = defineProps<{
    villageService?: VillageService;
    statuses: Record<string, string>;
    isEdit?: boolean;
}>();

const form = useForm({
    title: props.villageService?.title || '',
    excerpt: props.villageService?.excerpt || '',
    description: props.villageService?.description || '',
    
    requirements: props.villageService?.requirements || '',
    procedures: props.villageService?.procedures || '',
    processing_time: props.villageService?.processing_time || '',
    cost: props.villageService?.cost || '',
    service_hours: props.villageService?.service_hours || '',
    
    contact_person: props.villageService?.contact_person || '',
    phone: props.villageService?.phone || '',
    email: props.villageService?.email || '',
    
    status: props.villageService?.status || 'draft',
    is_pinned: props.villageService?.is_pinned || false,
    sort_order: props.villageService?.sort_order || 0,
    
    meta_title: props.villageService?.meta?.meta_title || '',
    meta_description: props.villageService?.meta?.meta_description || '',
    
    featured_image: null as File | null,
    downloadable_form: null as File | null,
    
    // For UI state only
    _featured_image_preview: props.villageService?.featured_image_url || '',
    _downloadable_form_name: props.villageService?.downloadable_form_name || '',
    
    // _method is injected automatically by our service wrapper for edits
});

const handleImageChange = (file: File | null) => {
    form.featured_image = file;
};

const handleFileChange = (file: File | null) => {
    form.downloadable_form = file;
};

const handleSubmit = () => {
    if (props.isEdit && props.villageService) {
        // Our VillageServiceService handles the _method=PUT merging
        import('@/services/village-service').then(({ VillageServiceService }) => {
            VillageServiceService.update(props.villageService!.ulid, form.data());
        });
    } else {
        import('@/services/village-service').then(({ VillageServiceService }) => {
            VillageServiceService.store(form.data());
        });
    }
};
</script>

<template>
    <form @submit.prevent="handleSubmit" class="space-y-8">
        <div class="flex items-center justify-between">
            <Button variant="outline" as-child>
                <Link :href="route('admin.village-services.index')">
                    <ArrowLeftIcon class="w-4 h-4 mr-2" />
                    Kembali
                </Link>
            </Button>
            <div class="flex gap-2">
                <Button type="submit" :disabled="form.processing">
                    <SaveIcon class="w-4 h-4 mr-2" />
                    Simpan
                </Button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-8">
                <!-- Basic Info -->
                <Card>
                    <CardHeader>
                        <CardTitle>Informasi Utama</CardTitle>
                        <CardDescription>Detail utama dari layanan desa ini.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="space-y-2">
                            <Label for="title">Nama Layanan <span class="text-destructive">*</span></Label>
                            <Input 
                                id="title" 
                                v-model="form.title" 
                                placeholder="Cth: Surat Pengantar Nikah" 
                                required 
                            />
                            <p class="text-xs text-destructive" v-if="form.errors.title">{{ form.errors.title }}</p>
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="excerpt">Ringkasan (Opsional)</Label>
                            <Textarea 
                                id="excerpt" 
                                v-model="form.excerpt" 
                                placeholder="Penjelasan singkat layanan ini..." 
                                rows="2" 
                            />
                            <p class="text-xs text-destructive" v-if="form.errors.excerpt">{{ form.errors.excerpt }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label>Deskripsi Lengkap <span class="text-destructive">*</span></Label>
                            <RichTextEditor 
                                v-model="form.description" 
                            />
                            <p class="text-xs text-destructive" v-if="form.errors.description">{{ form.errors.description }}</p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Syarat & Ketentuan -->
                <Card>
                    <CardHeader>
                        <CardTitle>Persyaratan & Prosedur</CardTitle>
                        <CardDescription>Tulis persyaratan dan langkah-langkah dalam poin-poin.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="space-y-2">
                            <Label>Persyaratan</Label>
                            <RichTextEditor 
                                v-model="form.requirements" 
                            />
                            <p class="text-xs text-destructive" v-if="form.errors.requirements">{{ form.errors.requirements }}</p>
                        </div>
                        <div class="space-y-2">
                            <Label>Prosedur / Langkah-langkah</Label>
                            <RichTextEditor 
                                v-model="form.procedures" 
                            />
                            <p class="text-xs text-destructive" v-if="form.errors.procedures">{{ form.errors.procedures }}</p>
                        </div>
                    </CardContent>
                </Card>
                
                <!-- Detail Layanan -->
                <Card>
                    <CardHeader>
                        <CardTitle>Detail Operasional</CardTitle>
                    </CardHeader>
                    <CardContent class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <Label for="processing_time">Waktu Proses</Label>
                            <Input id="processing_time" v-model="form.processing_time" placeholder="Cth: 1 Hari Kerja" />
                            <p class="text-xs text-destructive" v-if="form.errors.processing_time">{{ form.errors.processing_time }}</p>
                        </div>
                        <div class="space-y-2">
                            <Label for="cost">Biaya</Label>
                            <Input id="cost" v-model="form.cost" placeholder="Cth: Gratis / Rp. 50.000" />
                            <p class="text-xs text-destructive" v-if="form.errors.cost">{{ form.errors.cost }}</p>
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <Label for="service_hours">Jam Layanan</Label>
                            <Input id="service_hours" v-model="form.service_hours" placeholder="Cth: Senin - Jumat (08:00 - 15:00)" />
                            <p class="text-xs text-destructive" v-if="form.errors.service_hours">{{ form.errors.service_hours }}</p>
                        </div>
                    </CardContent>
                </Card>

            </div>

            <div class="space-y-8">
                <!-- Status & Publikasi -->
                <Card>
                    <CardHeader>
                        <CardTitle>Publikasi</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="space-y-2">
                            <Label for="status">Status</Label>
                            <Select v-model="form.status">
                                <SelectTrigger id="status">
                                    <SelectValue placeholder="Pilih status" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="(label, value) in statuses" :key="value" :value="value">
                                        {{ label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p class="text-xs text-destructive" v-if="form.errors.status">{{ form.errors.status }}</p>
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="sort_order">Urutan Tampil (Opsional)</Label>
                            <Input id="sort_order" type="number" min="0" v-model="form.sort_order" />
                            <p class="text-xs text-destructive" v-if="form.errors.sort_order">{{ form.errors.sort_order }}</p>
                        </div>

                        <div class="flex items-center space-x-2 pt-2 border-t">
                            <Switch id="is_pinned" :checked="form.is_pinned" @update:checked="(val: boolean) => form.is_pinned = val" />
                            <Label for="is_pinned">Sematkan di Beranda</Label>
                        </div>
                        <p class="text-xs text-destructive" v-if="form.errors.is_pinned">{{ form.errors.is_pinned }}</p>
                    </CardContent>
                </Card>

                <!-- Media -->
                <Card>
                    <CardHeader>
                        <CardTitle>Media & Lampiran</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="space-y-2">
                            <Label>Gambar Layanan (Opsional)</Label>
                            <ImageUploader 
                                :model-value="form.featured_image"
                                :preview-url="form._featured_image_preview"
                                @change="handleImageChange"
                            />
                            <p class="text-xs text-destructive" v-if="form.errors.featured_image">{{ form.errors.featured_image }}</p>
                        </div>
                        
                        <div class="space-y-2 border-t pt-6">
                            <Label>Formulir/Lampiran (Opsional)</Label>
                            <div v-if="form._downloadable_form_name && !form.downloadable_form" class="mb-4 p-3 border rounded-md flex items-center justify-between bg-muted/50">
                                <span class="text-sm truncate mr-4">{{ form._downloadable_form_name }}</span>
                                <Button type="button" variant="ghost" size="sm" class="text-destructive h-8 px-2" @click="() => { form.downloadable_form = null; form._downloadable_form_name = ''; }">
                                    <XIcon class="h-4 w-4" />
                                </Button>
                            </div>
                            <FileUploader 
                                :model-value="form.downloadable_form"
                                accept=".pdf,.doc,.docx"
                                help-text="Format yang diizinkan: PDF, DOC, DOCX. Maks: 10MB"
                                @change="handleFileChange"
                            />
                            <p class="text-xs text-destructive" v-if="form.errors.downloadable_form">{{ form.errors.downloadable_form }}</p>
                        </div>
                    </CardContent>
                </Card>
                
                <!-- Kontak Layanan -->
                <Card>
                    <CardHeader>
                        <CardTitle>Kontak Layanan</CardTitle>
                        <CardDescription>Opsional, jika ada kontak khusus.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-2">
                            <Label for="contact_person">Nama Kontak / Bidang</Label>
                            <Input id="contact_person" v-model="form.contact_person" placeholder="Cth: Kasi Pemerintahan" />
                            <p class="text-xs text-destructive" v-if="form.errors.contact_person">{{ form.errors.contact_person }}</p>
                        </div>
                        <div class="space-y-2">
                            <Label for="phone">Nomor Telepon/WA</Label>
                            <Input id="phone" v-model="form.phone" placeholder="Cth: 081234567890" />
                            <p class="text-xs text-destructive" v-if="form.errors.phone">{{ form.errors.phone }}</p>
                        </div>
                    </CardContent>
                </Card>

                <!-- SEO -->
                <Card>
                    <CardHeader>
                        <CardTitle>SEO (Opsional)</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-2">
                            <Label for="meta_title">Meta Title</Label>
                            <Input id="meta_title" v-model="form.meta_title" />
                            <p class="text-xs text-destructive" v-if="form.errors.meta_title">{{ form.errors.meta_title }}</p>
                        </div>
                        <div class="space-y-2">
                            <Label for="meta_description">Meta Description</Label>
                            <Textarea id="meta_description" v-model="form.meta_description" rows="3" />
                            <p class="text-xs text-destructive" v-if="form.errors.meta_description">{{ form.errors.meta_description }}</p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </form>
</template>
