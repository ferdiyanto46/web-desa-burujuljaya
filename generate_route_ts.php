<?php

$routesJson = file_get_contents('routes_dump.json');
$routeTsContent = "
const routes: Record<string, string> = $routesJson;

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
        console.warn(`Route \${name} not found`);
        return '#';
    }

    if (params !== undefined && params !== null) {
        if (typeof params === 'string' || typeof params === 'number') {
            url = url.replace(/\{[^}]+\}/, String(params));
        } else if (typeof params === 'object') {
            for (const [key, value] of Object.entries(params)) {
                url = url.replace(`{\${key}}`, String(value))
                         .replace(`{\${key}?}`, String(value));
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
        pattern = `^\${pattern}\$`;
        
        if (new RegExp(pattern).test(path)) {
            currentName = key;
            break;
        }
    }

    if (name) {
        if (name.includes('*')) {
            const pattern = name.replace(/\*/g, '.*');
            return new RegExp(`^\${pattern}\$`).test(currentName || '');
        }
        return currentName === name;
    }
    
    return currentName;
};

export const route = routeFn;
";

file_put_contents('resources/js/lib/route.ts', $routeTsContent);
echo "route.ts updated successfully.\n";
