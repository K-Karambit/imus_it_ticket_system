function updateNotificationCount(count) {
    const ticketElement = document.getElementById("new-ticket-count");
    if (!ticketElement) {
        console.error("Element with ID 'new-ticket-count' not found.");
        return;
    }

    const ticketCount = parseInt(ticketElement.innerHTML) || 0; // Default to 0 if empty/NaN
    const newCount = ticketCount + parseInt(count) || 0;
    ticketElement.textContent = newCount;
}




function playNotificationRingtone() {
    const notificationAudio = document.getElementById('notification-audio');
    if (notificationAudio) {
        notificationAudio.play();
    }
}





updateNotificationCount(0);