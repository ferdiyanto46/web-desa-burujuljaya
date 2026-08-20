<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ArrowLeft, Save } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import AdminLayout from '@/layouts/AdminLayout.vue'

const props = defineProps<{
    category?: any
}>()

const form = useForm({
    name: props.category?.name || '',
    description: props.category?.description || '',
})

const submit = () => {
    if (props.category?.ulid) {
        form.put(route('admin.umkm-categories.update', props.category.ulid))
    } else {
        form.post(route('admin.umkm-categories.store'))
    }
}
</script>

<template>
    <Head title="Tambah Kategori Umkm" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight">Tambah Kategori Umkm</h2>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" as-child>
                        <Link :href="route('admin.umkm-categories.index')">
                            <ArrowLeft class="w-4 h-4 mr-2" />
                            Kembali
                        </Link>
                    </Button>
                </div>
            </div>
        </template>

        <div class="max-w-2xl mx-auto space-y-6">
            <Card>
                <CardHeader>
                    <CardTitle>Informasi Kategori</CardTitle>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div class="space-y-2">
                            <Label for="name">Nama Kategori</Label>
                            <Input id="name" v-model="form.name" required />
                            <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="description">Deskripsi</Label>
                            <Textarea id="description" v-model="form.description" rows="4" />
                            <p v-if="form.errors.description" class="text-sm text-red-500">{{ form.errors.description }}</p>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <Button type="submit" :disabled="form.processing">
                                <Save class="w-4 h-4 mr-2" />
                                Simpan
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>