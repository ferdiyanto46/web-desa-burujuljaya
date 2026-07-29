<script setup lang="ts">
import TopbarNotification from './TopbarNotification.vue';
import TopbarProfile from './TopbarProfile.vue';
import TopbarSearch from './TopbarSearch.vue';
</script>

<template>
    <div class="flex flex-1 items-center justify-between gap-x-4 lg:gap-x-6">
        <div class="flex flex-1">
            <TopbarSearch />
        </div>
        <div class="flex items-center gap-x-4 lg:gap-x-6">
            <TopbarNotification />
            
            <!-- Separator -->
            <div class="hidden sm:block h-6 w-px bg-gray-200 dark:bg-gray-700" aria-hidden="true"></div>
            
            <TopbarProfile />
        </div>
    </div>
</template>
