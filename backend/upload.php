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
</head>
<body>
  <?php include "navigation.php";?>

<div class="container py-5">
  <form action="doc-submit.php" method="post" enctype="multipart/form-data">
  <h2 class="text-center mb-4">📤 Upload Document</h2>
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
          
          <!-- Document Title -->
          <div class="mb-4">
            <label for="docTitle" class="form-label fw-bold">Document Title</label>
            <input type="text" class="form-control" id="docTitle" name="docTitle" placeholder="Enter a clear title for your document">
          </div>

          <!-- Document Description -->
          <div class="mb-4">
            <label for="docDescription" class="form-label fw-bold">Description</label>
            <textarea class="form-control" id="docDescription" name="docDescription" rows="3" placeholder="Provide a brief summary or details about this document..."></textarea>
          </div>

          <!-- Upload Box -->
          <div class="mb-4 text-center">
            <label for="fileUpload" class="form-label fw-bold">Choose File</label>
            <input class="form-control" type="file" id="fileUpload" name="fileUpload" required>
            <small class="text-muted">Supported formats: PDF, DOCX, JPG, PNG</small>
          </div>

          <!-- Select Category -->
          <div class="mb-4">
            <label for="categorySelect" class="form-label fw-bold">Select Category</label>
            <select class="form-select" id="categorySelect" name="category">
              <?
              include "sens/sconn.php";
              $categories = getData($conn,"categories",["ID","title"],[],"","",0);
              foreach($categories as $c){
                ?>
                <option value="<?echo $c['ID'];?>"><?echo $c['title'];?></option>
                <?
              }
              ?>
            </select>
          </div>

          <!-- Create New Category -->
          <div class="mb-4">
            <label for="newCategory" class="form-label fw-bold">Create New Category</label>
            <div class="input-group">
              <input type="text" class="form-control" id="newCategory" name="newCategory" placeholder="Enter new category name">
              <button class="btn btn-outline-primary" type="button">
                <i class="bi bi-plus-circle"></i> Add
              </button>
            </div>
          </div>

          <!-- Submit -->
          <div class="text-center">
            <button class="btn btn-success px-5" type="submit">
              <i class="bi bi-cloud-upload me-2"></i> Upload
            </button>
          </div>

        </div>
      </div>
    </div>
  </div>
  </form>
</div>



<? include "footer.php";?>
</body>
</html>
<? } ?>