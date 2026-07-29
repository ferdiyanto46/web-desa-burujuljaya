<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Icons  } from '@/constants/icons';
import type {IconName} from '@/constants/icons';
import { route } from '@/lib/route';
import { useLayoutStore } from '@/stores/layoutStore';


interface Props {
    item: {
        name: string;
        label: string;
        icon: string;
        route: string;
        activeRoute?: string;
    };
    collapsed?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    collapsed: false,
});

const layoutStore = useLayoutStore();

// Since we have global route() function available:
const isActive = computed(() => {
    const currentRoute = (route as any)().current() as string | undefined;

    if (!currentRoute) {
return false;
}
    
    if (props.item.activeRoute) {
        // Handle wildcard matching
        const pattern = props.item.activeRoute.replace('*', '.*');
        const regex = new RegExp('^' + pattern);

        return regex.test(currentRoute);
    }

    return currentRoute === props.item.route;
});

const href = computed(() => {
    try {
        return route(props.item.route);
    } catch {
        return '#';
    }
});
</script>

<template>
    <Link 
        :href="href"
        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors group"
        :class="[
            isActive 
                ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' 
                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white',
            collapsed ? 'justify-center' : ''
        ]"
        :title="collapsed ? item.label : undefined"
        @click="layoutStore.isMobileDrawerOpen ? layoutStore.closeMobileDrawer() : null"
    >
        <component 
            :is="Icons[item.icon as IconName]" 
            class="w-5 h-5 shrink-0 transition-colors"
            :class="[
                isActive 
                    ? 'text-blue-700 dark:text-blue-400' 
                    : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-300'
            ]"
            aria-hidden="true"
        />
        <span 
            v-if="!collapsed" 
            class="truncate transition-opacity duration-300"
        >
            {{ item.label }}
        </span>
    </Link>
</template>
