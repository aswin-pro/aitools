function toggleRecording(fieldId) {
    const micIcon = document.getElementById(`microphone-icon-${fieldId}`);
    const pauseIcon = document.getElementById(`pause-icon-${fieldId}`);
    const inputField = document.getElementById(fieldId);

    // Check if browser supports Web Speech API
    if (!('webkitSpeechRecognition' in window)) {
        alert('Your browser does not support speech recognition. Please use Chrome or a compatible browser.');
        return;
    }

    const recognition = new webkitSpeechRecognition(); // Initialize the speech recognition object
    recognition.lang = navigator.language || 'en-IN'; // Set the language (you can adjust as needed)
    recognition.interimResults = false; // Set to false to avoid showing interim results
    recognition.maxAlternatives = 1; // Set to capture only one result


    // console.log("Starting speech recognition for field: ", fieldId);

    // Toggle between microphone and pause icons
    if (micIcon.style.display === 'none') {
        micIcon.style.display = 'block';
        pauseIcon.style.display = 'none';
        recognition.stop(); // Stop recording logic when mic is clicked again
        // console.log("Stopped speech recognition");
    } else {
        micIcon.style.display = 'none';
        pauseIcon.style.display = 'block';
        recognition.start(); // Start recording logic
        // console.log("Started speech recognition");

        // Event when speech is recognized
        recognition.onresult = function (event) {
            const speechToText = event.results[0][0].transcript; // Get the speech transcript
            inputField.value += ' ' + speechToText; // Append the result to the input or textarea
            // console.log("Speech recognized: ", speechToText);
        };

        // Event when the recognition ends
        recognition.onspeechend = function () {
            recognition.stop(); // Stop recognition when user stops speaking
            // console.log("Speech recognition ended");
            micIcon.style.display = 'block'; // Show mic icon again after recording
            pauseIcon.style.display = 'none'; // Hide pause icon
        };

        // Handle errors
        recognition.onerror = function (event) {
            alert(`Speech recognition not supported on this browser.`);
            // micIcon.style.display = 'block'; // Revert back to mic icon if error occurs
            // pauseIcon.style.display = 'none';
            $(".record-btn").hide();
        };
    }
}