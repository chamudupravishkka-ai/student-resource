<?php
include "sens/session-check.php";
if($allow){
  header("Location: login.php");
}else{
  $apploaded = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "metas.php";?>
  <?php include "head-lib.php";?>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <?php include "navigation.php";?>
  <div class="container">

    <h3 class="mb-4 text-center p-3">Analysis Graph</h3>

<a href="profile.php" style="background-color: rgba(0, 0, 0, 0.1);margin-left: auto;" class="btn btn-custom-outline px-3"><i class="bi bi-arrow-left me-1"></i> Back to Dashboard</a>
    <div class="d-flex justify-content-center mb-3">
      <div class="btn-group" role="group">
        

        <input type="radio" class="btn-check" name="graphOption" id="history">
        <label class="btn btn-outline-primary" for="history">History</label>

        <input type="radio" class="btn-check" name="graphOption" id="saved">
        <label class="btn btn-outline-primary" for="saved">Saved</label>
      </div>
    </div>

    <canvas id="analysisChart" height="120"></canvas>
  </div>

  <script>
    let db;
    let chart;
    
    // Global holders for processed chart data
    let storageData = [];
    let historyData = [];
    let savedData = [];

    // 1. Open IndexedDB connection
    const request = indexedDB.open('stResource', 5);

    request.onerror = (event) => {
      console.error("Database error:", event.target.errorCode);
    };

    request.onsuccess = (event) => {
      db = event.target.result;
      console.log("Database connection established for analysis graph.");
      
      // Fetch data from stores and initialize chart
      fetchAllDataAndInitChart();
    };

    request.onupgradeneeded = (event) => {
      const database = event.target.result;
      if (!database.objectStoreNames.contains('saved')) {
        database.createObjectStore('saved', { keyPath: 'id', autoIncrement: true });
      }
      if (!database.objectStoreNames.contains('history')) {
        database.createObjectStore('history', { keyPath: 'id', autoIncrement: true });
      }
    };

    // 2. Fetch data from both stores and aggregate by month
    function fetchAllDataAndInitChart() {
      if (!db) return;

      const historyTransaction = db.transaction(['history'], 'readonly');
      const historyStore = historyTransaction.objectStore('history');
      const historyReq = historyStore.getAll();

      historyReq.onsuccess = (e1) => {
        const rawHistory = e1.target.result;
        historyData = aggregateByMonth(rawHistory);

        // Now fetch saved data
        const savedTransaction = db.transaction(['saved'], 'readonly');
        const savedStore = savedTransaction.objectStore('saved');
        const savedReq = savedStore.getAll();

        savedReq.onsuccess = (e2) => {
          const rawSaved = e2.target.result;
          savedData = aggregateByMonth(rawSaved);
          
          // Estimate storage usage per month based on item counts or payload size approximation
          // (Assuming 1 item roughly uses a baseline KB size, or total combined records)
          storageData = calculateStorageUsage(rawHistory, rawSaved);

          // Initialize the chart with default selection (Storage Usage)
          initChart(storageData, "Storage Usage (MB)", true);
        };
      };
    }

    // Helper: Group records by Month (e.g., "Jan", "Feb")
    function aggregateByMonth(records) {
      const monthCounts = {};
      const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

      records.forEach(record => {
        if (!record.date) return;
        const dateObj = new Date(record.date);
        const monthStr = monthNames[dateObj.getMonth()];

        monthCounts[monthStr] = (monthCounts[monthStr] || 0) + 1;
      });

      // Convert to array format required by Chart.js [{ time: "Jan", count: X }, ...]
      return monthNames
        .filter(m => monthCounts[m] !== undefined)
        .map(m => ({ time: m, count: monthCounts[m] }));
    }

    // Helper: Calculate approximate storage consumption per month
    function calculateStorageUsage(historyRecords, savedRecords) {
      const combined = [...historyRecords, ...savedRecords];
      const monthBytes = {};
      const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

      combined.forEach(record => {
        if (!record.date) return;
        const dateObj = new Date(record.date);
        const monthStr = monthNames[dateObj.getMonth()];

        // Estimate size of record in bytes (JSON string length as a proxy)
        const approxSize = JSON.stringify(record).length;
        monthBytes[monthStr] = (monthBytes[monthStr] || 0) + approxSize;
      });

      return monthNames
        .filter(m => monthBytes[m] !== undefined)
        .map(m => ({ time: m, count: monthBytes[m] })); // count will represent bytes here
    }

    // 3. Initialize Chart.js
    function initChart(dataset, label, convertToMB = false) {
      const ctx = document.getElementById("analysisChart").getContext("2d");
      
      let processedData = dataset.map(d => convertToMB ? (d.count / (1024 * 1024)).toFixed(4) : d.count);

      chart = new Chart(ctx, {
        type: "line",
        data: {
          labels: dataset.map(d => d.time),
          datasets: [{
            label: label,
            data: processedData,
            borderColor: "teal",
            backgroundColor: "rgba(0,128,128,0.2)",
            fill: true
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: { beginAtZero: true }
          }
        }
      });
    }

    // 4. Update existing chart instance
    function updateChart(dataset, label, convertToMB = false) {
      if (!chart) return;
      chart.data.labels = dataset.map(d => d.time);
      chart.data.datasets[0].label = label;
      chart.data.datasets[0].data = dataset.map(d => convertToMB ? (d.count / (1024 * 1024)).toFixed(4) : d.count);
      chart.update();
    }

    // Event Listeners for Radio Buttons
    document.getElementById("storage").addEventListener("change", () => {
      updateChart(storageData, "Storage Usage (MB)", true);
    });

    document.getElementById("history").addEventListener("change", () => {
      updateChart(historyData, "History Count", false);
    });

    document.getElementById("saved").addEventListener("change", () => {
      updateChart(savedData, "Saved Count", false);
    });
  </script>
  <?php include "footer.php"; ?>
</body>
</html>
<?php } ?>