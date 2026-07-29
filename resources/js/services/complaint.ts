import type { VisitOptions } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';

export const ComplaintService = {
    adminIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('admin.complaints.index'), params, options);
    },
    
    show(id: string) {
        router.get(route('admin.complaints.show', id));
    },
    
    updateStatus(id: string, status: string, options?: VisitOptions) {
        router.put(route('admin.complaints.update', id), { status }, options);
    },
    
    destroy(id: string, options?: VisitOptions) {
        router.delete(route('admin.complaints.destroy', id), options);
    },
    
    // Public routes
    create() {
        router.get(route('complaints.create'));
    },
    
    store(data: any, options?: VisitOptions) {
        router.post(route('complaints.store'), data, options);
    },
    
    track(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('complaints.track'), params, options);
    }
};
