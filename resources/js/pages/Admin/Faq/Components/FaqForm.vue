<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Loader2 } from 'lucide-vue-next';
import FormInput from '@/components/forms/FormInput.vue';
import FormSelect from '@/components/forms/FormSelect.vue';
import FormSwitch from '@/components/forms/FormSwitch.vue';
import FormTextarea from '@/components/forms/FormTextarea.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import RichTextEditor from '@/components/ui/editor/RichTextEditor.vue';
import type { Faq } from '@/types/faq';

const props = defineProps<{
    faq?: Faq;
    isEdit?: boolean;
}>();

const form = useForm({
    question: props.faq?.question ?? '',
    answer: props.faq?.answer ?? '',
    category: props.faq?.category ?? '',
    sort_order: props.faq?.sort_order ?? 0,
    is_featured: props.faq?.is_featured ?? false,
    status: props.faq?.status ?? 'draft',
    published_at: props.faq?.published_at ? new Date(props.faq.published_at).toISOString().slice(0, 16) : '',
    meta_title: props.faq?.meta_title ?? '',
    meta_description: props.faq?.meta_description ?? '',
    canonical_url: props.faq?.canonical_url ?? '',
    _method: props.isEdit ? 'PUT' : 'POST',
});

const submit = () => {
    if (props.isEdit && props.faq) {
        form.post(route('admin.faqs.update', props.faq.ulid), {
            preserveScroll: true,
        });
    } else {
        form.post(route('admin.faqs.store'), {
            preserveScroll: true,
        });
    }
};

const statusOptions = [
    { value: 'draft', label: 'Draf' },
    { value: 'published', label: 'Diterbitkan' },
    { value: 'archived', label: 'Diarsipkan' },
];
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <Card>
            <CardHeader>
                <CardTitle>Informasi Utama</CardTitle>
                <CardDescription>Pertanyaan dan jawaban FAQ.</CardDescription>
            </CardHeader>
            <CardContent class="space-y-4">
                <FormInput
                    v-model="form.question"
                    label="Pertanyaan"
                    id="question"
                    :error="form.errors.question"
                    required
                />
                
                <div>
                    <label class="block font-medium text-sm text-gray-700 mb-1">Jawaban <span class="text-red-500">*</span></label>
                    <RichTextEditor
                        v-model="form.answer"
                        id="answer"
                    />
                    <p v-if="form.errors.answer" class="text-sm text-red-600 mt-1">{{ form.errors.answer }}</p>
                </div>
            </CardContent>
        </Card>

        <Card>
            <CardHeader>
                <CardTitle>Kategorisasi & Urutan</CardTitle>
                <CardDescription>Atur pengelompokan dan urutan tampilan.</CardDescription>
            </CardHeader>
            <CardContent>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <FormInput
                        v-model="form.category"
                        label="Kategori"
                        id="category"
                        :error="form.errors.category"
                        placeholder="Contoh: Umum, Layanan, Fasilitas"
                    />

                    <FormInput
                        v-model="form.sort_order"
                        type="number"
                        min="0"
                        label="Urutan Tampil"
                        id="sort_order"
                        :error="form.errors.sort_order"
                        help="Angka lebih kecil akan tampil lebih dulu (Ascending)"
                    />
                </div>
                <div class="mt-4">
                    <FormSwitch
                        v-model="form.is_featured"
                        label="Jadikan FAQ Sorotan"
                        id="is_featured"
                        :error="form.errors.is_featured"
                        description="FAQ ini akan diutamakan penampilannya."
                    />
                </div>
            </CardContent>
        </Card>

        <Card>
            <CardHeader>
                <CardTitle>Status & Waktu Publikasi</CardTitle>
            </CardHeader>
            <CardContent>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <FormSelect
                        v-model="form.status"
                        label="Status Publikasi"
                        id="status"
                        :options="statusOptions"
                        :error="form.errors.status"
                        required
                    />

                    <FormInput
                        v-if="form.status === 'published'"
                        v-model="form.published_at"
                        type="datetime-local"
                        label="Waktu Terbit"
                        id="published_at"
                        :error="form.errors.published_at"
                    />
                </div>
            </CardContent>
        </Card>

        <Card>
            <CardHeader>
                <CardTitle>SEO & Metadata (Opsional)</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <FormInput
                    v-model="form.meta_title"
                    label="Meta Title"
                    id="meta_title"
                    :error="form.errors.meta_title"
                    help="Maksimal 60 karakter."
                />

                <FormTextarea
                    v-model="form.meta_description"
                    label="Meta Description"
                    id="meta_description"
                    :error="form.errors.meta_description"
                    :rows="3"
                    help="Maksimal 160 karakter."
                />

                <FormInput
                    v-model="form.canonical_url"
                    label="Canonical URL"
                    id="canonical_url"
                    :error="form.errors.canonical_url"
                />
            </CardContent>
        </Card>

        <div class="flex items-center justify-end gap-4">
            <Button
                variant="outline"
                type="button"
                :disabled="form.processing"
                @click="route('admin.faqs.index')"
            >
                Batal
            </Button>
            <Button
                type="submit"
                :disabled="form.processing"
            >
                <Loader2 v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                {{ isEdit ? 'Simpan Perubahan' : 'Simpan FAQ' }}
            </Button>
        </div>
    </form>
</template>
