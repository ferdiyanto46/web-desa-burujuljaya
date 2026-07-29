
const routes: Record<string, string> = {
    "well-known.passkeys": "/.well-known/passkey-endpoints",
    "home": "/",
    "boost.browser-logs": "/_boost/browser-logs",
    "admin.agendas.index": "/admin/agendas",
    "admin.agendas.store": "/admin/agendas",
    "admin.agendas.create": "/admin/agendas/create",
    "admin.agendas.update": "/admin/agendas/{agenda}",
    "admin.agendas.destroy": "/admin/agendas/{agenda}",
    "admin.agendas.edit": "/admin/agendas/{agenda}/edit",
    "admin.announcements.index": "/admin/announcements",
    "admin.announcements.store": "/admin/announcements",
    "admin.announcements.create": "/admin/announcements/create",
    "admin.announcements.update": "/admin/announcements/{announcement}",
    "admin.announcements.destroy": "/admin/announcements/{announcement}",
    "admin.announcements.edit": "/admin/announcements/{announcement}/edit",
    "admin.apparatus.index": "/admin/apparatus",
    "admin.apparatus.store": "/admin/apparatus",
    "admin.apparatus.create": "/admin/apparatus/create",
    "admin.apparatus.update": "/admin/apparatus/{apparatu}",
    "admin.apparatus.destroy": "/admin/apparatus/{apparatu}",
    "admin.apparatus.edit": "/admin/apparatus/{apparatu}/edit",
    "admin.articles.index": "/admin/articles",
    "admin.articles.store": "/admin/articles",
    "admin.articles.create": "/admin/articles/create",
    "admin.articles.update": "/admin/articles/{article}",
    "admin.articles.destroy": "/admin/articles/{article}",
    "admin.articles.edit": "/admin/articles/{article}/edit",
    "admin.complaints.index": "/admin/complaints",
    "admin.complaints.show": "/admin/complaints/{complaint}",
    "admin.complaints.update": "/admin/complaints/{complaint}",
    "admin.complaints.destroy": "/admin/complaints/{complaint}",
    "admin.dashboard": "/admin/dashboard",
    "admin.documents.index": "/admin/documents",
    "admin.documents.store": "/admin/documents",
    "admin.documents.create": "/admin/documents/create",
    "admin.documents.update": "/admin/documents/{document}",
    "admin.documents.destroy": "/admin/documents/{document}",
    "admin.documents.edit": "/admin/documents/{document}/edit",
    "admin.gallery-albums.index": "/admin/gallery-albums",
    "admin.gallery-albums.store": "/admin/gallery-albums",
    "admin.gallery-albums.create": "/admin/gallery-albums/create",
    "admin.gallery-albums.photos.store": "/admin/gallery-albums/{album}/photos",
    "admin.gallery-albums.photos.destroy": "/admin/gallery-albums/{album}/photos/{photo}",
    "admin.gallery-albums.show": "/admin/gallery-albums/{gallery_album}",
    "admin.gallery-albums.update": "/admin/gallery-albums/{gallery_album}",
    "admin.gallery-albums.destroy": "/admin/gallery-albums/{gallery_album}",
    "admin.gallery-albums.edit": "/admin/gallery-albums/{gallery_album}/edit",
    "admin.products.index": "/admin/products",
    "admin.products.store": "/admin/products",
    "admin.products.create": "/admin/products/create",
    "admin.products.update": "/admin/products/{product}",
    "admin.products.destroy": "/admin/products/{product}",
    "admin.products.edit": "/admin/products/{product}/edit",
    "admin.settings.index": "/admin/settings",
    "admin.settings.update": "/admin/settings",
    "admin.tourism.index": "/admin/tourism",
    "admin.tourism.store": "/admin/tourism",
    "admin.tourism.create": "/admin/tourism/create",
    "admin.tourism.update": "/admin/tourism/{tourism}",
    "admin.tourism.destroy": "/admin/tourism/{tourism}",
    "admin.tourism.edit": "/admin/tourism/{tourism}/edit",
    "admin.umkm.index": "/admin/umkm",
    "admin.umkm.store": "/admin/umkm",
    "admin.umkm.create": "/admin/umkm/create",
    "admin.umkm.update": "/admin/umkm/{umkm}",
    "admin.umkm.destroy": "/admin/umkm/{umkm}",
    "admin.umkm.edit": "/admin/umkm/{umkm}/edit",
    "admin.users.index": "/admin/users",
    "admin.users.store": "/admin/users",
    "admin.users.create": "/admin/users/create",
    "admin.users.update": "/admin/users/{user}",
    "admin.users.destroy": "/admin/users/{user}",
    "admin.users.edit": "/admin/users/{user}/edit",
    "admin.videos.index": "/admin/videos",
    "admin.videos.store": "/admin/videos",
    "admin.videos.create": "/admin/videos/create",
    "admin.videos.update": "/admin/videos/{video}",
    "admin.videos.destroy": "/admin/videos/{video}",
    "admin.videos.edit": "/admin/videos/{video}/edit",
    "admin.village-profile.edit": "/admin/village-profile",
    "admin.village-profile.update": "/admin/village-profile",
    "agendas.index": "/agenda",
    "agendas.show": "/agenda/{slug}",
    "pages.apparatus": "/aparatur",
    "articles.index": "/artikel",
    "articles.show": "/artikel/{slug}",
    "dashboard": "/dashboard",
    "documents.index": "/dokumen",
    "documents.download": "/dokumen/{ulid}/download",
    "verification.send": "/email/verification-notification",
    "verification.notice": "/email/verify",
    "verification.verify": "/email/verify/{id}/{hash}",
    "password.request": "/forgot-password",
    "password.email": "/forgot-password",
    "gallery.index": "/galeri",
    "gallery.show": "/galeri/{slug}",
    "complaints.track": "/lacak-pengaduan",
    "login": "/login",
    "login.store": "/login",
    "logout": "/logout",
    "passkey.confirm": "/passkeys/confirm",
    "passkey.confirm-options": "/passkeys/confirm/options",
    "passkey.login": "/passkeys/login",
    "passkey.login-options": "/passkeys/login/options",
    "complaints.create": "/pengaduan",
    "complaints.store": "/pengaduan",
    "announcements.index": "/pengumuman",
    "announcements.show": "/pengumuman/{slug}",
    "products.index": "/produk",
    "products.show": "/produk/{slug}",
    "pages.profile": "/profil",
    "register": "/register",
    "register.store": "/register",
    "password.update": "/reset-password",
    "password.reset": "/reset-password/{token}",
    "appearance.edit": "/settings/appearance",
    "user-password.update": "/settings/password",
    "profile.edit": "/settings/profile",
    "profile.update": "/settings/profile",
    "profile.destroy": "/settings/profile",
    "security.edit": "/settings/security",
    "storage.local": "/storage/{path}",
    "storage.local.upload": "/storage/{path}",
    "two-factor.login": "/two-factor-challenge",
    "two-factor.login.store": "/two-factor-challenge",
    "umkm.index": "/umkm",
    "umkm.show": "/umkm/{slug}",
    "password.confirm": "/user/confirm-password",
    "password.confirm.store": "/user/confirm-password",
    "password.confirmation": "/user/confirmed-password-status",
    "two-factor.confirm": "/user/confirmed-two-factor-authentication",
    "passkey.store": "/user/passkeys",
    "passkey.registration-options": "/user/passkeys/options",
    "passkey.destroy": "/user/passkeys/{passkey}",
    "two-factor.enable": "/user/two-factor-authentication",
    "two-factor.disable": "/user/two-factor-authentication",
    "two-factor.qr-code": "/user/two-factor-qr-code",
    "two-factor.recovery-codes": "/user/two-factor-recovery-codes",
    "two-factor.regenerate-recovery-codes": "/user/two-factor-recovery-codes",
    "two-factor.secret-key": "/user/two-factor-secret-key",
    "videos.index": "/video",
    "pages.vision-mission": "/visi-misi",
    "tourism.index": "/wisata",
    "tourism.show": "/wisata/{slug}"
};

export interface RouteHelper {
    (name?: string, params?: Record<string, any> | string | number): string | any;
    current: (name?: string) => string | boolean | undefined;
}

const routeFn = function(name?: string, params?: Record<string, any> | string | number): any {
    if (!name) {
        return routeFn;
    }

    let url = routes[name];

    if (!url) {
        console.warn(`Route ${name} not found`);

        return '#';
    }

    if (params !== undefined && params !== null) {
        if (typeof params === 'string' || typeof params === 'number') {
            url = url.replace(/\{[^}]+\}/, String(params));
        } else if (typeof params === 'object') {
            for (const [key, value] of Object.entries(params)) {
                url = url.replace(`{${key}}`, String(value))
                         .replace(`{${key}?}`, String(value));
            }
        }
    }

    url = url.replace(/\/[^{}]*\{[^}]+\?\}/g, '');
    url = url.replace(/\{[^}]+\}/g, '');

    return url;
} as RouteHelper;

routeFn.current = (name?: string) => {
    if (typeof window === 'undefined') {
return undefined;
}

    const path = window.location.pathname;
    let currentName: string | undefined;

    // Iterate backwards to match more specific routes first
    const entries = Object.entries(routes).reverse();

    for (const [key, value] of entries) {
        // Convert route pattern to regex
        // e.g. /artikel/{slug} -> ^/artikel/[^/]+$
        let pattern = value.replace(/\{[^}]+\?\}/g, '(?:/[^/]+)?');
        pattern = pattern.replace(/\{[^}]+\}/g, '[^/]+');
        pattern = `^${pattern}$`;
        
        if (new RegExp(pattern).test(path)) {
            currentName = key;
            break;
        }
    }

    if (name) {
        if (name.includes('*')) {
            const pattern = name.replace(/\*/g, '.*');

            return new RegExp(`^${pattern}$`).test(currentName || '');
        }

        return currentName === name;
    }
    
    return currentName;
};

export const route = routeFn;
