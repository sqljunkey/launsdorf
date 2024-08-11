document.addEventListener("DOMContentLoaded", function() {
    const emojiMenu = document.getElementById('emoji-menu');
    const textInput = document.getElementById('text-input');

    // Add event listener to text input for handling emoji insertion
    textInput.addEventListener('input', handleEmojiInsertion);

    // Load the emoji images
    const emojiList = ['😃', '😀', '😁']; // Add more emojis as needed
    emojiList.forEach(emoji => {
        const emojiImage = document.createElement('img');
        emojiImage.src = `emojis/${encodeURIComponent(emoji)}.png`; // Replace "emojis" with the path to your emoji images folder
        emojiImage.alt = emoji;
        emojiImage.addEventListener('click', () => insertEmoji(emoji));
        emojiMenu.appendChild(emojiImage);
    });

    function handleEmojiInsertion() {
        const cursorPosition = textInput.selectionStart;
        const text = textInput.value;
        const updatedText = text.substring(0, cursorPosition) + text.substring(cursorPosition).replace(/:\w+:/g, match => {
            const emojiCode = match.replace(/:/g, '');
            const emojiImage = document.createElement('img');
            emojiImage.src = `jedi/${encodeURIComponent(emojiCode)}.png`; // Replace "emojis" with the path to your emoji images folder
            emojiImage.alt = match;
            emojiImage.width = 20; // Adjust size as needed
            emojiImage.height = 20; // Adjust size as needed
            return emojiImage.outerHTML;
        });
        textInput.value = updatedText;
    }

    function insertEmoji(emoji) {
        textInput.value += emoji;
    }
});
