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
      <h3 class="text-center mb-4">Login to Account</h3>
      <p id="error" style="color: red;font-size: 13px;">&#160;</p>
     
      <div class="upload-icon">
        <i class="bi bi-person-circle fs-1 text-muted"></i>
      </div>
      
      <form action="loginsite.php" method="post">
       
        
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" required>
        </div>

        
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" max="200" class="form-control" id="password" placeholder="Enter password" required>
        </div>

       
        <div class="d-grid mb-3">
          <button id="submit" type="button" class="btn btn-primary" onclick="checkvalid()">Login</button>
        </div>
      </form>

     
      <div class="text-center my-3">
        <span class="text-muted">or</span>
      </div>

      
      <div class="d-grid">
        <button class="btn btn-outline-danger" onclick="window.location.href='register.php'">
          New Registration
        </button>
      </div>
    </div>
  </div>
 <script>
  function checkvalid(){
    var ele = document.getElementById('submit');
    let errorMsg = "";
    
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    
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
  <? include "footer.php"?>
</body>

</html>
