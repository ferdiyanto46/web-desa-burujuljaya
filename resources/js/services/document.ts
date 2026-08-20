import type { VisitOptions } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';

export const DocumentService = {
    adminIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('admin.documents.index'), params, options);
    },
    
    create() {
        router.get(route('admin.documents.create'));
    },
    
    store<T extends Record<string, any>>(data: T, options?: VisitOptions) {
        router.post(route('admin.documents.store'), data, options);
    },
    
    edit(id: string) {
        router.get(route('admin.documents.edit', id));
    },
    
    update<T extends Record<string, any>>(id: string, data: T, options?: VisitOptions) {
        router.post(route('admin.documents.update', id), {
            _method: 'PUT',
            ...data
        }, options);
    },
    
    destroy(id: string, options?: VisitOptions) {
        router.delete(route('admin.documents.destroy', id), options);
    },
    
    restore(id: string, options?: VisitOptions) {
        router.post(route('admin.documents.restore', id), {}, options);
    },
    
    forceDelete(id: string, options?: VisitOptions) {
        router.delete(route('admin.documents.force-delete', id), options);
    },
    
    publicIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('documents.index'), params, options);
    },
    
    downloadUrl(ulid: string) {
        return route('documents.download', ulid);
    }
};
