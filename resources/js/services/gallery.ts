import type { VisitOptions } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';

export const GalleryService = {
    adminIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('admin.galleries.index'), params, options);
    },
    
    create() {
        router.get(route('admin.galleries.create'));
    },
    
    store<T extends Record<string, any>>(data: T, options?: VisitOptions) {
        router.post(route('admin.galleries.store'), data, options);
    },
    
    edit(id: string) {
        router.get(route('admin.galleries.edit', id));
    },
    
    update<T extends Record<string, any>>(id: string, data: T, options?: VisitOptions) {
        router.put(route('admin.galleries.update', id), data, options);
    },
    
    destroy(id: string, options?: VisitOptions) {
        router.delete(route('admin.galleries.destroy', id), options);
    },
    
    // Photos
    storePhoto<T extends Record<string, any>>(albumId: string, data: T, options?: VisitOptions) {
        router.post(route('admin.galleries.photos.store', albumId), data, options);
    },
    
    destroyPhoto(albumId: string, photoId: string, options?: VisitOptions) {
        router.delete(route('admin.galleries.photos.destroy', [albumId, photoId]), options);
    },

    publicIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('gallery.index'), params, options);
    },
    
    publicShow(slug: string) {
        router.get(route('gallery.show', slug));
    }
};
