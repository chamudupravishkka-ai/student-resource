<?
if($apploaded){
?>
<div id="mainCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="ban_img/ban1.webp" class="d-block w-100" alt="First Slide Image">
      <div class="carousel-caption caption-top-left">
        
        <h2 class="fw-bold">Smart Studying Starts Here</h2>
        <p class="lead mb-0">Stop searching, start learning.High-quality study materials crafted for students.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="ban_img/ban2.webp" class="d-block w-100" alt="Second Slide Image">
      <div class="carousel-caption caption-top-left">
        <h2 class="fw-bold">Everything You Need to Ace Your Studies</h2>
        <p class="lead mb-0">Access past papers, lecture notes and essential learning tools all in one place.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="ban_img/ban3.webp" class="d-block w-100" alt="Third Slide Image">
      <div class="carousel-caption caption-top-left">
        <h2 class="fw-bold">Share Resources</h2>
        <p class="lead mb-0">You can share your resources between your frinends.</p>
      </div>
    </div>
  </div>

  <!-- Controls -->
  <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>  
<? }else{ header("Location: index.php");} ?>
