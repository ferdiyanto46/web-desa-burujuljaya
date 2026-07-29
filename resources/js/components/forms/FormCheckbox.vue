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

const proxyChecked = computed({
    get() {
        return props.modelValue;
    },
    set(val) {
        emit('update:modelValue', val);
    },
});
</script>

<template>
    <div class="relative flex items-start">
        <div class="flex h-6 items-center">
            <input
                :id="id"
                v-model="proxyChecked"
                type="checkbox"
                :disabled="disabled"
                class="h-4 w-4 rounded transition-colors focus:ring-2 disabled:cursor-not-allowed disabled:bg-gray-100 dark:bg-gray-800 dark:disabled:bg-gray-900"
                :class="[
                    hasError 
                        ? 'border-red-300 text-red-600 focus:ring-red-500 dark:border-red-700 dark:text-red-500' 
                        : 'border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:checked:bg-blue-500 dark:focus:ring-blue-400'
                ]"
            />
        </div>
        <div class="ml-3 text-sm leading-6">
            <label v-if="label" :for="id" class="font-medium text-gray-900 dark:text-gray-100" :class="{ 'opacity-50': disabled }">
                {{ label }}
            </label>
            <p v-if="description" class="text-gray-500 dark:text-gray-400" :class="{ 'opacity-50': disabled }">
                {{ description }}
            </p>
            <p v-if="error" class="mt-1 text-sm font-medium text-red-600 dark:text-red-500">
                {{ error }}
            </p>
        </div>
    </div>
</template>
