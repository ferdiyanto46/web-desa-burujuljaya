<script setup lang="ts">
import { Search } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    search?: string;
    placeholder?: string;
}

const props = withDefaults(defineProps<Props>(), {
    search: '',
    placeholder: 'Cari data...',
});

const emit = defineEmits<{
    (e: 'update:search', value: string): void;
    (e: 'search'): void;
}>();

const modelValue = computed({
    get: () => props.search,
    set: (val) => emit('update:search', val),
});
</script>

<template>
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 py-4">
        <div class="flex flex-1 items-center space-x-2 w-full sm:max-w-sm relative">
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <Search class="h-4 w-4 text-gray-400" />
                </div>
                <input
                    v-model="modelValue"
                    type="search"
                    :placeholder="placeholder"
                    @keyup.enter="emit('search')"
                    class="block w-full rounded-md border border-gray-300 bg-white py-2 pl-10 pr-3 text-sm placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 dark:placeholder-gray-400 dark:focus:border-blue-500"
                />
            </div>
        </div>
        <div class="flex items-center space-x-2 w-full sm:w-auto">
            <slot name="filters" />
            <slot name="actions" />
        </div>
    </div>
</template>
