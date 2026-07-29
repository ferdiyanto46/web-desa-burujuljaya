<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    modelValue: boolean;
    label?: string;
    description?: string;
    disabled?: boolean;
    error?: string;
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: false,
    disabled: false,
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

const id = Math.random().toString(36).substring(2, 9);
const hasError = computed(() => !!props.error);

const toggle = () => {
    if (props.disabled) {
return;
}

    emit('update:modelValue', !props.modelValue);
};
</script>

<template>
    <div class="flex items-center justify-between">
        <div class="flex flex-col">
            <label v-if="label" :for="id" class="text-sm font-medium text-gray-900 dark:text-gray-100" :class="{ 'opacity-50 cursor-not-allowed': disabled, 'cursor-pointer': !disabled }" @click="toggle">
                {{ label }}
            </label>
            <p v-if="description" class="text-sm text-gray-500 dark:text-gray-400 mt-1" :class="{ 'opacity-50': disabled }">
                {{ description }}
            </p>
            <p v-if="error" class="text-sm font-medium text-red-600 dark:text-red-500 mt-1">
                {{ error }}
            </p>
        </div>
        <button
            :id="id"
            type="button"
            role="switch"
            :aria-checked="modelValue"
            :disabled="disabled"
            @click="toggle"
            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:focus:ring-offset-gray-900"
            :class="[
                modelValue ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700',
                hasError ? 'ring-2 ring-red-500 ring-offset-2' : ''
            ]"
        >
            <span class="sr-only">Toggle {{ label }}</span>
            <span
                aria-hidden="true"
                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                :class="[modelValue ? 'translate-x-5' : 'translate-x-0']"
            />
        </button>
    </div>
</template>
