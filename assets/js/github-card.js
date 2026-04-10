
function github_card_get_or_null(obj, key) {
    return obj && obj.hasOwnProperty(key) ? obj[key] : null;
}


function github_card_contributors_plus(number) {
    if (typeof number !== 'number') return number;

    if (number >= 100) {
        return number + '+';
    }
    return String(number);
}


function github_card_compact_number(number) {
    if (typeof number !== 'number') return number;

    const absNumber = Math.abs(number);
    if (absNumber < 1000) return String(number);

    const units = ['', 'K', 'M', 'B', 'T']; // Thousand, Million, Billion, Trillion
    const power = Math.floor(Math.log10(absNumber) / 3); // Determine which unit to use

    let value = number / Math.pow(1000, power);

    // Remove .0 if whole number
    value = (value === Math.floor(value)) ? Math.floor(value) : Math.round(value * 10) / 10;

    return value + units[power];
}



function github_card_pluralize(count, singular, pluralSuffix) {
    return count <= 1 ? singular : singular + pluralSuffix;
}