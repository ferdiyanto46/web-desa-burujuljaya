<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    modelValue: string | number | null;
    label?: string;
    type?: string;
    placeholder?: string;
    error?: string;
    disabled?: boolean;
    required?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'text',
    disabled: false,
    required: false,
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const id = Math.random().toString(36).substring(2, 9);

const hasError = computed(() => !!props.error);
</script>

<template>
    <div class="mb-4 w-full">
        <label v-if="label" :for="id" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ label }} <span v-if="required" class="text-red-500">*</span>
        </label>
        
        <input
            :id="id"
            :type="type"
            :value="modelValue"
            :placeholder="placeholder"
            :disabled="disabled"
            :required="required"
            @input="emit('update:modelValue', ($event.target as HTMLInputElement).value)"
            class="block w-full rounded-md border text-sm transition-colors focus:outline-none focus:ring-2 disabled:cursor-not-allowed disabled:bg-gray-100 dark:bg-gray-800 dark:text-gray-100 dark:disabled:bg-gray-900"
            :class="[
                hasError 
                    ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 dark:border-red-700 dark:text-red-400' 
                    : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:focus:border-blue-400 dark:focus:ring-blue-400'
            ]"
        />
        
        <p v-if="error" class="mt-1 text-sm text-red-600 dark:text-red-400">
            {{ error }}
        </p>
    </div>
</template>
