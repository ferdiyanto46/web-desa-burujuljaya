<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const currentDate = computed(() => {
    return new Intl.DateTimeFormat('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(new Date());
});

// Calculate greeting based on hour
const greeting = computed(() => {
    const hour = new Date().getHours();

    if (hour < 10) {
return 'Selamat Pagi';
}

    if (hour < 15) {
return 'Selamat Siang';
}

    if (hour < 18) {
return 'Selamat Sore';
}

    return 'Selamat Malam';
});

// Get user from Inertia page props (shared via HandleInertiaRequests)
const page = usePage();
const user = computed(() => page.props.auth?.user);

</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                    {{ greeting }}, {{ user?.name || 'Admin' }}! 👋
                </h2>
                <p class="text-gray-500 dark:text-gray-400">
                    Ini adalah ringkasan operasional SID Burujuljaya hari ini.
                </p>
            </div>
            <div class="text-left sm:text-right">
                <div class="text-sm font-medium text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 px-4 py-2 rounded-lg border border-gray-100 dark:border-gray-800">
                    {{ currentDate }}
                </div>
            </div>
        </div>
    </div>
</template>
