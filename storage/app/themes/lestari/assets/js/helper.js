const capitalizeFirstCharacterOfEachWord = (sentence) => {
    return sentence
        .split(' ') // Split the sentence into words
        .map(word => 
            word.charAt(0).toUpperCase() + word.slice(1) // Capitalize the first character of each word
        )
        .join(' '); // Join the words back into a sentence
}

const truncateText = (text, maxLength) => {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
};

const underscore = (text) => text.replace('\s+','_')