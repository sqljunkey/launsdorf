async function sendPing() {
    try {
        const response = await fetch('pocket_data.txt');
        const text = await response.text();
        const firstLine = text.split('\n')[0];
        updateSignalLabel(firstLine); // Assuming updateSignalLabel accepts a string
    } catch (error) {
        console.error('An error occurred:', error);
    }
}
const pingIntervalId = setInterval(sendPing, 1000);

function updateSignalLabel(content) {
    const signalLabel = document.getElementById('signalLabel');
    signalLabel.textContent = `Signal: ${content}`;
}

// Call the function when the script loads
sendPing();
