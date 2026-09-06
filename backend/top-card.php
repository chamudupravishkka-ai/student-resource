<div class="container my-5" >
  
  <div class="row g-4" id="mainCard">
    <!-- Card -->

    <?
   include "sens/data-type.php";

    $cards = getDataWithJoins($conn,'documents',['documents.*', 'users.username','users.icon'],[
        'INNER JOIN users ON documents.user_id = users.ID'
    ],[],'','documents.date DESC',8
);

    foreach($cards as $c){
      $shortText = mb_substr($c['description'], 0, 200) . (mb_strlen($c['description']) > 200 ? '...' : '');
?>

<div class="col-lg-3 col-md-4 col-sm-6">
  <div class="card shadow-sm h-100">

    <!-- Icon area with overlays -->
    <div class="position-relative text-center py-4 bg-light">
      <!-- Large PDF icon -->
      <i class="<? echo $types[filturl($c['url'])][2]; ?> text-<? echo $types[filturl($c['url'])][0]; ?>" style="font-size: 70px;"></i>

      <!-- Favourite heart (UPDATED with onclick and data attributes) -->
      <button class="btn btn-light position-absolute top-0 end-0 m-2 p-1 rounded-circle" 
              onclick="toggleFavorite(this)"
              data-title="<? echo htmlspecialchars($c['title'], ENT_QUOTES); ?>"
              data-url="preview.php?id=<? echo $c['ID']; ?>"
              data-type="<? echo $types[filturl($c['url'])][1]; ?>"
              data-color="<? echo $types[filturl($c['url'])][0]; ?>">
        <i class="bi bi-heart"></i>
      </button>

      <!-- Badge -->
      <span class="badge bg-<? echo $types[filturl($c['url'])][0]; ?> position-absolute top-0 start-0 m-2">
        <? echo $types[filturl($c['url'])][1]; ?>
      </span>
    </div>

    <!-- Card body -->
    <div class="card-body">
      <h6 class="card-title mb-1"><? echo $c['title']; ?></h6>
      <p class="card-text small mb-2" style="height: 147px;"><? echo $shortText; ?></p>

      <!-- Meta info -->
      <div class="d-flex align-items-center mb-2">
        <img src="user-icon/<? echo $c['icon']; ?>.webp" class="rounded-circle me-2" width="40" height="40" alt="U">
        <div>
          <small class="text-muted"><? echo $c['username']; ?></small><br>
          <small class="text-muted"><? echo $c['date']; ?></small>
        </div>
      </div>

      <!-- Action button -->
      <a href="preview.php?id=<? echo $c['ID']; ?>" class="btn btn-sm btn-<? echo $types[filturl($c['url'])][0]; ?>">
        Open <? echo $types[filturl($c['url'])][1]; ?>
      </a>
    </div>

  </div>
</div>

<?
    }

    ?>
  </div>
</div>
