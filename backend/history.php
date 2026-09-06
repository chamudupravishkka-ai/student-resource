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
  <h2 class="mb-4 text-center">📜 History</h2>

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
              <th scope="col"><input type="checkbox"></th>
              <th scope="col">History Link</th>
              <th scope="col">Type</th>
              <th scope="col">Time</th>
              <th scope="col"><button class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i>
                </button></th>
            </tr>
          </thead>
          <tbody id="savedTableBody">
            
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
  let db;

// 1. Bump the version from 3 to 4 to force the browser to create the store
const request = indexedDB.open('stResource', 5); 

request.onerror = (event) => {
  console.error("Database error:", event.target.errorCode);
};

request.onsuccess = (event) => {
  db = event.target.result; 
  console.log("Database connection established for history view.");
  
  // Fetch and display data immediately once connected
  loadHistoryData();
};

request.onupgradeneeded = (event) => {
  const database = event.target.result;
  
  // Ensure 'saved' store exists
  if (!database.objectStoreNames.contains('saved')) {
    const savedStore = database.createObjectStore('saved', { keyPath: 'id', autoIncrement: true });
    savedStore.createIndex('url', 'url', { unique: true });
  }

  // Ensure 'history' store exists
  if (!database.objectStoreNames.contains('history')) {
    database.createObjectStore('history', { keyPath: 'id', autoIncrement: true });
    console.log("'history' store created successfully!");
  }
};
function loadHistoryData() {
  if (!db) return;

  // Change 'history' below to your specific table name if it's different
  const transaction = db.transaction(['history'], 'readonly');
  const objectStore = transaction.objectStore('history');
  const request = objectStore.getAll(); // Grab all records

  request.onsuccess = (event) => {
    const records = event.target.result;
    const tbody = document.getElementById('savedTableBody');
    tbody.innerHTML = ''; // Clear out placeholder/old rows

    // Handle empty state
    if (records.length === 0) {
      tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-4">No history records found.</td></tr>`;
      return;
    }

    // Loop through records and create the table rows dynamically
    records.forEach(record => {
      // Format the stored date nicely
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
          <button class="btn btn-sm btn-outline-danger" onclick="deleteHistoryRecord(${record.id})">
            <i class="bi bi-trash"></i>
          </button>
        </td>
      `;
      tbody.appendChild(row);
    });
  };

  request.onerror = (event) => {
    console.error('Error loading history records:', event.target.error);
  };
}

// 3. Delete a single record when its trash button is clicked
function deleteHistoryRecord(id) {
  const transaction = db.transaction(['history'], 'readwrite');
  const objectStore = transaction.objectStore('history');
  const request = objectStore.delete(id);

  request.onsuccess = () => {
    console.log(`History record ${id} deleted successfully.`);
    loadHistoryData(); // Refresh the table view
  };

  request.onerror = (event) => {
    console.error('Error deleting record:', event.target.error);
  };
}
</script>
<? include "footer.php";?>
</body>
</html>
<? } ?>