<script setup lang="ts">
import ConfirmDialog from './ConfirmDialog.vue';

interface Props {
    isOpen: boolean;
    title?: string;
    itemName?: string;
    processing?: boolean;
}

withDefaults(defineProps<Props>(), {
    title: 'Hapus Data',
    itemName: 'data ini',
    processing: false,
});

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'confirm'): void;
}>();
</script>

<template>
    <ConfirmDialog
        :is-open="isOpen"
        :title="title"
        :message="`Apakah Anda yakin ingin menghapus ${itemName}? Tindakan ini tidak dapat dibatalkan.`"
        confirm-text="Hapus"
        cancel-text="Batal"
        type="danger"
        :processing="processing"
        @close="emit('close')"
        @confirm="emit('confirm')"
    />
</template>
