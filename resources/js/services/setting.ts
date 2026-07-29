import type { VisitOptions } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';

export const SettingService = {
    edit() {
        router.get(route('admin.settings.edit'));
    },
    
    update(data: any, options?: VisitOptions) {
        router.put(route('admin.settings.update'), data, options);
    }
};


