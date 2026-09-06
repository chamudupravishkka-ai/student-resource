<? $apploaded = true; ?>
<!DOCTYPE html>
<html>
<head>
  <?php include "metas.php";?>
  <?php include "head-lib.php";?>

  <style>
    .carddata {
      border-radius: 15px;
    }

    .btn-primary {
      background-color: var(--color-teal);
      border: none;
    }

    .btn-primary:hover {
      background-color: var(--color-orange);
    }

    .btn-outline-danger {
      border-color: var(--color-pink);
      color: var(--color-pink);
    }

    .btn-outline-danger:hover {
      background-color: var(--color-pink);
      color: #fff;
    }

    .upload-icon {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background-color: #f8f9fa;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px auto;
      border: 2px dashed var(--color-teal);
      cursor: pointer;
      transition: 0.3s;
    }

    .upload-icon:hover {
      border-color: var(--color-orange);
      background-color: #fff3e6;
    }
  </style>
</head>
<body>

  
  <? include "navigation.php";?>
<br>
  <div class="container d-flex justify-content-center align-items-center">
    <div class="carddata shadow-lg p-4" style="max-width: 420px; width: 100%;">
      <h3 class="text-center mb-4">Create Account</h3>
      <p id="error" style="color: red;font-size: 13px;">&#160;</p>
     
     
      <div class="upload-icon" onclick="document.getElementById('userIcon').click();">
        <i class="bi bi-person-circle fs-1 text-muted"></i>
      </div>
     

      <form action="registersite.php" method="post">
        <input type="file" id="userIcon" class="d-none" accept="image/*">
        <div class="mb-3">
          <label for="user" class="form-label">Username</label>
          <input type="text" class="form-control" id="user" placeholder="Enter username" name="user" min="3" max="50" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
        </div>

        
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required>
        </div>

       
        <div class="d-grid mb-3">
          <button id="submit" type="button" class="btn btn-primary" onclick="checkvalid()">Register</button>
        </div>
      </form>
<script>
  const fileInput = document.getElementById('userIcon');
if (fileInput.files.length > 0) {
    formData.append('userIcon', fileInput.files[0]);
}
</script>
     
      <div class="text-center my-3">
        <span class="text-muted">or</span>
      </div>

      
      <div class="d-grid">
        <button class="btn btn-outline-danger" onclick="window.location.href='login.php'">
          Login
        </button>
      </div>
    </div>
  </div>
 <script>
  function checkvalid(){
    var ele = document.getElementById('submit');
    let errorMsg = "";
    
    const username = document.getElementById("user").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    
    if (username.length < 4 || username.length > 15) {
      errorMsg += "Username must be 4–15 characters long.<br>";
    }

    
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
      errorMsg += "Please enter a valid email address.<br>";
    }

    
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    if (!passwordPattern.test(password)) {
      errorMsg += "Password must be at least 8 characters, include uppercase, lowercase, and a number.<br>";
    }

    
    if (errorMsg) {
      document.getElementById("error").innerHTML = errorMsg;
    } else {
      document.getElementById("error").innerHTML = "";
      ele.type = "submit";
      ele.submit(); 
    }
  
  }
</script>
<script>
  const fileInput = document.getElementById("userIcon");
  const uploadIcon = document.querySelector(".upload-icon");

  fileInput.addEventListener("change", function () {
    const file = this.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
      
      uploadIcon.innerHTML = "";

      
      const img = document.createElement("img");
      img.src = e.target.result;
      img.style.width = "100%";
      img.style.height = "100%";
      img.style.objectFit = "cover";
      img.style.borderRadius = "50%";

      uploadIcon.appendChild(img);
    };
    reader.readAsDataURL(file);
  });
</script>
  <? include "footer.php"?>
</body>

</html>
