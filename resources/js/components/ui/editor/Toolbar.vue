<script setup lang="ts">
import { Editor } from '@tiptap/vue-3';
import { 
    BoldIcon, 
    ItalicIcon, 
    UnderlineIcon, 
    StrikethroughIcon,
    Heading1Icon, 
    Heading2Icon, 
    Heading3Icon,
    ListIcon,
    ListOrderedIcon,
    QuoteIcon,
    UndoIcon,
    RedoIcon,
    AlignLeftIcon,
    AlignCenterIcon,
    AlignRightIcon,
    AlignJustifyIcon
} from 'lucide-vue-next';

interface Props {
    editor: Editor | null;
}

defineProps<Props>();
</script>

<template>
    <div v-if="editor" class="flex flex-wrap items-center gap-1 border-b border-gray-200 bg-gray-50 p-2 dark:border-gray-700 dark:bg-gray-800 rounded-t-md">
        <!-- Text Formatting -->
        <button
            type="button"
            @click="editor.chain().focus().toggleBold().run()"
            :class="[ 'p-1.5 rounded text-gray-600 hover:bg-gray-200 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100', { 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-gray-100': editor.isActive('bold') } ]"
            title="Bold"
        >
            <BoldIcon class="h-4 w-4" />
        </button>
        <button
            type="button"
            @click="editor.chain().focus().toggleItalic().run()"
            :class="[ 'p-1.5 rounded text-gray-600 hover:bg-gray-200 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100', { 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-gray-100': editor.isActive('italic') } ]"
            title="Italic"
        >
            <ItalicIcon class="h-4 w-4" />
        </button>
        <button
            type="button"
            @click="editor.chain().focus().toggleUnderline().run()"
            :class="[ 'p-1.5 rounded text-gray-600 hover:bg-gray-200 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100', { 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-gray-100': editor.isActive('underline') } ]"
            title="Underline"
        >
            <UnderlineIcon class="h-4 w-4" />
        </button>
        <button
            type="button"
            @click="editor.chain().focus().toggleStrike().run()"
            :class="[ 'p-1.5 rounded text-gray-600 hover:bg-gray-200 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100', { 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-gray-100': editor.isActive('strike') } ]"
            title="Strikethrough"
        >
            <StrikethroughIcon class="h-4 w-4" />
        </button>

        <div class="mx-1 h-5 w-px bg-gray-300 dark:bg-gray-600"></div>

        <!-- Headings -->
        <button
            type="button"
            @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
            :class="[ 'p-1.5 rounded text-gray-600 hover:bg-gray-200 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100', { 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-gray-100': editor.isActive('heading', { level: 1 }) } ]"
            title="Heading 1"
        >
            <Heading1Icon class="h-4 w-4" />
        </button>
        <button
            type="button"
            @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
            :class="[ 'p-1.5 rounded text-gray-600 hover:bg-gray-200 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100', { 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-gray-100': editor.isActive('heading', { level: 2 }) } ]"
            title="Heading 2"
        >
            <Heading2Icon class="h-4 w-4" />
        </button>
        <button
            type="button"
            @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
            :class="[ 'p-1.5 rounded text-gray-600 hover:bg-gray-200 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100', { 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-gray-100': editor.isActive('heading', { level: 3 }) } ]"
            title="Heading 3"
        >
            <Heading3Icon class="h-4 w-4" />
        </button>

        <div class="mx-1 h-5 w-px bg-gray-300 dark:bg-gray-600"></div>

        <!-- Lists -->
        <button
            type="button"
            @click="editor.chain().focus().toggleBulletList().run()"
            :class="[ 'p-1.5 rounded text-gray-600 hover:bg-gray-200 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100', { 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-gray-100': editor.isActive('bulletList') } ]"
            title="Bullet List"
        >
            <ListIcon class="h-4 w-4" />
        </button>
        <button
            type="button"
            @click="editor.chain().focus().toggleOrderedList().run()"
            :class="[ 'p-1.5 rounded text-gray-600 hover:bg-gray-200 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100', { 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-gray-100': editor.isActive('orderedList') } ]"
            title="Ordered List"
        >
            <ListOrderedIcon class="h-4 w-4" />
        </button>
        <button
            type="button"
            @click="editor.chain().focus().toggleBlockquote().run()"
            :class="[ 'p-1.5 rounded text-gray-600 hover:bg-gray-200 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100', { 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-gray-100': editor.isActive('blockquote') } ]"
            title="Blockquote"
        >
            <QuoteIcon class="h-4 w-4" />
        </button>

        <div class="mx-1 h-5 w-px bg-gray-300 dark:bg-gray-600"></div>

        <!-- Alignment -->
        <button
            type="button"
            @click="editor.chain().focus().setTextAlign('left').run()"
            :class="[ 'p-1.5 rounded text-gray-600 hover:bg-gray-200 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100', { 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-gray-100': editor.isActive({ textAlign: 'left' }) } ]"
            title="Align Left"
        >
            <AlignLeftIcon class="h-4 w-4" />
        </button>
        <button
            type="button"
            @click="editor.chain().focus().setTextAlign('center').run()"
            :class="[ 'p-1.5 rounded text-gray-600 hover:bg-gray-200 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100', { 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-gray-100': editor.isActive({ textAlign: 'center' }) } ]"
            title="Align Center"
        >
            <AlignCenterIcon class="h-4 w-4" />
        </button>
        <button
            type="button"
            @click="editor.chain().focus().setTextAlign('right').run()"
            :class="[ 'p-1.5 rounded text-gray-600 hover:bg-gray-200 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100', { 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-gray-100': editor.isActive({ textAlign: 'right' }) } ]"
            title="Align Right"
        >
            <AlignRightIcon class="h-4 w-4" />
        </button>
        <button
            type="button"
            @click="editor.chain().focus().setTextAlign('justify').run()"
            :class="[ 'p-1.5 rounded text-gray-600 hover:bg-gray-200 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100', { 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-gray-100': editor.isActive({ textAlign: 'justify' }) } ]"
            title="Justify"
        >
            <AlignJustifyIcon class="h-4 w-4" />
        </button>

        <div class="mx-1 h-5 w-px bg-gray-300 dark:bg-gray-600"></div>

        <!-- History -->
        <button
            type="button"
            @click="editor.chain().focus().undo().run()"
            :disabled="!editor.can().undo()"
            class="p-1.5 rounded text-gray-600 hover:bg-gray-200 hover:text-gray-900 disabled:opacity-50 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100"
            title="Undo"
        >
            <UndoIcon class="h-4 w-4" />
        </button>
        <button
            type="button"
            @click="editor.chain().focus().redo().run()"
            :disabled="!editor.can().redo()"
            class="p-1.5 rounded text-gray-600 hover:bg-gray-200 hover:text-gray-900 disabled:opacity-50 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100"
            title="Redo"
        >
            <RedoIcon class="h-4 w-4" />
        </button>
    </div>
</template>
