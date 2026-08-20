import { customRef } from 'vue';

/**
 * Creates a debounced ref that only updates after the specified delay
 */
export function useDebounce<T>(value: T, delay = 300) {
    let timeout: ReturnType<typeof setTimeout>;
    
    return customRef((track, trigger) => {
        return {
            get() {
                track();

                return value;
            },
            set(newValue: T) {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    value = newValue;
                    trigger();
                }, delay);
            },
        };
    });
}

/**
 * A simple debounce function for callbacks
 */
export function debounce<T extends (...args: unknown[]) => unknown>(fn: T, delay = 300) {
    let timeoutId: ReturnType<typeof setTimeout>;
    
    return function (...args: Parameters<T>) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
}
