import type { VisitOptions } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';

export const VillageServiceService = {
    // Admin routes
    adminIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('admin.village-services.index'), params, options);
    },
    
    create() {
        router.get(route('admin.village-services.create'));
    },
    
    store<T extends Record<string, any>>(data: T, options?: VisitOptions) {
        router.post(route('admin.village-services.store'), data, options);
    },
    
    edit(id: string) {
        router.get(route('admin.village-services.edit', id));
    },
    
    update<T extends Record<string, any>>(id: string, data: T, options?: VisitOptions) {
        // Use post with _method=PUT to support file uploads
        router.post(route('admin.village-services.update', id), {
            _method: 'PUT',
            ...data
        }, options);
    },
    
    destroy(id: string, options?: VisitOptions) {
        router.delete(route('admin.village-services.destroy', id), options);
    },
    
    restore(id: string, options?: VisitOptions) {
        router.post(route('admin.village-services.restore', id), {}, options);
    },
    
    forceDelete(id: string, options?: VisitOptions) {
        router.delete(route('admin.village-services.force-delete', id), options);
    },
    
    // Public routes
    publicIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('village-services.index'), params, options);
    },
    
    publicShow(slug: string) {
        router.get(route('village-services.show', slug));
    }
};
