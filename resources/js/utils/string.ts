export const truncateString = (str: string | null | undefined, length = 50, suffix = '...'): string => {
    if (!str) {
return '';
}

    if (str.length <= length) {
return str;
}

    return str.substring(0, length).trim() + suffix;
};

export const capitalizeFirstLetter = (str: string | null | undefined): string => {
    if (!str) {
return '';
}

    return str.charAt(0).toUpperCase() + str.slice(1);
};
