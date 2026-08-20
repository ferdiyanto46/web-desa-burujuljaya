<script setup lang="ts">
import { CollapsibleRoot, CollapsibleTrigger, CollapsibleContent } from 'reka-ui';
import { computed, ref, watch } from 'vue';
import { Icons  } from '@/constants/icons';
import type {IconName} from '@/constants/icons';
import type { NavigationItem } from '@/constants/navigation';
import { route } from '@/lib/route';
import SidebarItem from './SidebarItem.vue';

interface Props {
    item: NavigationItem;
    collapsed?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    collapsed: false,
});

// Determine if any child is active to keep collapsible open
const isActive = computed(() => {
    const currentRoute = (route as any)().current() as string | undefined;

    if (!currentRoute) {
return false;
}
    
    return props.item.children?.some(child => {
        if (child.activeRoute) {
            const pattern = child.activeRoute.replace('*', '.*');
            const regex = new RegExp(`^${pattern}$`);

            return regex.test(currentRoute);
        }

        return currentRoute === child.route;
    }) ?? false;
});

const isOpen = ref(isActive.value);

// Optional: sync state if route changes externally
watch(isActive, (newVal) => {
    if (newVal && !isOpen.value) {
        isOpen.value = true;
    }
});
</script>

<template>
    <CollapsibleRoot v-model:open="isOpen" class="space-y-1">
        <CollapsibleTrigger asChild>
            <button 
                class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium transition-colors group"
                :class="[
                    isActive || isOpen
                        ? 'text-gray-900 bg-gray-50 dark:bg-gray-800/50 dark:text-white'
                        : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white',
                    collapsed ? 'justify-center' : ''
                ]"
                :title="collapsed ? item.label : undefined"
            >
                <div class="flex items-center gap-3">
                    <component 
                        :is="Icons[item.icon as IconName]" 
                        class="w-5 h-5 shrink-0 transition-colors"
                        :class="[
                            isActive || isOpen
                                ? 'text-gray-900 dark:text-white' 
                                : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-300'
                        ]"
                        aria-hidden="true"
                    />
                    <span v-if="!collapsed" class="truncate transition-opacity duration-300">
                        {{ item.label }}
                    </span>
                </div>
                
                <component 
                    v-if="!collapsed"
                    :is="Icons.ChevronDownIcon" 
                    class="w-4 h-4 shrink-0 transition-transform duration-200"
                    :class="{ '-rotate-90': !isOpen }"
                    aria-hidden="true"
                />
            </button>
        </CollapsibleTrigger>
        
        <CollapsibleContent class="overflow-hidden transition-all data-[state=closed]:animate-collapsible-up data-[state=open]:animate-collapsible-down">
            <div 
                v-if="!collapsed" 
                class="pl-10 pr-2 py-1 space-y-1"
            >
                <SidebarItem 
                    v-for="child in item.children" 
                    :key="child.name" 
                    :item="child" 
                />
            </div>
        </CollapsibleContent>
    </CollapsibleRoot>
</template>
