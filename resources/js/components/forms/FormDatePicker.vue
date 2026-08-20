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
    showNowButton?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'date',
    disabled: false,
    required: false,
    showNowButton: true,
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const id = Math.random().toString(36).substring(2, 9);
const hasError = computed(() => !!props.error);

const formatForInput = (val: string | null | undefined, inputType: string): string => {
    if (!val) return '';
    if (inputType === 'datetime-local') {
        try {
            const date = new Date(val);
            if (!isNaN(date.getTime())) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                return `${year}-${month}-${day}T${hours}:${minutes}`;
            }
        } catch {
            // fallback
        }
        return val.slice(0, 16).replace(' ', 'T');
    }
    if (inputType === 'date') {
        try {
            const date = new Date(val);
            if (!isNaN(date.getTime())) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }
        } catch {
            // fallback
        }
        return val.slice(0, 10);
    }
    return val;
};

// Vue expects datetime-local format to be YYYY-MM-DDThh:mm
const proxyValue = computed({
    get() {
        return formatForInput(props.modelValue, props.type);
    },
    set(val) {
        emit('update:modelValue', val);
    }
});

const setNow = () => {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    
    if (props.type === 'datetime-local') {
        emit('update:modelValue', `${year}-${month}-${day}T${hours}:${minutes}`);
    } else if (props.type === 'date') {
        emit('update:modelValue', `${year}-${month}-${day}`);
    }
};
</script>

<template>
    <div class="mb-4 w-full">
        <div class="flex items-center justify-between mb-1">
            <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ label }} <span v-if="required" class="text-red-500">*</span>
            </label>
            <button 
                v-if="!disabled && showNowButton && (type === 'datetime-local' || type === 'date')" 
                type="button" 
                @click="setNow"
                class="text-xs font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
            >
                Set Sekarang
            </button>
        </div>
        
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
