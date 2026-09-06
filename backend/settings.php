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

    <style>
        h2{
            font-family:BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color:rgb(35, 113, 243)
        }
        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #111827;
        }

        /* Matching Dashboard Wireframe Card Style */
        .settings-card {
            background-color: #ffffff;
            border: 1.5px solid rgb(35, 113, 243);
            border-radius: 16px;
            padding: 1.5rem;
            height: 100%;
            transition: transform 0.2s ease;
        }

        .section-title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgb(35, 113, 243);
        }

        /* Status Badge */
        .status-dot {
            height: 10px;
            width: 10px;
            background-color: #22c55e;
            border-radius: 50%;
            display: inline-block;
        }

        /* Custom Form Controls */
        .form-check-input:checked {
            background-color: #111827;
            border-color: #111827;
        }


        .btn-custom-outline {
            border: 1.5px solid #111827;
            color: #111827;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .btn-custom-outline:hover {
            background-color: #111827;
            color: #ffffff;
        }

        .quick-link-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            color: #111827;
            text-decoration: none;
            margin-bottom: 0.5rem;
            transition: background 0.2s;
        }

        .quick-link-item:hover {
            background-color: #f3f4f6;
            color: #111827;
        }
      
    </style>
</head>
<body>

  
  <? include "navigation.php";?>
  <div class="container py-5">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="fw-bold m-0"><i class="bi bi-gear me-2"></i>Settings</h2>
            <a href="profile.php" class="btn btn-custom-outline px-3"><i class="bi bi-arrow-left me-1"></i> Back to Dashboard</a>
        </div>

        <div class="row g-4">
            
            <!-- 1. Account Details -->
            <div class="col-lg-6">
                <div class="settings-card">
                    <div class="section-title">
                        <i class="bi bi-person-circle fs-4"></i> Account Details
                    </div>
                    <form>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">FULL NAME</label>
                            <input type="text" class="form-control" value="<?echo $username;?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">EMAIL ADDRESS</label>
                            <input type="email" class="form-control" value="<?echo $email;?>">
                        </div>
                        <button type="button" class="btn btn-custom-outline btn-sm" onclick="window.location.href='updatelog.php'">Update Details</button>
                    </form>
                </div>
            </div>

            <!-- 2. System & Connection Status (Online Status, Browser, Net Speed) -->
            <div class="col-lg-6">
                <div class="settings-card">
                    <div class="section-title">
                        <i class="bi bi-laptop fs-4"></i> System & Network
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <span class="fw-medium">Online Status</span>
                        <span class="badge bg-light text-dark border d-flex align-items-center gap-2 px-3 py-2">
                            <span class="status-dot"></span> <span id="online-txt">Online</span>
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <span class="fw-medium">Browser Info</span>
                        <span class="text-muted small" id="browserInfo"></span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-medium">Network Speed</span>
                        <span class="badge bg-primary text-white px-3 py-2" id="netSpeed">Checking...</span>
                    </div>
                </div>
            </div>

            <!-- 3. Privacy & History (History Mode, Clear Cookies) -->
            <div class="col-lg-6">
                <div class="settings-card">
    <div class="section-title">
        <i class="bi bi-device-hdd fs-4"></i>
        Device Storage
    </div>

    <div class="d-flex justify-content-between border-bottom py-2">
        <span>Storage Used</span>
        <span id="storageUsed">Loading...</span>
    </div>

    <div class="d-flex justify-content-between border-bottom py-2">
        <span>Storage Available</span>
        <span id="storageAvailable">Loading...</span>
    </div>

    <div class="d-flex justify-content-between border-bottom py-2">
        <span>Storage Usage</span>
        <span id="storagePercent">Loading...</span>
    </div>

    <div class="d-flex justify-content-between border-bottom py-2">
        <span>RAM</span>
        <span id="ramInfo">Loading...</span>
    </div>

    <div class="d-flex justify-content-between border-bottom py-2">
        <span>CPU Cores</span>
        <span id="cpuInfo">Loading...</span>
    </div>

    <div class="d-flex justify-content-between border-bottom py-2">
        <span>Operating System</span>
        <span id="osInfo">Loading...</span>
    </div>

    <div class="d-flex justify-content-between border-bottom py-2">
        <span>Platform</span>
        <span id="platformInfo">Loading...</span>
    </div>

    <div class="d-flex justify-content-between border-bottom py-2">
        <span>Screen Resolution</span>
        <span id="screenInfo">Loading...</span>
    </div>

    <div class="d-flex justify-content-between border-bottom py-2">
        <span>Language</span>
        <span id="languageInfo">Loading...</span>
    </div>

    <div class="d-flex justify-content-between py-2">
        <span>Time Zone</span>
        <span id="timezoneInfo">Loading...</span>
    </div>
</div>
            </div>

            <!-- 4. Quick Links -->
            <div class="col-lg-6">
                <div class="settings-card">
                    <div class="section-title">
                        <i class="bi bi-link-45deg fs-4"></i> Quick Links
                    </div>

                    <a href="history.php" class="quick-link-item">
                        <span><i class="bi bi-clock-history me-2"></i> Manage History</span>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>

                    <a href="saved.php" class="quick-link-item">
                        <span><i class="bi bi-bookmark-check me-2"></i> Manage Saved Items</span>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>

                    <a href="help.php" class="quick-link-item">
                        <span><i class="bi bi-question-circle me-2"></i> Help & Support</span>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
    <script>
const onlineTxt = document.getElementById("online-txt");
const statusDot = document.querySelector(".status-dot");
const browserInfo = document.getElementById("browserInfo");
const netSpeed = document.getElementById("netSpeed");
const storageUsed = document.getElementById("storageUsed");
const storageAvailable = document.getElementById("storageAvailable");
const storagePercent = document.getElementById("storagePercent");
const ramInfo = document.getElementById("ramInfo");
const cpuInfo = document.getElementById("cpuInfo");
const osInfo = document.getElementById("osInfo");
const platformInfo = document.getElementById("platformInfo");
const screenInfo = document.getElementById("screenInfo");
const languageInfo = document.getElementById("languageInfo");
const timezoneInfo = document.getElementById("timezoneInfo");

function updateOnlineStatus() {
    if (navigator.onLine) {
        onlineTxt.textContent = "Online";
        statusDot.style.background = "#22c55e";
    } else {
        onlineTxt.textContent = "Offline";
        statusDot.style.background = "#dc3545";
    }
}

window.addEventListener("online", updateOnlineStatus);
window.addEventListener("offline", updateOnlineStatus);
updateOnlineStatus();

browserInfo.textContent = navigator.userAgent;

if (navigator.connection) {
    function updateNetwork() {
        let type = navigator.connection.effectiveType || "Unknown";
        let down = navigator.connection.downlink || "-";
        netSpeed.textContent = down + " Mbps (" + type + ")";
    }
    updateNetwork();
    navigator.connection.addEventListener("change", updateNetwork);
} else {
    netSpeed.textContent = "Not Supported";
}

ramInfo.textContent = navigator.deviceMemory ? navigator.deviceMemory + " GB" : "Not Supported";
cpuInfo.textContent = navigator.hardwareConcurrency ? navigator.hardwareConcurrency + " Cores" : "Unknown";
osInfo.textContent = navigator.userAgentData ? navigator.userAgentData.platform : navigator.platform;
platformInfo.textContent = navigator.platform;
screenInfo.textContent = screen.width + " × " + screen.height;
languageInfo.textContent = navigator.language;
timezoneInfo.textContent = Intl.DateTimeFormat().resolvedOptions().timeZone;
async function loadStorage() {
    if (navigator.storage && navigator.storage.estimate) {
        const estimate = await navigator.storage.estimate();
        const used = estimate.usage || 0;
        const total = estimate.quota || 0;

        const usedGB = (used / 1073741824).toFixed(2);
        const totalGB = (total / 1073741824).toFixed(2);
        const freeGB = ((total - used) / 1073741824).toFixed(2);
        const percent = ((used / total) * 100).toFixed(1);

        storageUsed.textContent = usedGB + " GB";
        storageAvailable.textContent = freeGB + " GB";
        storagePercent.textContent = percent + "%";
    } else {
        storageUsed.textContent = "Not Supported";
        storageAvailable.textContent = "Not Supported";
        storagePercent.textContent = "Not Supported";
    }
}

loadStorage();
</script>
  <? include "footer.php"?>
  
</body>

</html>
<? } ?>