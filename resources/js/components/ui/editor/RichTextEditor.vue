<script setup lang="ts">
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';
import TextAlign from '@tiptap/extension-text-align';
import Link from '@tiptap/extension-link';
import Image from '@tiptap/extension-image';
import Toolbar from './Toolbar.vue';
import FormError from '../../forms/FormError.vue';
import { computed, watch, onBeforeUnmount } from 'vue';

interface Props {
    modelValue: string;
    label?: string;
    error?: string;
    placeholder?: string;
    required?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: '',
    required: false,
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const editor = useEditor({
    extensions: [
        StarterKit,
        Underline,
        TextAlign.configure({
            types: ['heading', 'paragraph'],
        }),
        Link.configure({
            openOnClick: false,
        }),
        Image,
    ],
    content: props.modelValue,
    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getHTML());
    },
    editorProps: {
        attributes: {
            class: 'prose prose-sm sm:prose-base focus:outline-none dark:prose-invert max-w-none p-4 min-h-[300px]',
        },
    },
});

watch(() => props.modelValue, (value) => {
    const isSame = editor.value?.getHTML() === value;
    if (!isSame && editor.value) {
        editor.value.commands.setContent(value, { emitUpdate: false });
    }
});

onBeforeUnmount(() => {
    editor.value?.destroy();
});

const hasError = computed(() => !!props.error);
</script>

<template>
    <div class="w-full mb-4">
        <label v-if="label" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ label }} <span v-if="required" class="text-red-500">*</span>
        </label>
        
        <div 
            class="rounded-md border bg-white dark:bg-gray-900 overflow-hidden transition-colors"
            :class="[
                hasError 
                    ? 'border-red-300 ring-1 ring-red-300 dark:border-red-700 dark:ring-red-700' 
                    : 'border-gray-300 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 dark:border-gray-600 dark:focus-within:border-blue-400'
            ]"
        >
            <Toolbar :editor="editor || null" />
            <div class="bg-white dark:bg-gray-900 max-h-[500px] overflow-y-auto cursor-text" @click="editor?.commands.focus()">
                <editor-content :editor="editor" />
            </div>
        </div>
        
        <FormError :message="error" />
    </div>
</template>

<style>
/* Prose overrides for TipTap */
.ProseMirror p.is-editor-empty:first-child::before {
    content: attr(data-placeholder);
    float: left;
    color: #adb5bd;
    pointer-events: none;
    height: 0;
}
</style>
