<script setup lang="ts">
import ContentWrapper from '../components/dashboard/ContentWrapper.vue';
import DashboardContainer from '../components/dashboard/DashboardContainer.vue';
</script>

<template>
    <DashboardContainer>
        <ContentWrapper>
            <slot />
        </ContentWrapper>
    </DashboardContainer>
</template>
