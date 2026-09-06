<?
if($apploaded){
?>

<br>
  <footer class="bg-dark text-light pt-5 pb-4">
    <div class="container">
      <div class="row">

        <!-- About Section -->
        <div class="col-md-3">
          <h5 class="mb-3">About Us</h5>
          <p>We provide a collabarative hub for sharing resources,knowledge access and community connection</p>
        </div>

        <!-- Quick Links -->
        <div class="col-md-3">
          <h5 class="mb-3">Quick Links</h5>
          <ul class="list-unstyled">
            <li><a href="index.php" class="text-light text-decoration-none"><i class="fas fa-angle-right me-2"></i>Home</a></li>
            <li><a href="login.php" class="text-light text-decoration-none"><i class="fas fa-angle-right me-2"></i>Login</a></li>
            <li><a href="register.php" class="text-light text-decoration-none"><i class="fas fa-angle-right me-2"></i>Register</a></li>
            <li><a href="all-category.php" class="text-light text-decoration-none"><i class="fas fa-angle-right me-2"></i>Categories</a></li>
          </ul>
        </div>

        <!-- Contact Info -->
        <div class="col-md-3">
          <h5 class="mb-3">Contact</h5>
          <p><i class="fas fa-map-marker-alt me-2"></i>Colombo,Sri Lanka</p>
          <p><i class="fas fa-phone me-2"></i><a href="tel:0702473866" class="text-light text-decoration-none">0702473866</a> | <a href="tel:0721020034" class="text-light text-decoration-none">0721020034</a> </p>
          <p class="d-flex align-items-start"><i class="fas fa-envelope me-2 mt-1"></i>
          <span class="d-flex flex-column"><a href="mailto:chamudupravishkka@gmail.com" class="text-light text-decoration-none">chamudupravishkka@gmail.com</a><a href="mailto:dushaneranda0803@gmail.com" class="text-light text-decoration-none">dushaneranda0803@gmail.com</a>
          </span></p>
          </div>

        <!-- Social Media -->
        <div class="col-md-3">
          <h5 class="mb-3">Follow Us</h5>
          <a href="#" class="text-light me-3"><i class="fab fa-facebook fa-2x"></i></a>
          <a href="#" class="text-light me-3"><i class="fab fa-twitter fa-2x"></i></a>
          <a href="#" class="text-light me-3"><i class="fab fa-instagram fa-2x"></i></a>
          <a href="#" class="text-light"><i class="fab fa-linkedin fa-2x"></i></a>
        </div>

      </div>

      <hr class="border-light">
      <div class="text-center">
        <p class="mb-0">&copy; 2026 YourWebsite. All rights reserved.</p>
      </div>
    </div>
  </footer>
  <? }else{ header("Location: index.php");} ?>