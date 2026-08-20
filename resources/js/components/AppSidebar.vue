<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { BookOpen, FolderGit2, LayoutGrid, FileText, BellRing, CalendarDays, Building2, Store, MapPin, Package, Image, Video, FileDown, MessageSquare, Users, Settings } from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import adminRoutes from '@/routes/admin';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Artikel',
        href: adminRoutes.articles.index.url(),
        icon: FileText,
    },
    {
        title: 'Pengumuman',
        href: adminRoutes.announcements.index.url(),
        icon: BellRing,
    },
    {
        title: 'Agenda',
        href: adminRoutes.agendas.index.url(),
        icon: CalendarDays,
    },
    {
        title: 'Profil Desa',
        href: adminRoutes.villageProfile.edit.url(),
        icon: Building2,
    },
    {
        title: 'Halaman Desa',
        href: adminRoutes.villagePages.index.url(),
        icon: FileText,
    },
    {
        title: 'UMKM',
        href: adminRoutes.umkm.index.url(),
        icon: Store,
    },
    {
        title: 'Wisata',
        href: adminRoutes.tourisms.index.url(),
        icon: MapPin,
    },
    {
        title: 'Galeri',
        href: adminRoutes.galleries.index.url(),
        icon: Image,
    },
    {
        title: 'Video',
        href: adminRoutes.videos.index.url(),
        icon: Video,
    },
    {
        title: 'Pengguna',
        href: adminRoutes.users.index.url(),
        icon: Users,
    },
    {
        title: 'Pengaturan',
        href: adminRoutes.settings.index.url(),
        icon: Settings,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: FolderGit2,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
