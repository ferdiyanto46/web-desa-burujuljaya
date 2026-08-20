<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { ArrowLeftIcon } from 'lucide-vue-next';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { route } from '@/lib/route';

interface Role {
    id: number;
    name: string;
}

const props = defineProps<{
    roles: Role[];
}>();

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: props.roles && props.roles.length > 0 ? props.roles[0].name : 'user',
    is_active: true,
});

const submit = () => {
    form.post(route('admin.users.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Tambah Pengguna" />

    <DashboardContainer>
        <template #header>
            <div class="flex flex-col">
                <button
                    type="button"
                    @click="router.visit(route('admin.users.index'))"
                    class="mb-4 inline-flex w-fit items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                >
                    <ArrowLeftIcon class="mr-1 h-4 w-4" />
                    Kembali ke Daftar Pengguna
                </button>
                <PageTitle 
                    title="Tambah Pengguna Baru" 
                    description="Buat akun pengguna baru untuk mengakses sistem."
                />
            </div>
        </template>

        <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form @submit.prevent="submit" class="space-y-6 max-w-2xl">
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                        required
                    />
                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                        required
                    />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                        required
                    />
                    <p v-if="form.errors.password" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ form.errors.password }}</p>
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                    <select
                        id="role"
                        v-model="form.role"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                        required
                    >
                        <option v-for="role in roles" :key="role.id" :value="role.name">
                            {{ role.name }}
                        </option>
                    </select>
                    <p v-if="form.errors.role" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ form.errors.role }}</p>
                </div>

                <div class="flex items-center">
                    <input
                        id="is_active"
                        v-model="form.is_active"
                        type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                    />
                    <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                        Akun Aktif
                    </label>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button
                        type="button"
                        @click="router.visit(route('admin.users.index'))"
                        class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Pengguna' }}
                    </button>
                </div>
            </form>
        </div>
    </DashboardContainer>
</template>
