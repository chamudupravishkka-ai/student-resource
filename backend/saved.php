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
  <style>
    .history{
      height: 500px;
      overflow-y: auto;
    }
  </style>
<div class="container py-5">
  <!-- Title -->
  <h2 class="mb-4 text-center">📜 Saved</h2>

  <!-- Date Filter -->
  <form class="row g-3 mb-4 justify-content-center">
    <div class="col-md-4">
      <label for="dateFilter" class="form-label fw-bold">Filter by Date</label>
      <input type="date" class="form-control" id="dateFilter" name="dateFilter">
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <button class="btn btn-primary w-100" type="submit">
        <i class="bi bi-filter me-1"></i> Apply
      </button>
    </div>
  </form>

  <!-- History Table -->
  <div class="card shadow-sm history">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr>
            <th scope="col"><input type="checkbox" onclick="actall();"></th>
            <th scope="col">Saved Link</th>
            <th scope="col">Type</th>
            <th scope="col">Time</th>
            <th scope="col"> 
              <button class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash"></i>
              </button>
            </th>
          </tr>
        </thead>
        <tbody id="savedTableBody">
          <!-- Data from IndexedDB will be injected here dynamically -->
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
  let db;

// 1. Open the database connection when the page loads
const request = indexedDB.open('stResource', 5);

request.onerror = (event) => {
  console.error("Database error:", event.target.errorCode);
};

request.onsuccess = (event) => {
  db = event.target.result; 
  console.log("Database connection established for saved view.");
  
  // Fetch and display data immediately once connected
  loadSavedData();
};

// (Note: onupgradeneeded is typically handled where the DB is created, 
// but it's safe to keep an empty check here just in case this file opens first)
request.onupgradeneeded = (event) => {
  const database = event.target.result;
  if (!database.objectStoreNames.contains('saved')) {
    database.createObjectStore('saved', { keyPath: 'id', autoIncrement: true });
  }
};

// 2. Fetch all records from IndexedDB and render them into the table
function loadSavedData() {
  if (!db) return;

  const transaction = db.transaction(['saved'], 'readonly');
  const objectStore = transaction.objectStore('saved');
  const request = objectStore.getAll(); // Grab all records

  request.onsuccess = (event) => {
    const records = event.target.result;
    const tbody = document.getElementById('savedTableBody');
    tbody.innerHTML = ''; // Clear out any placeholder/old rows

    // Handle empty state
    if (records.length === 0) {
      tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-4">No saved items found.</td></tr>`;
      return;
    }

    // Loop through records and create the table rows dynamically
    records.forEach(record => {
      // Format the stored date nicely (e.g., "05 Sep 2026")
      const formattedDate = new Date(record.date).toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
      });

      const row = document.createElement('tr');
      row.innerHTML = `
        <td><input type="checkbox" onclick="delIdadd('${record.id}', this);"></td>
        <td><a href="${record.url}" target="_blank">${record.title}</a></td>
        <td><span class="badge bg-${record.color}">${record.type}</span></td>
        <td>${formattedDate}</td>
        <td>
          <button class="btn btn-sm btn-outline-danger" onclick="deleteRecord(${record.id})">
            <i class="bi bi-trash"></i>
          </button>
        </td>
      `;
      tbody.appendChild(row);
    });
  };

  request.onerror = (event) => {
    console.error('Error loading records:', event.target.error);
  };
}

// 3. Delete a single record when its trash button is clicked
function deleteRecord(id) {
  const transaction = db.transaction(['saved'], 'readwrite');
  const objectStore = transaction.objectStore('saved');
  const request = objectStore.delete(id);

  request.onsuccess = () => {
    console.log(`Record ${id} deleted successfully.`);
    loadSavedData(); // Refresh the table view
  };

  request.onerror = (event) => {
    console.error('Error deleting record:', event.target.error);
  };
}
</script>
</div>
<? include "footer.php";?>
</body>
</html>
<? } ?>