import { router } from '@inertiajs/vue3'

export function storeAnnouncement<T extends Record<string, any>>(
    data: T,
    options = {}
): void {
    router.post(route('admin.announcements.store'), data, {
        preserveScroll: true,
        ...options,
    })
}

export function updateAnnouncement<T extends Record<string, any>>(
    id: string,
    data: T,
    options = {}
): void {
    // We must use POST with _method=PUT to support file uploads in Inertia
    router.post(
        route('admin.announcements.update', id),
        {
            _method: 'put',
            ...data,
        },
        {
            preserveScroll: true,
            ...options,
        }
    )
}

export function deleteAnnouncement(id: string, options = {}): void {
    router.delete(route('admin.announcements.destroy', id), {
        preserveScroll: true,
        ...options,
    })
}
