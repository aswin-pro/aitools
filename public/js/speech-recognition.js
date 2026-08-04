$(document).ready(function () {
    const microphoneBtn = $('#microphone-btn');
    const microphoneIcon = $('#microphone-icon');
    const pauseIcon = $('#pause-icon');
    const messageInput = $('#message');

    const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.lang = navigator.language || 'en-IN';  // Auto-detect language
    recognition.interimResults = true;

    let isListening = false;
    let finalTranscript = '';  // Holds the final transcript

    microphoneBtn.on('click', function (e) {
        e.preventDefault();

        if (isListening) {
            recognition.stop();
            microphoneIcon.show();
            pauseIcon.hide();
            isListening = false;
        } else {
            recognition.start();
            microphoneIcon.hide();
            pauseIcon.show();
            pauseIcon.css('color', '#ff0810');
            isListening = true;
        }
    });

    recognition.onresult = function (event) {
        let interimTranscript = '';

        // Iterate through the event results to accumulate interim and final results
        for (let i = event.resultIndex; i < event.results.length; i++) {
            const transcript = event.results[i][0].transcript;

            if (event.results[i].isFinal) {
                finalTranscript += transcript;
            } else {
                interimTranscript += transcript;
            }
        }

        // Update the message input with the final and interim transcripts
        messageInput.val(finalTranscript + interimTranscript);
    };

    recognition.onend = function () {
        if (isListening) {
            recognition.start();
        } else {
            microphoneIcon.show();
            pauseIcon.hide();
        }
    };

    recognition.onerror = function (event) {
        console.error('Speech recognition error', event.error);
    };
});
