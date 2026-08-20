import { route } from '@/lib/route';

export interface NavigationItem {
    name: string;
    label: string;
    icon: string;
    route: string;
    activeRoute?: string; // used for matching active states using wildcards like 'admin.articles.*'
    roles?: string[]; // Restricted to specific roles (e.g. ['super-admin'])
    hidden?: boolean; // Set to true to disable/hide from sidebar navigation without deleting
    children?: NavigationItem[];
}

export interface NavigationGroup {
    label: string;
    roles?: string[];
    items: NavigationItem[];
}

export const ADMIN_NAVIGATION: NavigationGroup[] = [
    {
        label: 'Utama',
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
        label: 'Informasi & Desa',
        items: [
            {
                name: 'village-profile',
                label: 'Profil Desa',
                icon: 'HomeIcon',
                route: 'admin.village-profile.edit',
                activeRoute: 'admin.village-profile.*',
            },
            {
                name: 'village-pages',
                label: 'Halaman Desa',
                icon: 'FileTextIcon',
                route: 'admin.village-pages.index',
                activeRoute: 'admin.village-pages.*',
                hidden: true, // Dinonaktifkan sesuai permintaan
            },
            {
                name: 'village-services',
                label: 'Layanan Desa',
                icon: 'BriefcaseIcon',
                route: 'admin.village-services.index',
                activeRoute: 'admin.village-services.*',
                hidden: true, // Dinonaktifkan sesuai permintaan
            },
            {
                name: 'contact-information',
                label: 'Kontak & Info',
                icon: 'PhoneIcon',
                route: 'admin.contact-information.index',
                activeRoute: 'admin.contact-information.*',
                hidden: true, // Dinonaktifkan sesuai permintaan
            },
            {
                name: 'faqs',
                label: 'Tanya Jawab (FAQ)',
                icon: 'HelpCircleIcon',
                route: 'admin.faqs.index',
                activeRoute: 'admin.faqs.*',
                hidden: true, // Dinonaktifkan sesuai permintaan
            },
            {
                name: 'homepage-sections',
                label: 'Tata Letak Beranda',
                icon: 'LayoutDashboardIcon',
                route: 'admin.homepage-sections.index',
                activeRoute: 'admin.homepage-sections.*',
            },
            {
                name: 'page-banners',
                label: 'Banner Halaman',
                icon: 'ImageIcon',
                route: 'admin.settings.index',
                activeRoute: 'admin.settings.*',
            },
        ],
    },
    {
        label: 'Konten Publikasi',
        items: [
            {
                name: 'articles',
                label: 'Berita & Artikel',
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
                hidden: true, // Dinonaktifkan sesuai permintaan
            },
            {
                name: 'agendas',
                label: 'Agenda Kegiatan',
                icon: 'CalendarIcon',
                route: 'admin.agendas.index',
                activeRoute: 'admin.agendas.*',
                hidden: true, // Dinonaktifkan sesuai permintaan
            },
            {
                name: 'documents',
                label: 'Dokumen Publik',
                icon: 'FileIcon',
                route: 'admin.documents.index',
                activeRoute: 'admin.documents.*',
            },
        ],
    },
    {
        label: 'Galeri & Media',
        items: [
            {
                name: 'galleries',
                label: 'Galeri Foto',
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
                hidden: true, // Dinonaktifkan sesuai permintaan
            },
            {
                name: 'apparatus',
                label: 'Perangkat Desa',
                icon: 'UsersIcon',
                route: 'admin.apparatus.index',
                activeRoute: 'admin.apparatus.*',
            },
        ],
    },
    {
        label: 'Layanan & Potensi',
        items: [
            {
                name: 'complaints',
                label: 'Pengaduan Warga',
                icon: 'MessageSquareWarningIcon',
                route: 'admin.complaints.index',
                activeRoute: 'admin.complaints.*',
            },
            {
                name: 'umkm',
                label: 'UMKM Desa',
                icon: 'StoreIcon',
                route: 'admin.umkm.index',
                activeRoute: 'admin.umkm.*',
            },
            {
                name: 'tourisms',
                label: 'Wisata Desa',
                icon: 'MapIcon',
                route: 'admin.tourisms.index',
                activeRoute: 'admin.tourisms.*',
                hidden: true, // Dinonaktifkan sesuai permintaan
            },
            {
                name: 'products',
                label: 'Produk Desa',
                icon: 'PackageIcon',
                route: 'admin.products.index',
                activeRoute: 'admin.products.*',
                hidden: true, // Dinonaktifkan sesuai permintaan
            },
        ],
    },
    {
        label: 'Sistem & Pengaturan',
        items: [
            {
                name: 'users',
                label: 'Manajemen Pengguna',
                icon: 'UsersIcon',
                route: 'admin.users.index',
                activeRoute: 'admin.users.*',
                roles: ['super-admin'],
            },
            {
                name: 'settings',
                label: 'Pengaturan Sistem',
                icon: 'SettingsIcon',
                route: 'admin.settings.index',
                activeRoute: 'admin.settings.*',
                roles: ['super-admin'],
            },
            {
                name: 'activity-logs',
                label: 'Log Aktivitas',
                icon: 'HistoryIcon',
                route: 'admin.activity-logs.index',
                activeRoute: 'admin.activity-logs.*',
            },
            {
                name: 'audit',
                label: 'Audit Keamanan',
                icon: 'ShieldIcon',
                route: 'admin.audit.index',
                activeRoute: 'admin.audit.*',
            },
            {
                name: 'backups',
                label: 'Backup Data',
                icon: 'DatabaseIcon',
                route: 'admin.backups.index',
                activeRoute: 'admin.backups.*',
                roles: ['super-admin'],
            },
            {
                name: 'cache',
                label: 'Cache Sistem',
                icon: 'FolderIcon',
                route: 'admin.cache.index',
                activeRoute: 'admin.cache.*',
                roles: ['super-admin'],
            },
        ],
    },
];

// Helper to find breadcrumb path based on current route name
export const getBreadcrumbsForRoute = (currentRouteName: string) => {
    const breadcrumbs: { label: string; url?: string }[] = [];
    
    if (currentRouteName !== 'admin.dashboard') {
        breadcrumbs.push({ label: 'Dashboard', url: route('admin.dashboard') });
    }

    for (const group of ADMIN_NAVIGATION) {
        for (const item of group.items) {
            if (item.activeRoute) {
                const pattern = item.activeRoute.replace('*', '.*');
                const regex = new RegExp(`^${pattern}$`);

                if (regex.test(currentRouteName)) {
                    breadcrumbs.push({ label: item.label, url: route(item.route) });
                    
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
