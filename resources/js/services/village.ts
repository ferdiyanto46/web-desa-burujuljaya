import type { VisitOptions } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';

export const VillageService = {
    editProfile() {
        router.get(route('admin.village-profile.edit'));
    },
    
    updateProfile<T extends Record<string, any>>(data: T, options?: VisitOptions) {
        router.post(route('admin.village-profile.update'), {
            _method: 'PUT',
            ...data
        }, options);
    },
    
    // Apparatus
    adminIndexApparatus(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('admin.apparatuses.index'), params, options);
    },
    
    createApparatus() {
        router.get(route('admin.apparatuses.create'));
    },
    
    storeApparatus<T extends Record<string, any>>(data: T, options?: VisitOptions) {
        router.post(route('admin.apparatuses.store'), data, options);
    },
    
    editApparatus(id: string) {
        router.get(route('admin.apparatuses.edit', id));
    },
    
    updateApparatus<T extends Record<string, any>>(id: string, data: T, options?: VisitOptions) {
        router.post(route('admin.apparatuses.update', id), {
            _method: 'PUT',
            ...data
        }, options);
    },
    
    destroyApparatus(id: string, options?: VisitOptions) {
        router.delete(route('admin.apparatuses.destroy', id), options);
    }
};
