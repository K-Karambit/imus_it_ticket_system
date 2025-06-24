const webKey = "9MFMzNg5YeY7Sp7HdpMpqpBE13ujNPmv74FSXQhbSectqMYfgkCA8qNUKgjx6OgZpasbM9F7OCMrf8rz31dpQLCPRuOO6CTncDfiVJ9PfrSSgSaEyd2kJNkBqq9BYbbw";
let websocket = null;
let message = null;
let reconnectInterval = null;

function connectWebSocket() {
  websocket = new WebSocket(`ws://172.16.200.245:5500?key=${webKey}`);

  websocket.addEventListener("open", () => {
    message = "WebSocket connection opened";
    console.log(message);
    if (reconnectInterval) {
      clearInterval(reconnectInterval);
      reconnectInterval = null;
    }
  });

  websocket.addEventListener("error", (error) => {
    message = "WebSocket error. Attempting to reconnect...";
    console.error(message, error);
  });

  websocket.addEventListener("close", () => {
    message = "WebSocket connection closed. Attempting to reconnect...";
    console.warn(message);
    startReconnectInterval();
  });

  websocket.addEventListener("message", (event) => {
    try {
      const data = JSON.parse(event.data);
      if (data.sid == 1) {
        if (data.action === 'notification') {
          getNewTickets();
        }
      }
      console.log(data)
    } catch (error) {
      console.error("Error parsing message:", event.data);
    }
  });
}

function startReconnectInterval() {
  if (!reconnectInterval) {
    reconnectInterval = setInterval(() => {
      if (!websocket || websocket.readyState === WebSocket.CLOSED) {
        console.log("Reconnecting WebSocket...");
        connectWebSocket();
      }
    }, 5000);
  }
}


function sendWebsocketMessage(message) {
  websocket.send(message);
}


connectWebSocket();

