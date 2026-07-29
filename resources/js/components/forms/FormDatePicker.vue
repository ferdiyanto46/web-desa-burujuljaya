<script setup lang="ts">
import { computed } from 'vue';
import FormError from './FormError.vue';

interface Props {
    modelValue: string | null;
    label?: string;
    type?: 'date' | 'datetime-local' | 'time' | 'month';
    error?: string;
    disabled?: boolean;
    required?: boolean;
    min?: string;
    max?: string;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'date',
    disabled: false,
    required: false,
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const id = Math.random().toString(36).substring(2, 9);
const hasError = computed(() => !!props.error);

// Vue expects datetime-local format to be YYYY-MM-DDThh:mm
const proxyValue = computed({
    get() {
        if (!props.modelValue) {
return '';
}

        // If type is datetime-local, ensure the "T" format
        if (props.type === 'datetime-local' && props.modelValue.includes(' ')) {
            return props.modelValue.replace(' ', 'T');
        }

        return props.modelValue;
    },
    set(val) {
        emit('update:modelValue', val);
    }
});
</script>

<template>
    <div class="mb-4 w-full">
        <label v-if="label" :for="id" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ label }} <span v-if="required" class="text-red-500">*</span>
        </label>
        
        <input
            :id="id"
            :type="type"
            v-model="proxyValue"
            :disabled="disabled"
            :required="required"
            :min="min"
            :max="max"
            class="block w-full rounded-md border text-sm transition-colors focus:outline-none focus:ring-2 disabled:cursor-not-allowed disabled:bg-gray-100 dark:bg-gray-800 dark:text-gray-100 dark:disabled:bg-gray-900"
            :class="[
                hasError 
                    ? 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 dark:border-red-700 dark:text-red-400' 
                    : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:focus:border-blue-400 dark:focus:ring-blue-400'
            ]"
        />
        
        <FormError :message="error" />
    </div>
</template>
