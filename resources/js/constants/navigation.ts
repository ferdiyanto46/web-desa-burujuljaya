export interface NavigationItem {
    name: string;
    label: string;
    icon: string;
    route: string;
    activeRoute?: string; // used for matching active states using wildcards like 'admin.articles.*'
    children?: NavigationItem[];
}

export interface NavigationGroup {
    label: string;
    items: NavigationItem[];
}

export const ADMIN_NAVIGATION: NavigationGroup[] = [
    {
        label: 'Main',
        items: [
            {
                name: 'dashboard',
                label: 'Dashboard',
                icon: 'LayoutDashboardIcon',
                route: 'admin.dashboard',
                activeRoute: 'admin.dashboard',
            },
        ],
    },
    {
        label: 'Konten Publikasi',
        items: [
            {
                name: 'articles',
                label: 'Artikel',
                icon: 'FileTextIcon',
                route: 'admin.articles.index',
                activeRoute: 'admin.articles.*',
            },
            {
                name: 'announcements',
                label: 'Pengumuman',
                icon: 'MegaphoneIcon',
                route: 'admin.announcements.index',
                activeRoute: 'admin.announcements.*',
            },
            {
                name: 'agenda',
                label: 'Agenda',
                icon: 'CalendarIcon',
                route: 'admin.agenda.index',
                activeRoute: 'admin.agenda.*',
            },
        ],
    },
    {
        label: 'Galeri & Media',
        items: [
            {
                name: 'gallery',
                label: 'Galeri',
                icon: 'ImageIcon',
                route: 'admin.galleries.index',
                activeRoute: 'admin.galleries.*',
            },
            {
                name: 'videos',
                label: 'Video',
                icon: 'VideoIcon',
                route: 'admin.videos.index',
                activeRoute: 'admin.videos.*',
            },
            {
                name: 'documents',
                label: 'Dokumen',
                icon: 'FileIcon',
                route: 'admin.documents.index',
                activeRoute: 'admin.documents.*',
            },
        ],
    },
    {
        label: 'Layanan & Potensi',
        items: [
            {
                name: 'complaints',
                label: 'Pengaduan',
                icon: 'MessageSquareWarningIcon',
                route: 'admin.complaints.index',
                activeRoute: 'admin.complaints.*',
            },
            {
                name: 'products',
                label: 'Produk',
                icon: 'PackageIcon',
                route: 'admin.products.index',
                activeRoute: 'admin.products.*',
            },
            {
                name: 'umkm',
                label: 'UMKM',
                icon: 'StoreIcon',
                route: 'admin.umkm.index',
                activeRoute: 'admin.umkm.*',
            },
            {
                name: 'tourism',
                label: 'Wisata',
                icon: 'MapIcon',
                route: 'admin.tourism.index',
                activeRoute: 'admin.tourism.*',
            },
        ],
    },
    {
        label: 'Sistem',
        items: [
            {
                name: 'users',
                label: 'Pengguna',
                icon: 'UsersIcon',
                route: 'admin.users.index',
                activeRoute: 'admin.users.*',
            },
            {
                name: 'settings',
                label: 'Pengaturan',
                icon: 'SettingsIcon',
                route: 'admin.settings.index',
                activeRoute: 'admin.settings.*',
            },
        ],
    },
];

// Helper to find breadcrumb path based on current route name
export const getBreadcrumbsForRoute = (currentRouteName: string) => {
    // This is a simplified matching. In a real app with nested navs, this would be recursive.
    const breadcrumbs: { label: string; url?: string }[] = [];
    
    // Always start with Dashboard as root if not on dashboard
    if (currentRouteName !== 'admin.dashboard') {
        breadcrumbs.push({ label: 'Dashboard', url: route('admin.dashboard') });
    }

    for (const group of ADMIN_NAVIGATION) {
        for (const item of group.items) {
            // Check if current route matches activeRoute pattern (e.g., admin.articles.* matches admin.articles.index)
            if (item.activeRoute) {
                const pattern = item.activeRoute.replace('*', '.*');
                const regex = new RegExp(`^${pattern}$`);

                if (regex.test(currentRouteName)) {
                    breadcrumbs.push({ label: item.label, url: route(item.route) });
                    
                    // Add sub-page breadcrumbs based on action (create, edit, show)
                    if (currentRouteName.endsWith('.create')) {
                        breadcrumbs.push({ label: 'Tambah Baru' });
                    } else if (currentRouteName.endsWith('.edit')) {
                        breadcrumbs.push({ label: 'Edit' });
                    } else if (currentRouteName.endsWith('.show')) {
                        breadcrumbs.push({ label: 'Detail' });
                    }
                    
                    return breadcrumbs;
                }
            }
        }
    }
    
    return breadcrumbs;
};
