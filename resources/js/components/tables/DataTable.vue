<script setup lang="ts">
interface Column {
    key: string;
    label: string;
    sortable?: boolean;
    align?: 'left' | 'center' | 'right';
}

interface Props {
    columns: Column[];
    items: any[];
    loading?: boolean;
    sortKey?: string;
    sortOrder?: 'asc' | 'desc';
}

defineProps<Props>();

const emit = defineEmits<{
    (e: 'sort', key: string): void;
}>();
</script>

<template>
    <div class="overflow-x-auto rounded-lg ring-1 ring-gray-200 dark:ring-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th 
                        v-for="col in columns" 
                        :key="col.key"
                        scope="col" 
                        class="px-6 py-3 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400"
                        :class="[
                            col.align === 'center' ? 'text-center' : col.align === 'right' ? 'text-right' : 'text-left',
                            col.sortable ? 'cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700' : ''
                        ]"
                        @click="col.sortable && emit('sort', col.key)"
                    >
                        <div class="flex items-center space-x-1" :class="[col.align === 'center' ? 'justify-center' : col.align === 'right' ? 'justify-end' : 'justify-start']">
                            <span>{{ col.label }}</span>
                            <span v-if="col.sortable && sortKey === col.key">
                                <svg v-if="sortOrder === 'asc'" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                                <svg v-else class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </div>
                    </th>
                    <th v-if="$slots.actions" scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                <tr v-if="loading">
                    <td :colspan="$slots.actions ? columns.length + 1 : columns.length" class="px-6 py-12 text-center">
                        <div class="flex justify-center">
                            <svg class="h-8 w-8 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </td>
                </tr>
                <tr v-else-if="items.length === 0">
                    <td :colspan="$slots.actions ? columns.length + 1 : columns.length" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        Tidak ada data.
                    </td>
                </tr>
                <template v-else>
                    <tr v-for="(item, index) in items" :key="item.id || index" class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td 
                            v-for="col in columns" 
                            :key="col.key"
                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-300"
                            :class="[col.align === 'center' ? 'text-center' : col.align === 'right' ? 'text-right' : 'text-left']"
                        >
                            <slot :name="`cell-${col.key}`" :item="item">
                                {{ item[col.key] }}
                            </slot>
                        </td>
                        <td v-if="$slots.actions" class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                            <slot name="actions" :item="item" />
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>
