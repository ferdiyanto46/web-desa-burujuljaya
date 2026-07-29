import type { VisitOptions } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';

export const UserService = {
    adminIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('admin.users.index'), params, options);
    },
    
    create() {
        router.get(route('admin.users.create'));
    },
    
    store(data: any, options?: VisitOptions) {
        router.post(route('admin.users.store'), data, options);
    },
    
    edit(id: string) {
        router.get(route('admin.users.edit', id));
    },
    
    update(id: string, data: any, options?: VisitOptions) {
        router.put(route('admin.users.update', id), data, options);
    },
    
    destroy(id: string, options?: VisitOptions) {
        router.delete(route('admin.users.destroy', id), options);
    },
};
