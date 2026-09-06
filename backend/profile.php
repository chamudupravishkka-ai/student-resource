<?
include "sens/session-check.php";
if($allow){
  header("Location: login.php");

}else{
  $apploaded = true;
?><!DOCTYPE html>
<html>
<head>
	<?php include "metas.php";?>
  	<?php include "head-lib.php";?>
    <style>
      .tb{
        transition: 0.2s;
      }
      .tb:hover{
        cursor: pointer;
        scale: 1.01;
        background-color: lightcyan !important;

      }
    </style>
</head>
<body>
	<?php include "navigation.php";?>
  <?
  function formatBytes($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}
function getFolderSize($path) {
    $totalSize = 0;
    $path = realpath($path);

    if ($path !== false && file_exists($path)) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $totalSize += $file->getSize();
            }
        }
    }

    return $totalSize;
}

$folderPath = 'resources/'.$_SESSION['folder']; 
$bytes = getFolderSize($folderPath);
$intStorage = floor(($bytes/1073741824)*100);

  ?>
<div class="container py-5">
  <h2 class="mb-4 text-center fw-bold">📊 My Dashboard</h2>

  <!-- Row 1: Storage + Messages -->
  <div class="row g-4 mb-4">
    <!-- Storage Usage -->
    <div class="col-md-6">
      <div class="p-4 bg-light rounded-4 shadow-sm h-100 d-flex flex-column align-items-center">
        <i class="bi bi-hdd fs-1 text-primary mb-2"></i>
        <h5 class="fw-bold">Storage Usage</h5>
        <div class="progress w-75 my-3" style="height: 20px;">
          <div class="progress-bar bg-primary" role="progressbar" style="width: <?echo $intStorage;?>%;"><?echo $intStorage;?>%</div>
        </div>
        <small class="text-muted">You’ve used <?echo formatBytes($bytes)."/1.00 GB";?> of your storage</small>
      </div>
    </div>

    <!-- Messages -->
    <div class="col-md-6">
      <div class="p-4 bg-light rounded-4 shadow-sm h-100 d-flex flex-column align-items-center">
        <i class="bi bi-envelope fs-1 text-success mb-2" style="cursor: pointer;" onclick="window.location.href='friends.php'"></i>
        <h5 class="fw-bold">Messages</h5>
        <span class="badge bg-danger rounded-pill mb-3" id="msgcount"></span>

        <!-- Quick Actions -->
        <div class="d-flex flex-wrap justify-content-center gap-3">
          <button class="btn btn-outline-primary btn-sm" onclick="window.location.href='saved.php'">
            <i class="bi bi-bookmark"></i> Saved
          </button>
          <button class="btn btn-outline-secondary btn-sm" onclick="window.location.href='history.php'">
            <i class="bi bi-clock-history"></i> History
          </button>
          <button class="btn btn-outline-success btn-sm" onclick="window.location.href='upload.php'">
            <i class="bi bi-upload"></i> Upload Docs
          </button>
          <button class="btn btn-outline-info btn-sm" onclick="window.location.href='acc-change.php'">
            <i class="bi bi-person"></i> Profile
          </button>
        </div>
      </div>
    </div>
  </div>
<script>
  async function messageCount(){
    try{
      const response = await fetch("fetch/chat-count.php");
const data = await response.json();
document.getElementById('msgcount').innerHTML = data.count + " New";
}catch (error) {
    console.log(error);
  }
}
  messageCount();
  setInterval(messageCount,2000);
</script>
  <!-- Row 2: Settings, Help, Analytics, Logout -->
  <div class="row g-4">
    <div class="col-md-3" onclick="window.location.href='settings.php'">
      <div class="tb p-4 bg-light rounded-4 shadow-sm text-center h-100">
        <i class="bi bi-gear fs-2 text-dark mb-2"></i>
        <h6 class="fw-bold">Settings</h6>
      </div>
    </div>
    <div class="col-md-3" onclick="window.location.href='help.php'">
      <div class="tb p-4 bg-light rounded-4 shadow-sm text-center h-100">
        <i class="bi bi-question-circle fs-2 text-dark mb-2"></i>
        <h6 class="fw-bold">Help</h6>
      </div>
    </div>
    <div class="col-md-3" onclick="window.location.href='analytics.php'">
      <div class="tb p-4 bg-light rounded-4 shadow-sm text-center h-100">
        <i class="bi bi-graph-up fs-2 text-dark mb-2"></i>
        <h6 class="fw-bold">Analytics</h6>
      </div>
    </div>
    <div class="col-md-3" onclick="window.location.href='logout.php'">
      <div class="tb p-4 bg-light rounded-4 shadow-sm text-center h-100">
        <i class="bi bi-box-arrow-right fs-2 text-danger mb-2"></i>
        <h6 class="fw-bold">Logout</h6>
      </div>
    </div>
  </div>
</div>
<? include "footer.php";?>
</body>
</html>
<? } ?>