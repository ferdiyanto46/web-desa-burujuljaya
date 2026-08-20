import type { VisitOptions } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';

export const SettingService = {
    edit() {
        router.get(route('admin.settings.edit'));
    },
    
    update<T extends Record<string, any>>(data: T, options?: VisitOptions) {
        router.put(route('admin.settings.update'), data, options);
    }
};


