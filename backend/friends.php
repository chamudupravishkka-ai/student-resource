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
<html>
<head>
  <?php include "metas.php";?>
  <?php include "head-lib.php";?>
</head>
<body>

  
  <? include "navigation.php";?>
  <div class="container-fluid py-3" style="max-width:620px;height: 500px;">
  <!-- Top Search Bar -->
  <div class="container mt-4" style="max-width: 600px;">
  
  <!-- Wrapper needs position-relative so the dropdown list stays anchored to it -->
  <div class="position-relative">
    
    <!-- Search Input Group -->
    <div class="input-group shadow-sm">
      <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        Filter
      </button>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item filter-option" href="#" data-filter="all">All</a></li>
        <li><a class="dropdown-item filter-option" href="#" data-filter="unread">Unread</a></li>
      </ul>
      <input type="text" id="chatSearchInput" class="form-control" placeholder="Search chats..." oninput="fetchChats(this.value)" autocomplete="off">
    </div>

    <!-- Floating Chat List Box (Absolute) -->
    <div id="chatList" class="list-group shadow-lg position-absolute w-100 mt-1 bg-white" style="z-index: 1050; max-height: 350px; overflow-y: auto; display: none;">
      <!-- Dynamic results will be injected here via JavaScript -->
    </div>
  </div>

</div>

<script>
    share = '<?php echo $share;?>';
    title = '<?php echo $title;?>';
function fetchChats(query) {
    let chatList = document.getElementById('chatList');
    
    if (query.trim() === '') {
        chatList.style.display = 'none';
        chatList.innerHTML = '';
        return;
    }

    // Call backend PHP script using Fetch API
    fetch(`search-chats.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            chatList.innerHTML = ''; // Clear previous results
            console.log(data);
            if (data.length > 0) {
                chatList.style.display = 'block';
                
                data.forEach((chat, index) => {
                    let borderClass = index === data.length - 1 ? 'border-0' : 'border-0 border-bottom';
                    
                    let item = `
                        <a href="chat-ui.php?id=${chat.ID}&share=${share}&title=${title}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 chat-item ${borderClass}">
                          <img src="user-icon/${chat.icon}.webp" class="rounded-circle" width="45" height="45" alt="logo">
                          <div class="flex-grow-1">
                            <div class="d-flex w-100 justify-content-between">
                              <h6 class="mb-1 fw-bold text-dark">${chat.username}</h6>
                              <small class="text-muted">User ID: ${chat.ID}</small>
                            </div>
                          </div>
                        </a>
                    `;
                    chatList.innerHTML += item;
                });
            } else {
                chatList.style.display = 'block';
                chatList.innerHTML = `<div class="p-3 text-center text-muted small">No results found</div>`;
            }
        })
        .catch(error => console.error('Error fetching chats:', error));
}

// Hide dropdown when clicking outside
document.addEventListener('click', function(e) {
    let container = document.getElementById('chatList');
    let input = document.getElementById('chatSearchInput');
    if (!container.contains(e.target) && e.target !== input) {
        container.style.display = 'none';
    }
});
</script>

  <!-- Chat List -->
  <div class="list-group" id="chats">
    
</div>
</div>
<script>
  async function loadChats() {

    try {

        const response = await fetch("fetch/sumchats.php");

        if (!response.ok) {
            throw new Error("Failed to load chats");
        }

        const chats = await response.json();

        const chatList = document.getElementById("chats");

        chatList.innerHTML = "";

        chats.forEach(chat => {

            const time = new Date(
                chat.latest_date.replace(" ", "T")
            );

            const formattedTime = time.toLocaleTimeString([], {
                hour: "numeric",
                minute: "2-digit"
            });

            let unreadBadge = "";

            if (Number(chat.unread_count) > 0) {
                unreadBadge =
                    '<span class="badge bg-success rounded-pill">' +
                    chat.unread_count +
                    '</span>';
            }

            chatList.innerHTML +=
                '<a href="chat-ui.php?id=' + chat.id +'&share='+share+'&title='+ title+ '" ' +
                'class="list-group-item list-group-item-action d-flex align-items-center">' +

                    '<img src="user-icon/' + chat.icon + '.webp" ' +
                    'class="rounded-circle me-3" ' +
                    'style="width:45px;height:45px;" alt="">' +

                    '<div class="flex-grow-1">' +

                        '<div class="d-flex justify-content-between">' +

                            '<h6 class="mb-0 fw-bold">' +
                                chat.username +
                            '</h6>' +

                            '<small class="text-muted">' +
                                formattedTime +
                            '</small>' +

                        '</div>' +

                        '<div class="d-flex justify-content-between">' +

                            '<small class="text-muted">' +
                                chat.latest_message +
                            '</small>' +

                            unreadBadge +

                        '</div>' +

                    '</div>' +

                '</a>';

        });

    } catch (error) {

        console.error("Chat loading error:", error);

    }
}

loadChats();

setInterval(loadChats, 2000);
</script>
  <? include "footer.php"?>
  <? // include "chat-ui.php";?>
  
</body>

</html>
<? } ?>