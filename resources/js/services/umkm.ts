import type { VisitOptions } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';

export const UmkmService = {
    adminIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('admin.umkms.index'), params, options);
    },
    
    create() {
        router.get(route('admin.umkms.create'));
    },
    
    store<T extends Record<string, any>>(data: T, options?: VisitOptions) {
        router.post(route('admin.umkms.store'), data, options);
    },
    
    edit(id: string) {
        router.get(route('admin.umkms.edit', id));
    },
    
    update<T extends Record<string, any>>(id: string, data: T, options?: VisitOptions) {
        router.put(route('admin.umkms.update', id), data, options);
    },
    
    destroy(id: string, options?: VisitOptions) {
        router.delete(route('admin.umkms.destroy', id), options);
    },
    
    publicIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('umkm.index'), params, options);
    },
    
    publicShow(slug: string) {
        router.get(route('umkm.show', slug));
    }
};
