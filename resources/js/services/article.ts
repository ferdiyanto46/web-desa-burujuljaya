import type { VisitOptions } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';

export const ArticleService = {
    // Admin routes
    adminIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('admin.articles.index'), params, options);
    },
    
    create() {
        router.get(route('admin.articles.create'));
    },
    
    store(data: any, options?: VisitOptions) {
        router.post(route('admin.articles.store'), data, options);
    },
    
    edit(id: string) {
        router.get(route('admin.articles.edit', id));
    },
    
    update(id: string, data: any, options?: VisitOptions) {
        // Use post with _method=PUT to support file uploads
        router.post(route('admin.articles.update', id), {
            _method: 'PUT',
            ...data
        }, options);
    },
    
    destroy(id: string, options?: VisitOptions) {
        router.delete(route('admin.articles.destroy', id), options);
    },
    
    // Public routes
    publicIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('articles.index'), params, options);
    },
    
    publicShow(slug: string) {
        router.get(route('articles.show', slug));
    }
};
