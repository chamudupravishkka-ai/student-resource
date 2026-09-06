<? 
include "sens/session-check.php";
$apploaded = true; ?>
<?
if(isset($_GET['search'])){
  $search = $_GET['search'];
}else{
  header("Location: index.php");//?search='' when homepage
}// 1. Build your dynamic parameters and conditions
$search = htmlspecialchars($search ?? '');
$whereClauses = ['(documents.title LIKE ? OR documents.description LIKE ?)'];
$params = ["%" . $search . "%", "%" . $search . "%"];

// Optional Category Filter
if (isset($_GET['category']) && is_numeric($_GET['category'])) {
    $whereClauses[] = 'documents.cat_id = ?';
    $params[] = intval($_GET['category']);
}

// Optional Date Filter
if (!empty($_GET['date'])) {
    $whereClauses[] = 'documents.date LIKE ?';
    $params[] = $_GET['date'] . '%';
}

// Just the WHERE clause (no ORDER BY here, because Argument 6 handles WHERE)
$whereString = implode(' AND ', $whereClauses);

// 2. Safe Sorting Whitelist (This goes into Argument 7 as a STRING)
$allowedSorts = [
    'latest' => 'documents.date DESC',
    'name'   => 'documents.title DESC'
];
$sortString = $allowedSorts[$_GET['sort'] ?? ''] ?? 'documents.date DESC';
?>
<!DOCTYPE html>
<html>
<head>
  <?php include "metas.php";?>
  <?php include "head-lib.php";?>
</head>
<body>

  
  <?php include "navigation.php";?>
  <? include "sens/sconn.php";?>
  <?include "search.php";?>
  <div class="container">
    <h1 class="display-6">Search result to: <span style="color: hotpink;"><? echo $search;?></span></h1>
  </div>
  

  <div class="container my-5" >
  
  <div class="row g-4" id="mainCard">
    <!-- Card -->

    <?
   include "sens/data-type.php";
// 3. Call the function matching the exact argument signature
$cards = getDataWithJoins(
    $conn,
    'documents',
    ['documents.*', 'users.username', 'users.icon'],
    [
        'INNER JOIN users ON documents.user_id = users.ID'
    ],
    $params,      // Arg 5: array ($params)
    $whereString, // Arg 6: string ($where)
    $sortString,  // Arg 7: string ($orderBy) -> MUST BE A STRING
    8             // Arg 8: int ($limit)
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
        <img src="user-icon/<? echo $c['icon']; ?>.webp" class="rounded-circle me-2" width="40" alt="U">
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
    }if(!$cards){
      ?> 
      <h1 align="center">No Results can found</h1>

      <?
    }

    ?>
  </div>
</div>

<script src="js/saved_index.js"></script>
  <? include "footer.php";?>
  
</body>

</html>
