import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { debounce } from './useDebounce';

export function useFilters<T extends Record<string, any>>(initialFilters: T, routeName: string) {
    const filters = ref<T>({ ...initialFilters });
    const isFiltering = ref(false);

    const applyFilters = debounce(() => {
        isFiltering.value = true;
        
        const cleanFilters = Object.fromEntries(
            Object.entries(filters.value).filter((entry) => entry[1] !== null && entry[1] !== '')
        );

        router.get(
            route(routeName),
            cleanFilters as any,
            { 
                preserveState: true, 
                preserveScroll: true,
                onFinish: () => {
                    isFiltering.value = false;
                }
            }
        );
    }, 300);

    const resetFilters = () => {
        filters.value = { ...initialFilters };
        applyFilters();
    };

    // Auto apply when filters change
    watch(filters, () => {
        applyFilters();
    }, { deep: true });

    return {
        filters,
        isFiltering,
        applyFilters,
        resetFilters,
    };
}
