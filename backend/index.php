<? 
include "sens/session-check.php";
include "sens/sconn.php";
$apploaded = true; ?>
<!DOCTYPE html>
<html>
<head>
  <?php include "metas.php";?>
  <?php include "head-lib.php";?>
  <style>
    #mainCarousel h2{
      text-shadow: 2px 2px 3px darkblue;
    }
     #mainCarousel p{
      text-shadow: 1px 1px 2px black;
    }
  </style>
</head>
<body>

  
  <? include "navigation.php";?>
  <? include "banner.php";?>
  <? include "search.php";?>
  <h2 class="text-center fw-bold mb-5 text-gradient">For You</h2>
  <? 
  include "top-card.php"?>
  <div class="text-center mt-4">
      <button class="btn btn-outline-primary" onclick="loadDocumentCards();this.style.display='none';">Load more..</button>
      <script>
        // Function to fetch data from PHP and render cards
async function loadDocumentCards() {
  const container = document.getElementById('mainCard');
  container.innerHTML = "";
  
  if (!container) {
    console.error("Target container #hello123 not found!");
    return;
  }

  try {
    const response = await fetch('get-documents.php');
    
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    // 2. Parse the JSON response
    const documents = await response.json();

    // 3. Loop through the array and append cards to the container
    documents.forEach(doc => {
      const cardHTML = `
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
  <div class="card shadow-sm h-100">

    <!-- Icon area with overlays -->
    <div class="position-relative text-center py-4 bg-light">
      <!-- File Icon -->
      <i class="${doc.fileIcon} text-${doc.fileCol}" style="font-size: 70px;"></i>

      <!-- Favourite heart button with data attributes -->
      <button class="btn btn-light position-absolute top-0 end-0 m-2 p-1 rounded-circle" 
              onclick="toggleFavorite(this)"
              data-title="${doc.title}"
              data-url="preview.php?id=${doc.id}"
              data-type="${doc.fileName}"
              data-color="${doc.fileCol}">
        <i class="bi bi-heart"></i>
      </button>

      <!-- Badge -->
      <span class="badge bg-${doc.fileCol} position-absolute top-0 start-0 m-2">${doc.fileName}</span>
    </div>

    <!-- Card body -->
    <div class="card-body d-flex flex-column justify-content-between">
      <div>
        <h6 class="card-title mb-1">${doc.title}</h6>
        <p class="card-text small mb-2 text-muted">${doc.description}</p>
      </div>

      <div>
        <!-- Meta info -->
        <div class="d-flex align-items-center mb-3 mt-2">
          <img src="user-icon/${doc.uploaderAvatar}.webp" class="rounded-circle me-2" width="40" height="40" alt="Avatar">
          <div>
            <small class="text-muted d-block fw-semibold">${doc.uploaderName}</small>
            <small class="text-muted">${doc.uploadDate}</small>
          </div>
        </div>

        <!-- Action button -->
        <a href="preview.php?id=${doc.id}" class="btn btn-sm btn-${doc.fileCol}" target="_blank">Open ${doc.fileName}</a>
      </div>
    </div>

  </div>
</div>
      `;

      // Append card to container
      container.innerHTML += cardHTML;
    });

  } catch (error) {
    console.error("Failed to load cards:", error);
    container.innerHTML = `<div class="col-12 text-danger">Error loading documents. Please try again later.</div>`;
  }
}

// Helper function to prevent XSS attacks when inserting user-generated text
function escapeHtml(text) {
  if (!text) return '';
  return String(text)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}
      </script>
    </div>
  
<div class="container my-5">
  <!-- Title -->
  <h2 class="text-center fw-bold mb-5 text-gradient">🌟 Explore Categories</h2>

  <!-- Category Grid -->
  <div class="row g-4">
    <!-- Technology -->
    
    <?


$data = getData($conn,"categories",["ID","title","description","ficon","col1","col2"],[],"","",4);

//echo json_encode($data);


      foreach($data as $d){
        ?>
        <div class="col-lg-3 col-md-6" onclick="window.location.href='cat.php?cat=<?echo $d['ID'];?>'">
      <div class="category-card text-center p-4 shadow-lg h-100 rounded-4">
        <div class="icon-wrapper"  style="background: linear-gradient(135deg, <?echo $d['col1'];?>,<?echo $d['col2'];?>);">
          <i class="<?echo $d['ficon'];?> fs-1 text-white"></i>
        </div>
        <h5 class="fw-bold"><?echo $d['title'];?></h5>
        <p class="small text-muted"><?echo $d['description'];?></p>
      </div>
    </div>
        <?
      }
    ?>
  </div>
</div>

  <div class="text-center mt-4">
      <a href="all-category.php" class="btn btn-outline-primary">View All</a>
    </div>
    
<script src="js/saved_index.js"></script>
  <? include "footer.php"?>
</body>

</html>
