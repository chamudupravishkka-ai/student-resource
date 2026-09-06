<? 
include "sens/session-check.php";
$apploaded = true; ?>
<!DOCTYPE html>
<html>
<head>
  <?php include "metas.php";?>
  <?php include "head-lib.php";?>
</head>
<body>

  
  <? include "navigation.php";?>
  
<div class="container my-5">
  <!-- Title -->
  <h2 class="text-center fw-bold mb-5 text-gradient">🌟 All Categories</h2>

  <!-- Category Grid -->
  <div class="row g-4">
    <!-- Technology -->
    
    <?
include "sens/sconn.php";

$data = getData($conn,"categories",["ID","title","description","ficon","col1","col2"]);

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

  <? include "footer.php"?>
  
</body>

</html>
