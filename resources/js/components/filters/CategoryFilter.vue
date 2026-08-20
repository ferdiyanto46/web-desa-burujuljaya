<script setup lang="ts">
import { computed } from 'vue';
import FormSelect from '../forms/FormSelect.vue';

interface Props {
    modelValue: string | number | null;
    placeholder?: string;
    categories?: { id: number; name: string }[];
}

const props = withDefaults(defineProps<Props>(), {
    placeholder: 'Filter Kategori',
    categories: () => [],
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'filter', value: string): void;
}>();

const options = computed(() => {
    const list = Array.isArray(props.categories) ? props.categories : ((props.categories as any)?.data ?? []);
    return [
        { label: 'Semua Kategori', value: '' },
        ...list.map((c: any) => ({ label: c.name, value: c.id.toString() }))
    ];
});

const proxyValue = computed({
    get: () => props.modelValue?.toString() || '',
    set: (val) => {
        emit('update:modelValue', val);
        emit('filter', val);
    },
});
</script>

<template>
    <div class="w-full sm:w-48">
        <FormSelect
            v-model="proxyValue"
            :options="options"
            :placeholder="placeholder"
        />
    </div>
</template>
