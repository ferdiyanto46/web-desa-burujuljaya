import { usePage } from '@inertiajs/vue3';
import { useAuthStore } from '@/stores/authStore';

export function usePermission() {
    const authStore = useAuthStore();
    const page = usePage();

    // Optionally sync from Inertia shared props if backend passes them
    const syncFromProps = () => {
        const auth = page.props.auth as any;

        if (auth?.user) {
            authStore.setUser(auth.user);
            authStore.setPermissions(auth.permissions || []);
        }
    };


    const can = (permission: string) => authStore.hasPermission(permission);
    
    const canAny = (permissions: string[]) => {
        return permissions.some(p => authStore.hasPermission(p));
    };

    const canAll = (permissions: string[]) => {
        return permissions.every(p => authStore.hasPermission(p));
    };

    const isRole = (role: string) => {
        return ((authStore.currentUser as any)?.roles as string[] || []).includes(role) ?? false;
    };

    return {
        can,
        canAny,
        canAll,
        isRole,
        syncFromProps,
    };
}
