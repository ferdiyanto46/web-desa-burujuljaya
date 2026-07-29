<script setup lang="ts">
import { Icons } from '@/constants/icons';

defineProps<{
    systemStatus: {
        php_version: string;
        laravel_version: string;
        environment: string;
        database: string;
        storage: string;
    }
}>();
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col h-full">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <component :is="Icons.SettingsIcon" class="w-5 h-5 text-gray-500" />
                Status Sistem
            </h3>
        </div>
        
        <div class="p-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Environment</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white capitalize flex items-center gap-2">
                        <span class="relative flex h-2 w-2">
                          <span :class="['animate-ping absolute inline-flex h-full w-full rounded-full opacity-75', systemStatus.environment === 'production' ? 'bg-emerald-400' : 'bg-amber-400']"></span>
                          <span :class="['relative inline-flex rounded-full h-2 w-2', systemStatus.environment === 'production' ? 'bg-emerald-500' : 'bg-amber-500']"></span>
                        </span>
                        {{ systemStatus.environment }}
                    </dd>
                </div>
                
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">PHP Version</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ systemStatus.php_version }}</dd>
                </div>
                
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Laravel Version</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ systemStatus.laravel_version }}</dd>
                </div>
                
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Database</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white capitalize">{{ systemStatus.database }}</dd>
                </div>
                
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Storage Driver</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white capitalize">{{ systemStatus.storage }}</dd>
                </div>
            </dl>
        </div>
    </div>
</template>
