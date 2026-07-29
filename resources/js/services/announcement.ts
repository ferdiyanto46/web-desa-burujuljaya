import type { VisitOptions } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';

export const AnnouncementService = {
    adminIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('admin.announcements.index'), params, options);
    },
    
    create() {
        router.get(route('admin.announcements.create'));
    },
    
    store(data: any, options?: VisitOptions) {
        router.post(route('admin.announcements.store'), data, options);
    },
    
    edit(id: string) {
        router.get(route('admin.announcements.edit', id));
    },
    
    update(id: string, data: any, options?: VisitOptions) {
        router.put(route('admin.announcements.update', id), data, options);
    },
    
    destroy(id: string, options?: VisitOptions) {
        router.delete(route('admin.announcements.destroy', id), options);
    },
    
    publicIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('announcements.index'), params, options);
    },
    
    publicShow(slug: string) {
        router.get(route('announcements.show', slug));
    }
};
