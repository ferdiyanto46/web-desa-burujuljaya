<script setup lang="ts">
import { Icons } from '@/constants/icons';

const props = defineProps<{
    title: string;
    url?: string;
}>();

const getShareUrl = () => props.url || (typeof window !== 'undefined' ? window.location.href : '');

const shareToFacebook = () => {
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(getShareUrl())}`, '_blank', 'width=600,height=400');
};

const shareToTwitter = () => {
    window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(getShareUrl())}&text=${encodeURIComponent(props.title)}`, '_blank', 'width=600,height=400');
};

const shareToWhatsApp = () => {
    window.open(`https://api.whatsapp.com/send?text=${encodeURIComponent(props.title + ' ' + getShareUrl())}`, '_blank');
};

const copyLink = () => {
    if (typeof navigator !== 'undefined' && navigator.clipboard) {
        navigator.clipboard.writeText(getShareUrl());
        alert('Tautan disalin ke papan klip!');
    }
};
</script>

<template>
    <div class="flex items-center gap-3">
        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Bagikan:</span>
        <button @click="shareToFacebook" class="p-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-full transition-colors" aria-label="Share to Facebook">
            <component :is="Icons.UsersIcon" class="w-4 h-4" /> <!-- Placeholder for Facebook Icon -->
        </button>
        <button @click="shareToTwitter" class="p-2 bg-sky-50 dark:bg-sky-900/20 text-sky-600 dark:text-sky-400 hover:bg-sky-100 dark:hover:bg-sky-900/40 rounded-full transition-colors" aria-label="Share to Twitter">
            <component :is="Icons.MessageSquareWarningIcon" class="w-4 h-4" /> <!-- Placeholder for Twitter/X Icon -->
        </button>
        <button @click="shareToWhatsApp" class="p-2 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/40 rounded-full transition-colors" aria-label="Share to WhatsApp">
            <component :is="Icons.PhoneIcon" class="w-4 h-4" /> <!-- Placeholder for WhatsApp Icon -->
        </button>
        <button @click="copyLink" class="p-2 bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors" aria-label="Copy Link">
            <component :is="Icons.FileIcon" class="w-4 h-4" /> <!-- Placeholder for Copy Icon -->
        </button>
    </div>
</template>
