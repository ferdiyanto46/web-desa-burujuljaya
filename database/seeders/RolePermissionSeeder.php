<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Seeds all roles and permissions for the Desa Burujuljaya RBAC system.
 *
 * Role hierarchy (ascending privilege):
 *   editor-berita → operator → admin-desa → super-admin
 *
 * Permission naming convention: {resource}.{action}
 *   Actions: viewAny, view, create, update, delete, publish, restore
 *
 * Super-admin uses a Gate::before() bypass (defined in AppServiceProvider)
 * and therefore does NOT need explicit permission assignments.
 *
 * Idempotent: safe to run multiple times via `findOrCreate` + `syncPermissions`.
 */
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear the permission cache to avoid stale data during seeding
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Create every permission defined in the permission map
        $permissions = array_unique($this->allPermissions());
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Re-cache permissions before assigning them to roles to avoid Spatie cache issues
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Create roles and assign their permission sets
        $this->seedSuperAdminRole();
        $this->seedAdminDesaRole();
        $this->seedOperatorRole();
        $this->seedEditorBeritaRole();

        // Re-cache permissions after seeding
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Role Seeding
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * super-admin: Full system access via Gate::before() bypass.
     * No explicit permissions are assigned — the gate bypass covers everything.
     */
    private function seedSuperAdminRole(): void
    {
        Role::findOrCreate('super-admin');
    }

    /**
     * admin-desa: All content management + user management + settings.
     * Cannot manage roles or create backups (super-admin only).
     */
    private function seedAdminDesaRole(): void
    {
        $role = Role::findOrCreate('admin-desa');

        $role->syncPermissions([
            ...$this->editorBeritaPermissions(),
            ...$this->operatorPermissions(),
            ...$this->adminDesaPermissions(),
        ]);
    }

    /**
     * operator: Content management without user/settings management.
     * Cannot delete critical records — only soft-archive.
     */
    private function seedOperatorRole(): void
    {
        $role = Role::findOrCreate('operator');

        $role->syncPermissions([
            ...$this->editorBeritaPermissions(),
            ...$this->operatorPermissions(),
        ]);
    }

    /**
     * editor-berita: Limited to articles, announcements, and agendas only.
     */
    private function seedEditorBeritaRole(): void
    {
        $role = Role::findOrCreate('editor-berita');
        $role->syncPermissions($this->editorBeritaPermissions());
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Permission Sets per Role Tier
    // Each tier's permissions are cumulative (operator includes editor's, etc.)
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Permissions available to editor-berita (lowest privilege).
     *
     * @return array<string>
     */
    private function editorBeritaPermissions(): array
    {
        return [
            // Articles
            'articles.viewAny', 'articles.view', 'articles.create',
            'articles.update', 'articles.delete', 'articles.publish', 'articles.restore',

            // Announcements
            'announcements.viewAny', 'announcements.view', 'announcements.create',
            'announcements.update', 'announcements.delete', 'announcements.publish',

            // Agendas
            'agendas.viewAny', 'agendas.view', 'agendas.create',
            'agendas.update', 'agendas.delete',
        ];
    }

    /**
     * Additional permissions for operator (adds to editor-berita set).
     *
     * @return array<string>
     */
    private function operatorPermissions(): array
    {
        return [
            // Apparatus
            'apparatus.viewAny', 'apparatus.view', 'apparatus.create',
            'apparatus.update', 'apparatus.delete',

            // Galleries
            'galleries.viewAny', 'galleries.view', 'galleries.create',
            'galleries.update', 'galleries.delete', 'galleries.restore', 'galleries.forceDelete',

            // Gallery Photos
            'gallery-photos.viewAny', 'gallery-photos.view', 'gallery-photos.create',
            'gallery-photos.update', 'gallery-photos.delete', 'gallery-photos.restore', 'gallery-photos.forceDelete',
            'videos.viewAny', 'videos.view', 'videos.create',
            'videos.update', 'videos.delete',

            // Potentials
            'umkm.viewAny', 'umkm.view', 'umkm.create', 'umkm.update', 'umkm.delete',
            'tourism.viewAny', 'tourism.view', 'tourism.create', 'tourism.update', 'tourism.delete',
            'products.viewAny', 'products.view', 'products.create', 'products.update', 'products.delete',

            // Documents
            'documents.viewAny', 'documents.view', 'documents.create',
            'documents.update', 'documents.delete',

            // Contacts (read + reply only; delete is admin-desa)
            'contacts.viewAny', 'contacts.view', 'contacts.reply',
        ];
    }

    /**
     * Additional permissions for admin-desa (adds to operator set).
     *
     * @return array<string>
     */
    private function adminDesaPermissions(): array
    {
        return [
            // Village profile & statistics
            'village-profile.view', 'village-profile.update',
            'village-pages.viewAny', 'village-pages.view', 'village-pages.create', 'village-pages.update', 'village-pages.delete', 'village-pages.restore', 'village-pages.forceDelete',
            'village-services.viewAny', 'village-services.view', 'village-services.create', 'village-services.update', 'village-services.delete', 'village-services.restore', 'village-services.forceDelete',
            'contact-information.viewAny', 'contact-information.view', 'contact-information.create', 'contact-information.update', 'contact-information.delete', 'contact-information.restore', 'contact-information.forceDelete',
            'statistics.viewAny', 'statistics.view', 'statistics.create',
            'statistics.update', 'statistics.delete',

            // Website management & Complaints
            'banners.viewAny', 'banners.view', 'banners.create', 'banners.update', 'banners.delete',
            'faqs.viewAny', 'faqs.view', 'faqs.create', 'faqs.update', 'faqs.delete', 'faqs.restore', 'faqs.forceDelete',
            'homepage-sections.viewAny', 'homepage-sections.view', 'homepage-sections.create', 'homepage-sections.update', 'homepage-sections.delete',
            'complaints.viewAny', 'complaints.view', 'complaints.update', 'complaints.delete',
            'contacts.delete',

            // Activity Logs (view only)
            'logs.viewAny',
        ];
    }

    /**
     * Permissions exclusively available to super-admin via Gate::before().
     * Listed here for documentation/discoverability; not directly assigned to the role.
     *
     * @return array<string>
     */
    private function superAdminOnlyPermissions(): array
    {
        return [
            // User management (Exclusive to Super Administrator)
            'users.viewAny', 'users.view', 'users.create', 'users.update', 'users.delete', 'users.restore', 'users.forceDelete',

            // System Settings & Maintenance (Exclusive to Super Administrator)
            'settings.view', 'settings.update', 'manage settings',
            'roles.viewAny', 'roles.view', 'roles.create', 'roles.update', 'roles.delete',
            'backup.create', 'backup.download', 'manage backups',
            'cache.clear',
        ];
    }

    /**
     * The complete list of all permissions in the system.
     *
     * @return array<string>
     */
    private function allPermissions(): array
    {
        return [
            ...$this->editorBeritaPermissions(),
            ...$this->operatorPermissions(),
            ...$this->adminDesaPermissions(),
            ...$this->superAdminOnlyPermissions(),
        ];
    }
}
