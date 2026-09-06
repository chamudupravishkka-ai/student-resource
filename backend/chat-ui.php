<?
include "sens/session-check.php";
if($allow){
  header("Location: login.php");

}else{
  $apploaded = true;
  if(isset($_GET['share'])){
    $share = $_GET['share'];
  }else{
    $share = '';
  }
  if(isset($_GET['title'])){
    $title = $_GET['title'];
  }else{
    $title = '';
  }
?><!DOCTYPE html>
<html lang="en">
<head>
  <?php include "metas.php";?>
  <?php include "head-lib.php";?>
  
  <style>
    .chat-card {
      max-width: 600px;
      margin: 2rem auto;
    }
    .msg-container {
      height: 450px;
      overflow-y: auto;
      scroll-behavior: smooth;
    }
    .msg-bubble {
      max-width: 75%;
      word-wrap: break-word;
    }
    .sendFile{
      color: white;
    }
    .sendFile:visited{
      color: white;
    }
  </style>
</head>
<? include "navigation.php";?>
<body class="bg-light">
  <script>
  function convertId(inputStr) {
    const regex = /^@(\d+)--(.*)$/;
    const match = inputStr.match(regex);
  
    if (!match) return inputStr;
    
    const id = match[1];
    const text = match[2];
    
    // Return the generated HTML code
    return `<a href="preview.php?id=${id}" target="_blank" class="sendFile">${text}</a>`;
}

// Example usage:
const result = convertId("@3--Web based android app");
console.log(result); 
// Output: <a href="preview.php?id=3" class="sendFile">Web based android app</a>
</script>
<?

if(isset($_GET['id'])){
  $frid = intval($_GET['id']) ;
  include "sens/sconn.php";
  $friend = getData($conn,"users",["ID","username","icon"],[$frid,$userid],"ID = ? AND ID != ?","",1);
  if($friend){
  foreach($friend as $f){
    $fid = $f['ID'];
    $fname = $f['username'];
    $flogo = $f['icon'];
  }
}else{
  header("Location: index.php");
}
}else{
  header("Location: index.php");
}

?>

<div class="container">
  <div class="card chat-card shadow-sm">
    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
      <h5 class="mb-0"><img src="user-icon/<?echo $flogo;?>.webp"
      style="border-radius: 50%;width: 45px;height: 45px;" alt="">&#160;<?echo $fname?></h5>
      <small id="status-indicator" class="badge bg-success">Live</small>
    </div>

    <!-- Messages Container -->
    <div class="card-body msg-container d-flex flex-column gap-3 p-3" id="msgBox">
      <?
      $chats = getData(
    $conn,
    "friends",
    ["ID", "userID", "message", "udate"],
    [$userid, $frid, $frid, $userid],
    "((userID = ? AND to_id = ?) OR (userID = ? AND to_id = ?))",
    "udate ASC", 
    100
);
      if($chats){
        //var_dump($chats);
        foreach($chats as $c){
          if($c['userID'] == $userid){
            ?>
 <div class="d-flex justify-content-end">
        <div class="msg-bubble p-3 rounded-3 bg-secondary text-white shadow-sm">
          <script>
            m = '<?echo $c['message']?>';
            if(m[0] == "@"){document.write(convertId(m));}
            else{document.write(m);}
            </script>
          
        </div>
      </div>
    

            <?
          }else {
            ?>
        <div class="d-flex justify-content-start">
        <div class="msg-bubble p-3 rounded-3 bg-primary text-white shadow-sm">
           <script>
            m = '<?echo $c['message']?>';
            if(m[0] == "@"){document.write(convertId(m));}
            else{document.write(m);}
            </script>
        </div>
      </div>
            <?
          }
        }
      }
      ?>
     
    </div>

    <!-- Message Input Bar -->
    <div class="card-footer bg-white border-top p-3">
      <form id="chatForm" class="input-group" onsubmit="event.preventDefault(); sendMsg();">
        <textarea 
          class="form-control" 
          id="prompt" 
          rows="1" 
          placeholder="Type a message..." 
          style="resize: none;"
          onkeydown="if(event.key === 'Enter' && !event.shiftKey){ event.preventDefault(); sendMsg(); }"
        ></textarea>
        <button class="btn btn-primary px-4" type="submit">
          <i class="fa-solid fa-paper-plane"></i>
        </button>
      </form>
    </div>
  </div>
</div>

<script>
  const msgBoxEle = document.getElementById('msgBox');
  const promptInput = document.getElementById('prompt');
   <? if($share != ''){ ?>
    promptInput.value = '<?php echo "@".$share."--".$title;?>';
   <?}?>
  
  let lastMessageId = 0; // Tracks the last retrieved message ID

  // Helper function to append message elements safely
  function appendMessage(text, isSent = false) {
    const wrapper = document.createElement('div');
    wrapper.className = `d-flex justify-content-${isSent ? 'end' : 'start'}`;

    const bubble = document.createElement('div');
    bubble.className = `msg-bubble p-3 rounded-3 shadow-sm ${isSent ? 'bg-secondary' : 'bg-primary'} text-white`;
    bubble.innerHTML = convertId(text); // Safe text handling against XSS

    wrapper.appendChild(bubble);
    msgBoxEle.appendChild(wrapper);

    // Auto-scroll to bottom
    msgBoxEle.scrollTop = msgBoxEle.scrollHeight;
  }

  // Send message function using Fetch API
  async function sendMsg() {
    const text = promptInput.value.trim();
    if (!text) return;

    // Immediately show sent message in UI
    appendMessage(text, true);
    promptInput.value = '';

    const payload = new URLSearchParams();
    payload.append("message", text);

    try {
      const response = await fetch("message-process.php?id=<?echo $frid;?>", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: payload.toString()
      });

      if (!response.ok) throw new Error("Network response was not ok");
      const result = await response.text();
      console.log("Send success:", result);
    } catch (err) {
      console.error("Sending error:", err);
    }
  }

  // Request Polling Function to check for new messages
  async function pollMessages() {
    try {
      const response = await fetch(`message-read.php?id=<?php echo $frid; ?>`);
      if (response.ok) {
        const data = await response.json(); 
        // Expecting JSON format based on database columns: [{ ID: 1, userID: 123, message: "hello", udate: "..." }, ...]
        if (Array.isArray(data) && data.length > 0) {
          data.forEach(msg => {
            appendMessage(msg.message, false); // Using 'message' property from your database
            lastMessageId = msg.ID; // Using 'ID' property from your database
          });
        }
      }
    } catch (err) {
      console.error("Polling error:", err);
    }
  }

  // Poll every 3 seconds
  const POLLING_INTERVAL = 3000;
  setInterval(pollMessages, POLLING_INTERVAL);
</script>
<? include "footer.php"?>
</body>
</html>
<? } ?>