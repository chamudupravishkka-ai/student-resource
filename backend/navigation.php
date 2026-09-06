<?
if($apploaded){

?>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid d-flex justify-content-between align-items-center">

    <!-- Left: Navigation Icon -->
    <button class="btn btn-outline-light me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNav">
      <i class="bi bi-list"></i>
    </button>

    <!-- Center: Site Name -->
    <a class="navbar-brand mx-auto" href="index.php">
      <img src="site-img/logo.png" 
           alt="" class="logo me-2">
      ResourceHub
    </a>
<? if(isset($username)){?>
    <!-- Right: Logged User -->
    <a href="profile.php" class="btn d-flex align-items-center">
      <img src="user-icon/<?echo $userimg;?>.webp" 
           alt="" class="logo me-2"> <?echo $username;?>
      <!--<i class="bi bi-person-circle me-2"></i>-->
      
    </a>
<? }else{ ?>
  <a href="login.php" class="btn d-flex align-items-center">
    <i class="fa fa-user"></i>&#160;
      Login
    </a>
<? } ?>
  </div>
</nav>

<!-- Offcanvas Sidebar -->
<div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="offcanvasNav" data-bs-scroll="true" data-bs-backdrop="true">
  <div class="offcanvas-header">
    
      <? if(isset($username)){?>
        <h5 class="offcanvas-title">
        <img src="user-icon/<?echo $userimg;?>.webp" 
           alt="" class="logo me-2"> <?echo $username;?></h5>
      <? }else{ ?>
        <h5 class="offcanvas-title">
          <i class="fa fa-user"></i>
        <?echo "Guest"?></h5>

      <? } ?>
      
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link " href="index.php"><i class="bi bi-house"></i> Home</a></li>
      <li class="nav-item"><a class="nav-link " href="profile.php"><i class="bi bi-box-arrow-in-right"></i> Dashboard</a></li>
      <li class="nav-item"><a class="nav-link " href="friends.php"><i class="bi bi-chat"></i> Friends</a></li>
      <li class="nav-item"><a class="nav-link " href="upload.php"><i class="bi bi-upload"></i> Upload</a></li>
      <li class="nav-item"><a class="nav-link " href="saved.php"><i class="bi bi-bookmark"></i> Saved</a></li>
      <li class="nav-item"><a class="nav-link " href="history.php"><i class="bi bi-clock-history"></i> History</a></li>
    </ul>
  </div>
</div>
<? }
?>
