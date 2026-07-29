<script setup lang="ts">
import { ref, watch } from 'vue';
import { XIcon, ImageIcon } from 'lucide-vue-next';

interface Props {
    src?: string | null;
    alt?: string;
    file?: File | null;
    removable?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    alt: 'Image preview',
    removable: true,
});

const emit = defineEmits<{
    (e: 'remove'): void;
}>();

const objectUrl = ref<string | null>(null);

watch(() => props.file, (newFile) => {
    if (objectUrl.value) {
        URL.revokeObjectURL(objectUrl.value);
        objectUrl.value = null;
    }
    if (newFile) {
        objectUrl.value = URL.createObjectURL(newFile);
    }
}, { immediate: true });

const previewSrc = computed(() => {
    return objectUrl.value || props.src || null;
});

// Avoid memory leaks
import { onBeforeUnmount, computed } from 'vue';
onBeforeUnmount(() => {
    if (objectUrl.value) {
        URL.revokeObjectURL(objectUrl.value);
    }
});
</script>

<template>
    <div class="relative inline-flex items-center justify-center overflow-hidden rounded-lg border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800"
         :class="[previewSrc ? '' : 'p-8']"
    >
        <template v-if="previewSrc">
            <img :src="previewSrc" :alt="alt" class="h-full w-full object-cover max-h-64" />
            <button 
                v-if="removable"
                type="button"
                @click.prevent="emit('remove')"
                class="absolute right-2 top-2 rounded-full bg-red-500 p-1.5 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
                title="Hapus gambar"
            >
                <XIcon class="h-4 w-4" />
            </button>
        </template>
        <template v-else>
            <div class="flex flex-col items-center text-gray-400 dark:text-gray-500">
                <ImageIcon class="h-10 w-10 mb-2" />
                <span class="text-sm">Belum ada gambar</span>
            </div>
        </template>
    </div>
</template>
