import { ref } from 'vue';

export interface BreadcrumbItem {
    label: string;
    url?: string;
}

export function useBreadcrumb() {
    const items = ref<BreadcrumbItem[]>([]);

    const setItems = (newItems: BreadcrumbItem[]) => {
        items.value = newItems;
    };

    const addItem = (item: BreadcrumbItem) => {
        items.value.push(item);
    };

    const clearItems = () => {
        items.value = [];
    };

    return {
        items,
        setItems,
        addItem,
        clearItems,
    };
}
