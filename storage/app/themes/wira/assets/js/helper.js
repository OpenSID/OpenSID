window.capitalizeFirstCharacterOfEachWord = window.capitalizeFirstCharacterOfEachWord || ((sentence) => {
    return sentence
        .split(' ') // Split the sentence into words
        .map(word => 
            word.charAt(0).toUpperCase() + word.slice(1) // Capitalize the first character of each word
        )
        .join(' '); // Join the words back into a sentence
});

window.truncateText = window.truncateText || ((text, maxLength) => {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
});

window.underscore = window.underscore || ((text) => text.replace(/\s+/g,'_'));