import type { VisitOptions } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';

export const ProductService = {
    adminIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('admin.products.index'), params, options);
    },
    
    create() {
        router.get(route('admin.products.create'));
    },
    
    store(data: any, options?: VisitOptions) {
        router.post(route('admin.products.store'), data, options);
    },
    
    edit(id: string) {
        router.get(route('admin.products.edit', id));
    },
    
    update(id: string, data: any, options?: VisitOptions) {
        router.post(route('admin.products.update', id), {
            _method: 'PUT',
            ...data
        }, options);
    },
    
    destroy(id: string, options?: VisitOptions) {
        router.delete(route('admin.products.destroy', id), options);
    },
    
    publicIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('products.index'), params, options);
    },
    
    publicShow(slug: string) {
        router.get(route('products.show', slug));
    }
};
