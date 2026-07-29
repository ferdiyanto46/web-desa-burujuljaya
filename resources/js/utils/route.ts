/**
 * @deprecated Use lib/route.ts route().current() instead.
 */
import { usePage } from '@inertiajs/vue3';

/**
 * Check if the current route is active based on the URL path.
 * 
 * @param path The path to check against the current URL
 * @param exact If true, requires exact match. If false, matches if current URL starts with path
 */
export const isRouteActive = (path: string, exact = false): boolean => {
    const page = usePage();
    const currentUrl = page.url.split('?')[0]; // Remove query params
    
    // Normalize paths to ensure leading slash
    const normalizedPath = path.startsWith('/') ? path : `/${path}`;
    const normalizedCurrent = currentUrl.startsWith('/') ? currentUrl : `/${currentUrl}`;

    if (exact) {
        return normalizedCurrent === normalizedPath;
    }

    return normalizedCurrent.startsWith(normalizedPath);
};
