<script setup lang="ts">
import type { NavigationGroup } from '@/constants/navigation';
import NavigationLabel from './NavigationLabel.vue';
import SidebarCollapse from './SidebarCollapse.vue';
import SidebarItem from './SidebarItem.vue';

interface Props {
    group: NavigationGroup;
    collapsed?: boolean;
}

withDefaults(defineProps<Props>(), {
    collapsed: false,
});
</script>

<template>
    <div class="py-2">
        <NavigationLabel :label="group.label" :collapsed="collapsed" />
        <ul class="space-y-1 px-2 mt-1">
            <li v-for="item in group.items" :key="item.name">
                <template v-if="item.children && item.children.length > 0">
                    <SidebarCollapse :item="item" :collapsed="collapsed" />
                </template>
                <template v-else>
                    <SidebarItem :item="item" :collapsed="collapsed" />
                </template>
            </li>
        </ul>
    </div>
</template>
