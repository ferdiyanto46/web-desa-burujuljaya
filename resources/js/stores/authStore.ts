import { defineStore } from 'pinia';
import { ref } from 'vue';
import type { User } from '@/types';

export const useAuthStore = defineStore('auth', () => {
    const currentUser = ref<User | null>(null);
    const permissions = ref<string[]>([]);
    
    const setUser = (user: User | null) => {
        currentUser.value = user;
    };
    
    const setPermissions = (perms: string[]) => {
        permissions.value = perms;
    };
    
    const hasPermission = (permission: string) => {
        return permissions.value.includes(permission);
    };

    return {
        currentUser,
        permissions,
        setUser,
        setPermissions,
        hasPermission,
    };
});
