<script setup lang="ts">
import { computed } from 'vue';
import { useBreadcrumb } from '@/composables/useBreadcrumb';
import { getBreadcrumbsForRoute } from '@/constants/navigation';
import { route } from '@/lib/route';
import AppBreadcrumb from '../molecules/AppBreadcrumb.vue';

interface Props {
    title: string;
    description?: string;
    showBreadcrumbs?: boolean;
}

withDefaults(defineProps<Props>(), {
    showBreadcrumbs: true,
});


const { items } = useBreadcrumb();

// If breadcrumb items are not set manually, try to auto-generate based on route
const breadcrumbs = computed(() => {
    if (items.value.length > 0) {
        return items.value;
    }

    const currentRouteName = (route as any)().current() as string;

    return getBreadcrumbsForRoute(currentRouteName);
});
</script>

<template>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <AppBreadcrumb v-if="showBreadcrumbs" :items="breadcrumbs" class="mb-4" />
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                {{ title }}
            </h1>
            <p v-if="description" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                {{ description }}
            </p>
        </div>
        <div class="flex-shrink-0 flex items-center">
            <slot />
        </div>
    </div>
</template>
