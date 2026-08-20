import type { VisitOptions } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';

export const AgendaService = {
    adminIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('admin.agendas.index'), params, options);
    },
    
    create() {
        router.get(route('admin.agendas.create'));
    },
    
    store<T extends Record<string, any>>(data: T, options?: VisitOptions) {
        router.post(route('admin.agendas.store'), data, options);
    },
    
    edit(id: string) {
        router.get(route('admin.agendas.edit', id));
    },
    
    update<T extends Record<string, any>>(id: string, data: T, options?: VisitOptions) {
        router.put(route('admin.agendas.update', id), data, options);
    },
    
    destroy(id: string, options?: VisitOptions) {
        router.delete(route('admin.agendas.destroy', id), options);
    },
    
    publicIndex(params: Record<string, any> = {}, options?: VisitOptions) {
        router.get(route('agendas.index'), params, options);
    },
    
    publicShow(slug: string) {
        router.get(route('agendas.show', slug));
    }
};
