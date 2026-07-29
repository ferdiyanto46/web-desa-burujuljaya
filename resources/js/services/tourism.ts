import type { VisitOptions } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';

export const TourismService = {
    adminIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('admin.tourisms.index'), params, options);
    },
    
    create() {
        router.get(route('admin.tourisms.create'));
    },
    
    store(data: any, options?: VisitOptions) {
        router.post(route('admin.tourisms.store'), data, options);
    },
    
    edit(id: string) {
        router.get(route('admin.tourisms.edit', id));
    },
    
    update(id: string, data: any, options?: VisitOptions) {
        router.post(route('admin.tourisms.update', id), {
            _method: 'PUT',
            ...data
        }, options);
    },
    
    destroy(id: string, options?: VisitOptions) {
        router.delete(route('admin.tourisms.destroy', id), options);
    },
    
    publicIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('tourism.index'), params, options);
    },
    
    publicShow(slug: string) {
        router.get(route('tourism.show', slug));
    }
};
