import { defineStore } from 'pinia';
import { ref } from 'vue';

export type Theme = 'light' | 'dark' | 'system';
export type SidebarMode = 'expanded' | 'collapsed';

export function initializeTheme() {
    if (typeof window === 'undefined' || typeof document === 'undefined') {
return;
}

    const theme = localStorage.getItem('theme') || 'system';

    if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

export const useLayoutStore = defineStore('layout', () => {
    // Sidebar & Drawer State
    const desktopSidebarMode = ref<SidebarMode>('expanded');
    const isMobileDrawerOpen = ref(false);

    // Theme State
    const theme = ref<Theme>('system');
    const isDark = ref(false);
    const isDarkMode = isDark; // Alias for backward compatibility

    // Theme Logic
    const updateTheme = () => {
        if (typeof window === 'undefined' || typeof document === 'undefined') {
return;
}

        if (theme.value === 'dark' || (theme.value === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            isDark.value = true;
        } else {
            document.documentElement.classList.remove('dark');
            isDark.value = false;
        }
    };

    const setTheme = (newTheme: Theme) => {
        theme.value = newTheme;
        updateTheme();

        if (typeof localStorage !== 'undefined') {
            localStorage.setItem('theme', newTheme);
        }

        if (typeof document !== 'undefined') {
            const maxAge = 365 * 24 * 60 * 60;
            document.cookie = `appearance=${newTheme};path=/;max-age=${maxAge};SameSite=Lax`;
        }
    };

    const toggleDarkMode = () => {
        setTheme(isDark.value ? 'light' : 'dark');
    };

    const initTheme = () => {
        if (typeof window === 'undefined' || typeof localStorage === 'undefined') {
return;
}

        const storedTheme = localStorage.getItem('theme') as Theme | null;

        if (storedTheme) {
            theme.value = storedTheme;
        }

        updateTheme();

        // Listen for system changes if set to system
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (theme.value === 'system') {
                updateTheme();
            }
        });
    };

    // Sidebar Logic
    const toggleDesktopSidebar = () => {
        desktopSidebarMode.value = desktopSidebarMode.value === 'expanded' ? 'collapsed' : 'expanded';

        if (typeof localStorage !== 'undefined') {
            localStorage.setItem('sidebarMode', desktopSidebarMode.value);
        }
    };

    const setDesktopSidebarMode = (mode: SidebarMode) => {
        desktopSidebarMode.value = mode;

        if (typeof localStorage !== 'undefined') {
            localStorage.setItem('sidebarMode', mode);
        }
    };

    const toggleMobileDrawer = () => {
        isMobileDrawerOpen.value = !isMobileDrawerOpen.value;
    };

    const closeMobileDrawer = () => {
        isMobileDrawerOpen.value = false;
    };
    
    const initSidebar = () => {
        if (typeof localStorage === 'undefined') {
return;
}
        
        const storedMode = localStorage.getItem('sidebarMode') as SidebarMode | null;

        if (storedMode) {
            desktopSidebarMode.value = storedMode;
        }
    };

    return {
        // State
        desktopSidebarMode,
        isMobileDrawerOpen,
        theme,
        isDark,
        isDarkMode,

        // Actions
        setTheme,
        initTheme,
        toggleDarkMode,
        toggleDesktopSidebar,
        setDesktopSidebarMode,
        toggleMobileDrawer,
        closeMobileDrawer,
        initSidebar,
    };
});
