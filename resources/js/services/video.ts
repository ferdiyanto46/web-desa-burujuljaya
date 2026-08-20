import type { VisitOptions } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';

export const VideoService = {
    adminIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('admin.videos.index'), params, options);
    },
    
    create() {
        router.get(route('admin.videos.create'));
    },
    
    store<T extends Record<string, any>>(data: T, options?: VisitOptions) {
        router.post(route('admin.videos.store'), data, options);
    },
    
    edit(id: string) {
        router.get(route('admin.videos.edit', id));
    },
    
    update<T extends Record<string, any>>(id: string, data: T, options?: VisitOptions) {
        router.put(route('admin.videos.update', id), data, options);
    },
    
    destroy(id: string, options?: VisitOptions) {
        router.delete(route('admin.videos.destroy', id), options);
    },
    
    publicIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('videos.index'), params, options);
    }
};
