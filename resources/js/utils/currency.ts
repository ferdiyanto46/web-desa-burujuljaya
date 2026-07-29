export const formatCurrency = (amount: number | string | null | undefined, currency = 'IDR', locale = 'id-ID'): string => {
    if (amount === null || amount === undefined) {
return '-';
}
    
    const numericAmount = typeof amount === 'string' ? parseFloat(amount) : amount;

    if (isNaN(numericAmount)) {
return '-';
}

    return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(numericAmount);
};
