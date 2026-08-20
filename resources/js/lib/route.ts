
const routes: Record<string, string> = {
    "boost.browser-logs": "\/_boost\/browser-logs",
    "login": "\/login",
    "login.store": "\/login",
    "logout": "\/logout",
    "password.request": "\/forgot-password",
    "password.reset": "\/reset-password\/{token}",
    "password.email": "\/forgot-password",
    "password.update": "\/reset-password",
    "register": "\/register",
    "register.store": "\/register",
    "verification.notice": "\/email\/verify",
    "verification.verify": "\/email\/verify\/{id}\/{hash}",
    "verification.send": "\/email\/verification-notification",
    "password.confirm": "\/user\/confirm-password",
    "password.confirmation": "\/user\/confirmed-password-status",
    "password.confirm.store": "\/user\/confirm-password",
    "two-factor.login": "\/two-factor-challenge",
    "two-factor.login.store": "\/two-factor-challenge",
    "two-factor.enable": "\/user\/two-factor-authentication",
    "two-factor.confirm": "\/user\/confirmed-two-factor-authentication",
    "two-factor.disable": "\/user\/two-factor-authentication",
    "two-factor.qr-code": "\/user\/two-factor-qr-code",
    "two-factor.secret-key": "\/user\/two-factor-secret-key",
    "two-factor.recovery-codes": "\/user\/two-factor-recovery-codes",
    "two-factor.regenerate-recovery-codes": "\/user\/two-factor-recovery-codes",
    "passkey.login-options": "\/passkeys\/login\/options",
    "passkey.login": "\/passkeys\/login",
    "passkey.confirm-options": "\/passkeys\/confirm\/options",
    "passkey.confirm": "\/passkeys\/confirm",
    "passkey.registration-options": "\/user\/passkeys\/options",
    "passkey.store": "\/user\/passkeys",
    "passkey.destroy": "\/user\/passkeys\/{passkey}",
    "home": "\/",
    "profile.index": "\/profil",
    "profile.show": "\/profil\/{slug}",
    "village-services.index": "\/layanan",
    "village-services.show": "\/layanan\/{slug}",
    "contact-information.index": "\/kontak",
    "contact-information.show": "\/kontak\/{slug}",
    "faq.index": "\/faq",
    "sitemap": "\/sitemap.xml",
    "faq.show": "\/faq\/{slug}",
    "articles.index": "\/artikel",
    "articles.show": "\/artikel\/{slug}",
    "announcements.index": "\/pengumuman",
    "announcements.show": "\/pengumuman\/{slug}",
    "agendas.index": "\/agenda",
    "agendas.show": "\/agenda\/{slug}",
    "gallery.index": "\/galeri",
    "gallery.show": "\/galeri\/{slug}",
    "videos.index": "\/video",
    "tourism.index": "\/wisata",
    "tourism.show": "\/wisata\/{slug}",
    "umkm.index": "\/umkm",
    "umkm.show": "\/umkm\/{umkm}",
    "products.index": "\/produk",
    "products.show": "\/produk\/{product}",
    "documents.index": "\/dokumen",
    "documents.download": "\/dokumen\/{ulid}\/download",
    "complaints.create": "\/pengaduan",
    "complaints.store": "\/pengaduan",
    "complaints.track": "\/lacak-pengaduan",
    "dashboard": "\/dashboard",
    "profile.edit": "\/settings\/profile",
    "profile.update": "\/settings\/profile",
    "profile.destroy": "\/settings\/profile",
    "security.edit": "\/settings\/security",
    "user-password.update": "\/settings\/password",
    "appearance.edit": "\/settings\/appearance",
    "well-known.passkeys": "\/.well-known\/passkey-endpoints",
    "admin.dashboard": "\/admin\/dashboard",
    "admin.village-profile.edit": "\/admin\/village-profile",
    "admin.village-profile.update": "\/admin\/village-profile",
    "admin.village-pages.restore": "\/admin\/village-pages\/{village_page}\/restore",
    "admin.village-pages.force-delete": "\/admin\/village-pages\/{village_page}\/force-delete",
    "admin.village-pages.index": "\/admin\/village-pages",
    "admin.village-pages.create": "\/admin\/village-pages\/create",
    "admin.village-pages.store": "\/admin\/village-pages",
    "admin.village-pages.show": "\/admin\/village-pages\/{village_page}",
    "admin.village-pages.edit": "\/admin\/village-pages\/{village_page}\/edit",
    "admin.village-pages.update": "\/admin\/village-pages\/{village_page}",
    "admin.village-pages.destroy": "\/admin\/village-pages\/{village_page}",
    "admin.village-services.restore": "\/admin\/village-services\/{village_service}\/restore",
    "admin.village-services.force-delete": "\/admin\/village-services\/{village_service}\/force-delete",
    "admin.village-services.index": "\/admin\/village-services",
    "admin.village-services.create": "\/admin\/village-services\/create",
    "admin.village-services.store": "\/admin\/village-services",
    "admin.village-services.show": "\/admin\/village-services\/{village_service}",
    "admin.village-services.edit": "\/admin\/village-services\/{village_service}\/edit",
    "admin.village-services.update": "\/admin\/village-services\/{village_service}",
    "admin.village-services.destroy": "\/admin\/village-services\/{village_service}",
    "admin.contact-information.restore": "\/admin\/contact-information\/{contact_information}\/restore",
    "admin.contact-information.force-delete": "\/admin\/contact-information\/{contact_information}\/force-delete",
    "admin.contact-information.index": "\/admin\/contact-information",
    "admin.contact-information.create": "\/admin\/contact-information\/create",
    "admin.contact-information.store": "\/admin\/contact-information",
    "admin.contact-information.show": "\/admin\/contact-information\/{contact_information}",
    "admin.contact-information.edit": "\/admin\/contact-information\/{contact_information}\/edit",
    "admin.contact-information.update": "\/admin\/contact-information\/{contact_information}",
    "admin.contact-information.destroy": "\/admin\/contact-information\/{contact_information}",
    "admin.faqs.restore": "\/admin\/faqs\/{faq}\/restore",
    "admin.faqs.force-delete": "\/admin\/faqs\/{faq}\/force-delete",
    "admin.faqs.index": "\/admin\/faqs",
    "admin.faqs.create": "\/admin\/faqs\/create",
    "admin.faqs.store": "\/admin\/faqs",
    "admin.faqs.show": "\/admin\/faqs\/{faq}",
    "admin.faqs.edit": "\/admin\/faqs\/{faq}\/edit",
    "admin.faqs.update": "\/admin\/faqs\/{faq}",
    "admin.faqs.destroy": "\/admin\/faqs\/{faq}",
    "admin.homepage-sections.reorder": "\/admin\/homepage-sections\/reorder",
    "admin.homepage-sections.toggle": "\/admin\/homepage-sections\/{homepage_section}\/toggle",
    "admin.homepage-sections.index": "\/admin\/homepage-sections",
    "admin.homepage-sections.edit": "\/admin\/homepage-sections\/{homepage_section}\/edit",
    "admin.homepage-sections.update": "\/admin\/homepage-sections\/{homepage_section}",
    "admin.settings.index": "\/admin\/settings",
    "admin.settings.update": "\/admin\/settings",
    "admin.activity-logs.index": "\/admin\/activity-logs",
    "admin.audit.index": "\/admin\/audit",
    "admin.users.index": "\/admin\/users",
    "admin.users.create": "\/admin\/users\/create",
    "admin.users.store": "\/admin\/users",
    "admin.users.edit": "\/admin\/users\/{user}\/edit",
    "admin.users.update": "\/admin\/users\/{user}",
    "admin.users.destroy": "\/admin\/users\/{user}",
    "admin.backups.index": "\/admin\/backups",
    "admin.backups.store": "\/admin\/backups",
    "admin.backups.download": "\/admin\/backups\/{file}",
    "admin.backups.destroy": "\/admin\/backups\/{file}",
    "admin.cache.index": "\/admin\/cache",
    "admin.cache.clear": "\/admin\/cache\/clear",
    "admin.articles.index": "\/admin\/articles",
    "admin.articles.create": "\/admin\/articles\/create",
    "admin.articles.store": "\/admin\/articles",
    "admin.articles.edit": "\/admin\/articles\/{article}\/edit",
    "admin.articles.update": "\/admin\/articles\/{article}",
    "admin.articles.update.post": "\/admin\/articles\/{article}",
    "admin.articles.destroy": "\/admin\/articles\/{article}",
    "admin.announcements.index": "\/admin\/announcements",
    "admin.announcements.create": "\/admin\/announcements\/create",
    "admin.announcements.store": "\/admin\/announcements",
    "admin.announcements.edit": "\/admin\/announcements\/{announcement}\/edit",
    "admin.announcements.update": "\/admin\/announcements\/{announcement}",
    "admin.announcements.destroy": "\/admin\/announcements\/{announcement}",
    "admin.agendas.index": "\/admin\/agendas",
    "admin.agendas.create": "\/admin\/agendas\/create",
    "admin.agendas.store": "\/admin\/agendas",
    "admin.agendas.edit": "\/admin\/agendas\/{agenda}\/edit",
    "admin.agendas.update": "\/admin\/agendas\/{agenda}",
    "admin.agendas.destroy": "\/admin\/agendas\/{agenda}",
    "admin.apparatus.index": "\/admin\/apparatus",
    "admin.apparatus.create": "\/admin\/apparatus\/create",
    "admin.apparatus.store": "\/admin\/apparatus",
    "admin.apparatus.edit": "\/admin\/apparatus\/{apparatus}\/edit",
    "admin.apparatus.update": "\/admin\/apparatus\/{apparatus}",
    "admin.apparatus.update.post": "\/admin\/apparatus\/{apparatus}",
    "admin.apparatus.destroy": "\/admin\/apparatus\/{apparatus}",
    "admin.galleries.index": "\/admin\/galleries",
    "admin.galleries.create": "\/admin\/galleries\/create",
    "admin.galleries.store": "\/admin\/galleries",
    "admin.galleries.edit": "\/admin\/galleries\/{gallery}\/edit",
    "admin.galleries.update": "\/admin\/galleries\/{gallery}",
    "admin.galleries.destroy": "\/admin\/galleries\/{gallery}",
    "admin.galleries.photos.store": "\/admin\/galleries\/{gallery}\/photos",
    "admin.galleries.photos.reorder": "\/admin\/galleries\/{gallery}\/photos\/reorder",
    "admin.galleries.photos.update": "\/admin\/galleries\/{gallery}\/photos\/{photo}",
    "admin.galleries.photos.destroy": "\/admin\/galleries\/{gallery}\/photos\/{photo}",
    "admin.videos.index": "\/admin\/videos",
    "admin.videos.create": "\/admin\/videos\/create",
    "admin.videos.store": "\/admin\/videos",
    "admin.videos.edit": "\/admin\/videos\/{video}\/edit",
    "admin.videos.update": "\/admin\/videos\/{video}",
    "admin.videos.destroy": "\/admin\/videos\/{video}",
    "admin.complaints.index": "\/admin\/complaints",
    "admin.complaints.show": "\/admin\/complaints\/{complaint}",
    "admin.complaints.update": "\/admin\/complaints\/{complaint}",
    "admin.complaints.destroy": "\/admin\/complaints\/{complaint}",
    "admin.documents.restore": "\/admin\/documents\/{document}\/restore",
    "admin.documents.force-delete": "\/admin\/documents\/{document}\/force-delete",
    "admin.documents.index": "\/admin\/documents",
    "admin.documents.create": "\/admin\/documents\/create",
    "admin.documents.store": "\/admin\/documents",
    "admin.documents.edit": "\/admin\/documents\/{document}\/edit",
    "admin.documents.update": "\/admin\/documents\/{document}",
    "admin.documents.destroy": "\/admin\/documents\/{document}",
    "admin.products.restore": "\/admin\/products\/{product}\/restore",
    "admin.products.force-delete": "\/admin\/products\/{product}\/force-delete",
    "admin.products.index": "\/admin\/products",
    "admin.products.create": "\/admin\/products\/create",
    "admin.products.store": "\/admin\/products",
    "admin.products.show": "\/admin\/products\/{product}",
    "admin.products.edit": "\/admin\/products\/{product}\/edit",
    "admin.products.update": "\/admin\/products\/{product}",
    "admin.products.destroy": "\/admin\/products\/{product}",
    "admin.tourism-categories.index": "\/admin\/tourism-categories",
    "admin.tourism-categories.create": "\/admin\/tourism-categories\/create",
    "admin.tourism-categories.store": "\/admin\/tourism-categories",
    "admin.tourism-categories.edit": "\/admin\/tourism-categories\/{tourism_category}\/edit",
    "admin.tourism-categories.update": "\/admin\/tourism-categories\/{tourism_category}",
    "admin.tourism-categories.destroy": "\/admin\/tourism-categories\/{tourism_category}",
    "admin.tourism-facilities.index": "\/admin\/tourism-facilities",
    "admin.tourism-facilities.create": "\/admin\/tourism-facilities\/create",
    "admin.tourism-facilities.store": "\/admin\/tourism-facilities",
    "admin.tourism-facilities.edit": "\/admin\/tourism-facilities\/{tourism_facility}\/edit",
    "admin.tourism-facilities.update": "\/admin\/tourism-facilities\/{tourism_facility}",
    "admin.tourism-facilities.destroy": "\/admin\/tourism-facilities\/{tourism_facility}",
    "admin.tourisms.restore": "\/admin\/tourisms\/{tourism}\/restore",
    "admin.tourisms.force-delete": "\/admin\/tourisms\/{tourism}\/force-delete",
    "admin.tourisms.photos.destroy": "\/admin\/tourisms\/{tourism}\/photos\/{photo}",
    "admin.tourisms.index": "\/admin\/tourisms",
    "admin.tourisms.create": "\/admin\/tourisms\/create",
    "admin.tourisms.store": "\/admin\/tourisms",
    "admin.tourisms.edit": "\/admin\/tourisms\/{tourism}\/edit",
    "admin.tourisms.update": "\/admin\/tourisms\/{tourism}",
    "admin.tourisms.destroy": "\/admin\/tourisms\/{tourism}",
    "admin.umkm-categories.index": "\/admin\/umkm-categories",
    "admin.umkm-categories.create": "\/admin\/umkm-categories\/create",
    "admin.umkm-categories.store": "\/admin\/umkm-categories",
    "admin.umkm-categories.show": "\/admin\/umkm-categories\/{umkm_category}",
    "admin.umkm-categories.edit": "\/admin\/umkm-categories\/{umkm_category}\/edit",
    "admin.umkm-categories.update": "\/admin\/umkm-categories\/{umkm_category}",
    "admin.umkm-categories.destroy": "\/admin\/umkm-categories\/{umkm_category}",
    "admin.umkm.restore": "\/admin\/umkm\/{umkm}\/restore",
    "admin.umkm.force-delete": "\/admin\/umkm\/{umkm}\/force-delete",
    "admin.umkm.index": "\/admin\/umkm",
    "admin.umkm.create": "\/admin\/umkm\/create",
    "admin.umkm.store": "\/admin\/umkm",
    "admin.umkm.show": "\/admin\/umkm\/{umkm}",
    "admin.umkm.edit": "\/admin\/umkm\/{umkm}\/edit",
    "admin.umkm.update": "\/admin\/umkm\/{umkm}",
    "admin.umkm.destroy": "\/admin\/umkm\/{umkm}",
    "storage.local": "\/storage\/{path}",
    "storage.local.upload": "\/storage\/{path}"
};

export interface RouteHelper {
    (name?: string, params?: any | string | number): string | any;
    current: (name?: string) => string | boolean | undefined;
}

const routeFn = function(name?: string, params?: any | string | number): unknown {
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
