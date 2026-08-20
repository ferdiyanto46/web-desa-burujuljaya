<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import RichTextEditor from '@/components/ui/editor/RichTextEditor.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import ImageUploader from '@/components/ui/upload/ImageUploader.vue';
import type { VillagePage } from '@/types/village-page';

const props = defineProps<{
    page?: VillagePage;
    statuses: string[];
    pageTypes: string[];
}>();

const form = useForm({
    title: props.page?.title ?? '',
    page_type: props.page?.page_type ?? '',
    excerpt: props.page?.excerpt ?? '',
    content: props.page?.content ?? '',
    status: props.page?.status ?? 'draft',
    is_pinned: props.page?.is_pinned ?? false,
    sort_order: props.page?.sort_order ?? 0,
    meta_title: props.page?.meta_title ?? '',
    meta_description: props.page?.meta_description ?? '',
    featured_image: null as File | null,
});

const submit = () => {
    if (props.page) {
        form.transform((data) => ({
            ...data,
            _method: 'put',
        })).post(route('admin.village-pages.update', props.page.ulid), {
            forceFormData: true,
        });
    } else {
        form.post(route('admin.village-pages.store'), {
            forceFormData: true,
        });
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <Card>
                    <CardContent class="pt-6 space-y-4">
                        <div class="space-y-2">
                            <Label for="title">Judul Halaman <span class="text-destructive">*</span></Label>
                            <Input
                                id="title"
                                v-model="form.title"
                                required
                                :class="{ 'border-destructive': form.errors.title }"
                            />
                            <p v-if="form.errors.title" class="text-sm text-destructive">{{ form.errors.title }}</p>
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="excerpt">Kutipan Ringkas (Opsional)</Label>
                            <Textarea
                                id="excerpt"
                                v-model="form.excerpt"
                                rows="3"
                                :class="{ 'border-destructive': form.errors.excerpt }"
                            />
                            <p v-if="form.errors.excerpt" class="text-sm text-destructive">{{ form.errors.excerpt }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label>Konten Lengkap <span class="text-destructive">*</span></Label>
                            <RichTextEditor
                                v-model="form.content"
                                :error="form.errors.content"
                            />
                            <p v-if="form.errors.content" class="text-sm text-destructive">{{ form.errors.content }}</p>
                        </div>
                    </CardContent>
                </Card>
                
                <Card>
                    <CardContent class="pt-6 space-y-4">
                        <div class="space-y-2">
                            <Label>Metadata SEO (Opsional)</Label>
                            <p class="text-sm text-muted-foreground">Isi untuk mengoptimalkan mesin pencari (Google, dll).</p>
                        </div>
                        <div class="space-y-2">
                            <Label for="meta_title">Meta Title</Label>
                            <Input
                                id="meta_title"
                                v-model="form.meta_title"
                                :class="{ 'border-destructive': form.errors.meta_title }"
                            />
                            <p v-if="form.errors.meta_title" class="text-sm text-destructive">{{ form.errors.meta_title }}</p>
                        </div>
                        <div class="space-y-2">
                            <Label for="meta_description">Meta Description</Label>
                            <Textarea
                                id="meta_description"
                                v-model="form.meta_description"
                                rows="2"
                                :class="{ 'border-destructive': form.errors.meta_description }"
                            />
                            <p v-if="form.errors.meta_description" class="text-sm text-destructive">{{ form.errors.meta_description }}</p>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <div class="space-y-6">
                <Card>
                    <CardContent class="pt-6 space-y-4">
                        <div class="space-y-2">
                            <Label>Status & Pengaturan</Label>
                        </div>
                        
                        <div class="space-y-2">
                            <Label>Tipe Halaman <span class="text-destructive">*</span></Label>
                            <Select v-model="form.page_type">
                                <SelectTrigger>
                                    <SelectValue placeholder="Pilih Tipe Halaman" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="type in pageTypes" :key="type" :value="type">
                                        {{ type }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.page_type" class="text-sm text-destructive">{{ form.errors.page_type }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label>Status Terbit <span class="text-destructive">*</span></Label>
                            <Select v-model="form.status">
                                <SelectTrigger>
                                    <SelectValue placeholder="Pilih Status" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="status in statuses" :key="status" :value="status">
                                        {{ status === 'published' ? 'Terbit' : (status === 'draft' ? 'Draf' : 'Diarsipkan') }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.status" class="text-sm text-destructive">{{ form.errors.status }}</p>
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="sort_order">Urutan (Sort Order)</Label>
                            <Input
                                id="sort_order"
                                type="number"
                                v-model="form.sort_order"
                                :class="{ 'border-destructive': form.errors.sort_order }"
                            />
                            <p v-if="form.errors.sort_order" class="text-sm text-destructive">{{ form.errors.sort_order }}</p>
                        </div>

                        <div class="flex items-center justify-between space-x-2 pt-2">
                            <Label for="is_pinned" class="flex flex-col space-y-1">
                                <span>Sematkan Halaman</span>
                                <span class="font-normal text-xs text-muted-foreground">Tampilkan di posisi atas daftar.</span>
                            </Label>
                            <Switch
                                id="is_pinned"
                                :checked="form.is_pinned"
                                @update:checked="form.is_pinned = $event"
                            />
                        </div>
                        <p v-if="form.errors.is_pinned" class="text-sm text-destructive">{{ form.errors.is_pinned }}</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="pt-6 space-y-4">
                        <div class="space-y-2">
                            <Label>Foto Halaman (Opsional)</Label>
                            <ImageUploader
                                v-model="form.featured_image"
                                :initial-url="page?.featured_image_url"
                                :error="form.errors.featured_image"
                            />
                            <p v-if="form.errors.featured_image" class="text-sm text-destructive mt-1">{{ form.errors.featured_image }}</p>
                        </div>
                    </CardContent>
                </Card>

                <div class="flex justify-end gap-4">
                    <Button type="button" variant="outline" as-child :disabled="form.processing">
                        <Link :href="route('admin.village-pages.index')">
                            Batal
                        </Link>
                    </Button>
                    <Button type="submit" :disabled="form.processing">
                        {{ page ? 'Simpan Perubahan' : 'Buat Halaman' }}
                    </Button>
                </div>
            </div>
        </div>
    </form>
</template>
