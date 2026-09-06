<div class="container my-5">
  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-body p-4">
      <!-- Header -->
      <h2 class="text-center mb-4 text-primary fw-bold">
        <i class="bi bi-search me-2"></i> Search Engine
      </h2>

      <!-- Search Form -->
      <form method="get" action="search-results.php">
        <div class="row g-3 justify-content-center align-items-center">
         
          <div class="col-12 col-md-9">
            <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
              <span class="input-group-text bg-light text-muted border-0">
                <i class="bi bi-search"></i>
              </span>
              <input class="form-control border-0 bg-light text-dark" type="search" placeholder="Search anything..." aria-label="Search" name="search" value="<?if (isset($_GET['search'])){echo $_GET['search'];}?>">
              
              <button class="btn btn-primary border-0 px-3 px-sm-4" type="submit">
                <i class="bi bi-arrow-right-circle-fill me-sm-1"></i> <span class="d-none d-sm-inline">Search</span>
              </button>
            </div>
          </div>
          <div class="col-12 col-md-3 d-grid">
            <button class="btn btn-outline-secondary btn-lg shadow-sm rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#filterOptions" aria-expanded="false" aria-controls="filterOptions">
              <i class="bi bi-funnel-fill me-2"></i> Filters
            </button>
          </div>
          
        </div>

        <!-- Collapsible Filter Section -->
        <div class="collapse mt-3" id="filterOptions">
          <div class="card card-body bg-light border-0 shadow-sm rounded-3">
            <div class="row g-3">
              <div class="col-12 col-md-4">
                <label class="form-label fw-semibold text-muted small text-uppercase">Category</label>
                <select class="form-select border-0 shadow-sm" name="category">
                  <option value="" selected>All</option>
                  <?
              
              $categories = getData($conn,"categories",["ID","title"],[],"","",0);
              foreach($categories as $c){
                ?>
                <option value="<?echo $c['ID'];?>"><?echo $c['title'];?></option>
                <??>
                  <?}?>
                </select>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label fw-semibold text-muted small text-uppercase">Date Range</label>
                <input type="date" class="form-control border-0 shadow-sm" name="date">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label fw-semibold text-muted small text-uppercase">Sort By</label>
                <select class="form-select border-0 shadow-sm" name="sort">
                  <option value="latest">Latest</option>
                  <option value="name">Name</option>
                </select>
              </div>
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>