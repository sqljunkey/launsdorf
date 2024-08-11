const chatbox = document.getElementById("chatbox");
const messageInput = document.getElementById("message");
const loginContainer = document.getElementById("login-container");
const loginForm = document.getElementById("login-form");
const usernameInput = document.getElementById("username-input");
const loginButton = document.getElementById("login-button");
const chatContainer = document.getElementById("chat-container");
const usernameSpan = document.getElementById("username");
const usersTab = document.getElementById("users-tab");
const dmTab = document.getElementById("dm-tab");
const usersContent = document.getElementById("users-content");
const dmContent = document.getElementById("dm-content");
const rowElement = document.getElementById("bottom-row");
const usersTab1 = document.getElementById("users-tab");
const dmTab1 = document.getElementById("dm-tab");
const usersContent1 = document.getElementById("users-content");
const dmContent1 = document.getElementById("dm-content");
var emojiButton = document.getElementById("emoji-button");
var emojiMenu = document.getElementById("emoji-menu");


const baseUrl = "http://launsdorf.com:8000";

let blinkIntervalID;
let isTrue = true;
let pingIntervalId; // Holds the interval ID for ping messages

var chatEnvironment="main-chat";

// Get the username from the query parameter in the URL
const urlParams = new URLSearchParams(window.location.search);
//const username = urlParams.get("username");
//usernameSpan.textContent = username;


function hideLogin(username) {
  loginContainer.style.opacity = "0";
  setTimeout(() => {
    loginContainer.style.display = "none";
    chatContainer.style.opacity = "1";
    chatContainer.style.filter = "none";
  }, 500);

  // Set the username in the chat container
  usernameSpan.textContent = username;
}

 // Login event Listener
usernameInput.addEventListener("keydown", (event) => {
  if (event.key === "Enter") {
    event.preventDefault();
    const username = usernameInput.value;
    if (username.trim() !== "") {
      hideLogin(username);
    }
  }
});

//Button Clicked
loginButton.addEventListener("click", () => {
  const username = usernameInput.value;
  if (username.trim() !== "") {
    hideLogin(username);
  }
});





function sendMessage() {
  const message = messageInput.value;

  if (message.trim() !== "") {
    const username = usernameSpan.textContent;
    

    // Create a data object to send in the AJAX request
    const data = {
      username: username,
      message: message,
      chatEnvironment: chatEnvironment
    };

    // Make an AJAX request to send the message to the server
    const xhr = new XMLHttpRequest();
    xhr.open("POST", baseUrl+"/send_message", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.send(JSON.stringify(data));

    messageInput.value = "";
  }

  retrieveMessages();
}



// Event listener for pressing Enter key in the input field
messageInput.addEventListener("keydown", (event) => {
  if (event.key === "Enter") {
    sendMessage();
  }
});


// Send ping message to the server
function sendPing() {
  const username = usernameSpan.textContent;
  if (username.trim() !== "") {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", baseUrl+"/ping", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
      if (xhr.status === 200) {
        console.log(xhr.responseText);
      }
    };
    xhr.send(`username=${encodeURIComponent(username)}`);
  }
}



// Start sending ping messages every 1 seconds
function startPing() {
  pingIntervalId = setInterval(sendPing, 1000);
}

// Stop sending ping messages
function stopPing() {
  clearInterval(pingIntervalId);
}

function retrieveMessages() {
  const xhr = new XMLHttpRequest();
  const url = baseUrl+'/retrieve_messages';

  xhr.open('POST', url, true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        displayMessages(response.messages);
       
      } else {
        console.log('Error retrieving messages: ' + xhr.status);
      }
    }
  };

  const params = 'chatEnvironment=' + encodeURIComponent(chatEnvironment);
  xhr.send(params);
}







function displayMessages(messages) {
  const shouldScrollToBottom = chatbox.scrollTop + chatbox.clientHeight === chatbox.scrollHeight;

  chatbox.innerHTML = "";
  messages.forEach((message) => {
    const messageElement = document.createElement("p");

    // Show the timestamp based on elapsed time
    const elapsedSeconds = message.elapsedTime; // Assuming the server returns the elapsed time in seconds
    const daysAgo = Math.floor(elapsedSeconds / (24 * 60 * 60));
    const currentDate = new Date();
    const messageDate = new Date(currentDate - elapsedSeconds * 1000);
   
    // Show the timestamp in the user's local time zone
    const timestampElement = document.createElement("span");
    let timestampText = "";

    if (daysAgo === 0) {
            
      timestampText = messageDate.toLocaleTimeString("en-US", { hour: "2-digit", minute: "2-digit", hour12: false });
    } else {
      // Display the "Days Ago" feature for messages older than 1 day
      timestampText = `${daysAgo} day${daysAgo === 1 ? '' : 's'} ago`;
    }

    timestampElement.textContent = timestampText;

    // Set the timestamp color to gray
    timestampElement.style.color = "gray";
    messageElement.appendChild(timestampElement); // Append the timestamp before the username

    // Show the username and message content
    const messageContent = document.createElement("span");
    messageContent.textContent = ` <${message.username}>: `;
    messageContent.classList.add("message-content"); // Add CSS class for styling

    messageElement.appendChild(messageContent);

    // Set the message content color to black
    const messageText = document.createElement("span");
    messageText.textContent = message.message;
    messageText.classList.add("message-text");
    messageElement.appendChild(messageText);


    chatbox.appendChild(messageElement);
  });

  if (shouldScrollToBottom) {
    chatbox.scrollTop = chatbox.scrollHeight; // Auto-scroll to the latest message
  }
}









function retrieveUserList() {
  const xhr = new XMLHttpRequest();
  xhr.open("POST",baseUrl+"/retrieve_users", true);
  xhr.setRequestHeader("Content-Type", "application/json");

  const username = usernameSpan.textContent;
  const data = {
    username: username,
    chatEnv: chatEnvironment
  };

  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        const usersData = JSON.parse(xhr.responseText);
        const userList = document.getElementById("bottom-row");
        userList.style.overflow= "scroll";
        userList.innerHTML = ""; // Clear existing user list

        let count = 0;
        usersData.forEach((user) => {
          if (chatEnvironment === "dm-chat") {
            chatEnvironment = combineStrings(usernameSpan.textContent,user.username);
            sendOpenWindowRequest();
          }else {
          const listItem = document.createElement("li");
          const bulletLink = document.createElement("a");
          bulletLink.textContent = user.username;
          bulletLink.href = "#"; // Set the link destination if needed
          bulletLink.addEventListener("click", () => handleUserClick(user.username)); // Add click event listener
          listItem.appendChild(bulletLink);
          listItem.addEventListener("contextmenu", (event) => handleContextMenu(event, user.username)); // Add context menu event listener
          userList.appendChild(listItem);
          count++;
          }
        });

        const userCount = document.getElementById("user-count");
        
        if(chatEnvironment==="main-chat"){
        userCount.textContent = `Users Online (${count})`;
        }else{
        userCount.textContent = `Direct Messages (${count})`;
        }

      }
    }
  };

 console.log(data);
  xhr.send(JSON.stringify(data)); // Send the request with the JSON payload containing chatEnvironment
}




     
// Function to handle user click event
function handleUserClick(user) {
  // Code to handle the user click event
  if(chatEnvironment==="main-chat"){


  }else{
    chatEnvironment = combineStrings(usernameSpan.textContent,user);
  }
  console.log(`User clicked: ${user}`);
}

// Function to handle context menu event
function handleContextMenu(event, user) {
  event.preventDefault(); // Prevent default right-click context menu

  // Create the custom context menu
  const contextMenu = document.createElement("div");
  contextMenu.classList.add("context-menu");
  contextMenu.innerHTML = `
    <ul>
      <li>Direct Message</li>
      <li>View Profile</li>
    </ul>
  `;

  // Position the context menu based on the mouse pointer
  contextMenu.style.left = event.clientX + "px";
  contextMenu.style.top = event.clientY + "px";

  // Add the context menu to the page
  document.body.appendChild(contextMenu);
  // Handle option selection
  const options = contextMenu.querySelectorAll("li");
  options.forEach((option) => {
    option.addEventListener("click", () => handleContextMenuOption(option.innerText, user));
  });

  // Remove the context menu when clicked outside of it
  const removeContextMenu = () => {
    document.body.removeChild(contextMenu);
    document.removeEventListener("click", removeContextMenu);
  };
  setTimeout(() => {
    document.addEventListener("click", removeContextMenu, { once: true });
  }, 0);
}

// Function to handle context menu option selection
function handleContextMenuOption(option, user) {
  // Code to handle the selected context menu option
  console.log(`Selected option "${option}" for user: ${user}`);

  if (option === "Direct Message") {
    
    rowElement.innerHTML = "";
    chatEnvironment =combineStrings(usernameSpan.textContent,user);
    retrieveMessages();
    sendOpenWindowRequest();
    //clearInterval(id_interval);
    console.log(chatEnvironment);

  } else if (option === "View Profile") {

  }
}

function checkActivity() {
  const url = baseUrl+'/check_activity';
  const username = usernameSpan.textContent;
  const data = JSON.stringify({ username });

  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: data
  })
  .then(response => response.json())
  .then(data => {

    
    var isNewMessage = false;
    
    data.forEach((chatEnvironment) => {
      if(chatEnvironment.newMessage===true){
        if (chatEnvironment.chatEnvironment !== chatEnvironment){

         isNewMessage = true;

        }

      }});    
      
    if(isNewMessage){
        toggleChatTab(true);
    }else{
        toggleChatTab(false);
    }
    console.log('Response from server:', data);
    // Handle the response from the server here if needed
  })
  .catch(error => {
    console.error('Error sending POST request:', error);
    // Handle any errors that occurred during the POST request
  });
}




function toggleChatTab(toggleOn) {
  const chatTab = document.getElementById('dm-tab'); // Replace 'dm-tab' with the actual ID of the DM tab element

  if (toggleOn) {

   
    if(chatTab.style.color === 'black'){
      isBlack = true;
    }else{
      isBlack = false;
    }
 
      isBlack = !isBlack;
      const color = isBlack ? 'black' : 'gray'; // Toggle text color between black and gray
      chatTab.style.color = color; 
    
  } else  {


    chatTab.style.color = 'black'; 
  }
}








function sendOpenWindowRequest() {
  const url = baseUrl+'/open_window';
  const username = usernameSpan.textContent;
  const data = JSON.stringify({ username, chatEnvironment });

  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: data
  })
  .then(response => response.json())
  .then(data => {
    console.log('Response from server:', data);
    // Handle the response from the server here if needed
  })
  .catch(error => {
    console.error('Error sending POST request:', error);
    // Handle any errors that occurred during the POST request
  });
}


// Function to remove the context menu
function removeContextMenu(event) {
  const contextMenu = document.querySelector(".context-menu");
  if (contextMenu && !contextMenu.contains(event.target)) {
    document.body.removeChild(contextMenu);
    document.removeEventListener("click", removeContextMenu);

  }
}


// Combine Strings
function combineStrings(str1, str2) {
  const sortedStrings = [str1, str2].sort((a, b) => a.localeCompare(b));
  return `${sortedStrings[0]}-${sortedStrings[1]}`;
}





usersTab1.addEventListener("click", () => {
chatEnvironment ="main-chat";
id_interval=retrieveUserList();
retrieveMessages();
console.log(chatEnvironment);
});

dmTab1.addEventListener("click", () => {
 //rowElement.innerHTML = "New content for DM tab";
 
chatEnvironment ="dm-chat";
id_interval=retrieveUserList();
 retrieveMessages();
 //clearInterval(id_interval);

});

emojiButton.addEventListener("click", function () {
  emojiMenu.style.display = "block";
});

function addEmoji(emoji) {
  var textarea = document.getElementById("textarea-id"); // Replace with your textarea ID
  textarea.value += emoji;
  emojiMenu.style.display = "none";
}
// Initial retrieval of messages
retrieveMessages();
refreshchat_id=setInterval(retrieveMessages, 1000); 


// Initial retrieval of users
checkActivity();
checkActivity_id=setInterval(checkActivity, 5000);

// Retrieve user list initially and every 1 seconds
retrieveUserList();
id_interval=setInterval(retrieveUserList, 5000); 	

// Event listener for window unload (user leaves the page)
window.addEventListener("unload", () => {
  stopPing(); // Stop sending ping messages when the user leaves the page
});

// Start sending ping messages
startPing();
