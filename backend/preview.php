<?php 
include "sens/session-check.php";

if ($allow) {
    header("Location: login.php");
    exit();
}

$apploaded = true;
include "sens/data-type.php";
// 1. Fetch document data if ID is provided
if (isset($_GET['id'])) {
    $fileId = $_GET['id'];
    include "sens/sconn.php";
    $data = [];
    
    $sql = "SELECT 
                d.title AS doc_title,
                d.description AS doc_description,
                c.title AS category_name,
                d.url AS doc_url,
                d.date AS uploaded_date,
                u.icon AS user_icon,
                u.username AS user_name,
                u.folder AS user_folder
            FROM documents d
            LEFT JOIN categories c ON d.cat_id = c.ID
            LEFT JOIN users u ON d.user_id = u.ID 
            WHERE d.ID = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $fileId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
    } else {
        echo "Prepare failed: " . $conn->error;
    }
    
    $conn->close();
}
$realpath = 'resources/'.$data[0]['user_folder'].'/'.$data[0]['doc_url'];
$secret = "my-secret-key-123";

$encrypted = openssl_encrypt(
    $realpath,
    "AES-256-CBC",
    $secret,
    0,
    $iv = random_bytes(16)
);

$token = base64_encode($iv . "::" . $encrypted);

 function formatBytes($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include "metas.php";?>
    <?php include "head-lib.php";?>

    <style>
        .doc-preview-box {
            background-color: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .publisher-avatar {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>

  <?php include "navigation.php";?>

    <div class="container my-5">
        <!-- Main Document Section -->
         <h2 class="fw-bold text-dark mb-2"><?php echo $data[0]['doc_title'] ?? 'Untitled Document'; ?></h2>
        <div class="card shadow-sm p-4 mb-5 bg-white rounded">
            <div class="row g-4">
                
                <!-- Document Preview Box -->
                <div class="col-md-4">
                    <div class="doc-preview-box text-muted">
                        
                        <div class="text-center">
                            <i class="<?echo $types[filturl($realpath)][2]?> fs-1 d-block mb-2 text-<?echo $types[filturl($realpath)][0]?>"></i>
                            <span>Document Preview</span>
                        </div>
                    </div>
                </div>

                <!-- Document Details -->
                <div class="col-md-8 d-flex flex-column justify-content-between">
                    <div>
                        <!-- Document Description -->
                        <p class="text-muted mb-4">
                            <?php echo $data[0]['doc_description'] ?? 'No description available.'; ?>
                        </p>

                        <!-- Metadata Badges -->
                        <div class="row g-2 mb-4">
                            <div class="col-6 col-sm-4 col-md-3">
                                <small class="text-uppercase text-muted d-block small-tracking">Category</small>
                                <span class="badge bg-info text-dark"><?php echo $data[0]['category_name'] ?? 'General'; ?></span>
                            </div>
                            <div class="col-6 col-sm-4 col-md-3">
                                <small class="text-uppercase text-muted d-block small-tracking">Publish Type</small>
                                <span class="badge bg-secondary">Public Access</span>
                            </div>
                            <div class="col-6 col-sm-4 col-md-3">
                                <small class="text-uppercase text-muted d-block small-tracking">File Type</small>
                                <span class="fw-semibold text-dark"><i class="<?echo $types[filturl($realpath)][2]?> text-<?echo $types[filturl($realpath)][0]?> me-1"></i><?echo $types[filturl($realpath)][1]?></span>
                            </div>
                            <div class="col-6 col-sm-4 col-md-3">
                                <small class="text-uppercase text-muted d-block small-tracking">File Size</small>
                                <span class="fw-semibold text-dark"><? echo formatBytes(filesize($realpath));?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons & Date -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between pt-3 border-top gap-3">
                        <div class="text-muted">
                            <small><i class="bi bi-calendar3 me-1"></i> Uploaded: <strong><?php echo $data[0]['uploaded_date'] ?? 'N/A'; ?></strong></small>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary d-flex align-items-center gap-2" onclick="window.location.href='friends.php?share=<? echo $fileId;?>&title=<? echo $data[0]['doc_title'] ?? 'Untitled Document';?>'">
                                <i class="bi bi-share"></i> Share
                            </button>
                            <button class="btn btn-primary d-flex align-items-center gap-2" onclick="downloadfile()">
                                <i class="bi bi-cloud-arrow-down-fill"></i> Download File
                                <a href="download.php?token=<?php echo urlencode($token)?>" hidden id="downID" download="<?php echo $data[0]['doc_title'] ?? 'Untitled Document'; ?>"></a>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            <script>
                function downloadfile(){
                    document.getElementById('downID').click();
                }
            </script>
            <!-- Publisher Info -->
            <div class="d-flex align-items-center mb-3 mt-4">
                <img src="user-icon/<?php echo $data[0]['user_icon'] ?? 'default'; ?>.webp" alt="Publisher Icon" class="publisher-avatar me-2">
                <div>
                    <span class="fw-semibold d-block text-secondary text-sm">Published by</span>
                    <span class="fw-bold d-block text-primary"><?php echo $data[0]['user_name'] ?? 'Unknown'; ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Similar Documents Section -->
    <h1 class="h3 fw-bold mb-4 text-dark tracking-wide" align="center">Similar documents for you</h1>
    
    <?php //include "top-card.php"; ?>
       
    <?php include "footer.php"; ?>

    <!-- IndexedDB History Logger Script -->
    <script>
    console.log("1. History script tag loaded into page.");

    const historyRecord = {
        title: "<?php echo addslashes($data[0]['doc_title'] ?? ''); ?>",
        url: window.location.href,
        type: "<?php echo htmlspecialchars($data[0]['category_name'] ?? 'Document', ENT_QUOTES); ?>",
        color: "primary", 
        date: new Date()
    };

    const historyRequest = indexedDB.open('stResource', 5);

    historyRequest.onerror = (event) => {
        console.error("2. Database failed to open! Error code:", event.target.errorCode);
    };

    historyRequest.onsuccess = (event) => {
        console.log("2. Database connection success!");
        const db = event.target.result;
        
        if (db.objectStoreNames.contains('history')) {
            console.log("3. 'history' store found. Attempting to add record...");
            
            const transaction = db.transaction(['history'], 'readwrite');
            const store = transaction.objectStore('history');
            const request = store.add(historyRecord);
            
            request.onsuccess = () => {
                console.log("4. History added successfully! Record:", historyRecord);
            };
            
            request.onerror = (e) => {
                console.error("4. Error inside store.add:", e.target.error);
            };
        } else {
            console.warn("3. WARNING: 'history' store DOES NOT EXIST in the database yet!");
        }
    };

    historyRequest.onupgradeneeded = (event) => {
        console.log("UPGRADE NEEDED triggered.");
        const db = event.target.result;
        if (!db.objectStoreNames.contains('history')) {
            db.createObjectStore('history', { keyPath: 'id', autoIncrement: true });
            console.log("'history' store created successfully via upgrade.");
        }
    };
    </script>
</body>
</html>