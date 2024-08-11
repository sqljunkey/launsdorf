<!DOCTYPE html>
<html>
<head>
  <title>Chat Room</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php 
  
  // Get user agent and IP address
  $userAgent = $_SERVER['HTTP_USER_AGENT'];
  $ipAddress = $_SERVER['REMOTE_ADDR'];

  // Create a line with user agent and IP address
  $userInfo = "Chat.php User Agent: $userAgent\nIP Address: $ipAddress\n\n";

  // Write the user info to the file
  $result = file_put_contents("visitors.txt", $userInfo, FILE_APPEND);
  if ($result === false) {
     
  } else {
      
  }

?>
  <div class="container">
    <div class="header">
      <h1>Chat Room</h1>
      <div>
	    <img src="launsdorf5.png" alt="My Image">	  
        <a href="index.php">Home</a>
        <span class="divider">|</span>
        <a href="chat.php">Chat</a>
        <span class="divider">|</span>
        <a href="about.php">About</a>
      </div>
    </div>
    <div id="login-container">
      <div id="login-form">
        <h2>Nickname</h2>
        <input type="text" id="username-input" placeholder="Enter your nickname">
        <button id="login-button">Enter</button>
      </div>
    </div>
    <div id="chat-container">
      <div id="information">
        <div id="Welcome">
          <h2>Welcome to Chat Room <span id="username"></span>!</h2>
        </div>
        <div id="user-count">
          <p>Online Users</p>
        </div>
      </div>
      <div id="content">
        <div id="chatbox"></div>
        <div id="sidebar">
          <div id="top-row">
            <div id="users-tab" class="tab">Users</div>
            <div id="dm-tab" class="tab">DM</div>
          </div>
          <div id="bottom-row">
            <div id="users-content" class="tab-content"></div>
            <div id="dm-content" class="tab-content">ddd</div>
          </div>
        </div>
      </div>
      <div id="message-container">
        <input type="text" id="message" placeholder="Enter your message">
        <button id="emoji-button">❤</button>
        <button id="emoji-button2">☺</button>
      </div>
    </div>
  </div>
  <script src="chatscripts.js"></script>
</body>
</html>
