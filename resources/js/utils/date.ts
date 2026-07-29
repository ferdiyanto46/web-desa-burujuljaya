export const formatDate = (dateString: string | null | undefined, locale = 'id-ID'): string => {
    if (!dateString) {
return '-';
}

    const date = new Date(dateString);

    if (isNaN(date.getTime())) {
return '-';
}
    
    return new Intl.DateTimeFormat(locale, {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    }).format(date);
};

export const formatDateTime = (dateString: string | null | undefined, locale = 'id-ID'): string => {
    if (!dateString) {
return '-';
}

    const date = new Date(dateString);

    if (isNaN(date.getTime())) {
return '-';
}
    
    return new Intl.DateTimeFormat(locale, {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(date);
};
