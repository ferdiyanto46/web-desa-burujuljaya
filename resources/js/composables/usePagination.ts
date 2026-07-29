import { ref, computed } from 'vue';
import type { PaginationData, PaginationLink } from '@/types';

export function usePagination<T>(initialData?: PaginationData<T>) {
    const data = ref<PaginationData<T> | null>(initialData || null);

    const currentPage = computed(() => data.value?.current_page || 1);
    const totalPages = computed(() => data.value?.last_page || 1);
    const totalItems = computed(() => data.value?.total || 0);
    const items = computed(() => data.value?.data || []);
    
    // Links excluding "Previous" and "Next" text links usually returned by Laravel
    const pageLinks = computed(() => {
        if (!data.value?.links) {
return [];
}

        return data.value.links.filter(
            (link: PaginationLink) => !link.label.includes('Next') && !link.label.includes('Previous')
        );
    });

    const hasNextPage = computed(() => !!data.value?.next_page_url);
    const hasPrevPage = computed(() => !!data.value?.prev_page_url);

    const setPaginationData = (newData: PaginationData<T>) => {
        data.value = newData;
    };

    return {
        data,
        currentPage,
        totalPages,
        totalItems,
        items,
        pageLinks,
        hasNextPage,
        hasPrevPage,
        setPaginationData,
    };
}
