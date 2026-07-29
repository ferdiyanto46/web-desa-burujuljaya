export const getImageUrl = (path: string | null | undefined, fallback = '/images/placeholder.jpg'): string => {
    if (!path) {
return fallback;
}

    if (path.startsWith('http')) {
return path;
}

    if (path.startsWith('/')) {
return path;
}

    return `/storage/${path}`;
};
