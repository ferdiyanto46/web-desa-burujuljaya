<script setup lang="ts">
import ContentWrapper from '../components/dashboard/ContentWrapper.vue';
import DashboardContainer from '../components/dashboard/DashboardContainer.vue';
</script>

<template>
    <DashboardContainer>
        <template #header v-if="$slots.header">
            <slot name="header" />
        </template>
        <slot />
    </DashboardContainer>
</template>
