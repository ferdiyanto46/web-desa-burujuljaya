import type { VisitOptions } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';

export const DocumentService = {
    adminIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('admin.documents.index'), params, options);
    },
    
    create() {
        router.get(route('admin.documents.create'));
    },
    
    store(data: any, options?: VisitOptions) {
        router.post(route('admin.documents.store'), data, options);
    },
    
    edit(id: string) {
        router.get(route('admin.documents.edit', id));
    },
    
    update(id: string, data: any, options?: VisitOptions) {
        router.post(route('admin.documents.update', id), {
            _method: 'PUT',
            ...data
        }, options);
    },
    
    destroy(id: string, options?: VisitOptions) {
        router.delete(route('admin.documents.destroy', id), options);
    },
    
    publicIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('documents.index'), params, options);
    },
    
    downloadUrl(ulid: string) {
        return route('documents.download', ulid);
    }
};
