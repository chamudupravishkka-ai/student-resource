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
        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #111827;
        }

        /* Wireframe Consistent Card Style */
        .help-card {
            background-color: #ffffff;
            border: 1.5px solid #8b909b;
            border-radius: 16px;
            padding: 1.5rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .help-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.05);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            border: 1.5px solid #111827;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            background-color: #f9fafb;
        }

        .btn-custom {
             border: 1.5px solid #111827;
            color: #111827;
            font-weight: 600;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        

        .btn-custom-outline {
            
            background-color:rgb(35, 113, 243);
            color:rgb(237, 234, 228);
            font-weight: 600;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-custom-outline:hover {
            background-color: #111827;
            color: #ffffff;
        }

        /* Pure CSS Accordion using <details> & <summary> */
        .custom-faq-item {
            border: 1.5px solid #111827;
            border-radius: 12px;
            margin-bottom: 1rem;
            background-color: #ffffff;
            overflow: hidden;
        }

        .custom-faq-item summary {
            padding: 1rem;
            font-weight: bold;
            cursor: pointer;
            list-style: none; /* Default arrow icon එක අයින් කිරීමට */
            display: flex;
            justify-content: space-between;
            align-items: center;
            user-select: none;
        }

        .custom-faq-item summary::-webkit-details-marker {
            display: none;
        }

        .custom-faq-item summary::after {
            content: "▼";
            font-size: 1.2rem;
            font-weight: bold;
        }

        .custom-faq-item[open] summary::after {
            content: "▲";
        }

        .custom-faq-item[open] summary {
            background-color: #f3f4f6;
            border-bottom: 1px solid #111827;
        }

        .faq-content {
            padding: 1rem;
            color: #6b7280;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
  <? include "navigation.php";?>
  <div class="container py-5">
        
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="fw-bold m-0 text-dark"><i class="bi bi-question-circle me-2"></i>Help</h2>
                <p class="text-muted m-0 small mt-1">Learn how to use the site step-by-step.</p>
            </div>
            <a href="profile.php" class="btn btn-custom"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
        </div>

        <!-- Grid of Main Help Guides -->
        <div class="row g-4 mb-5">
            
            <!-- 1. Account & Login -->
            <div class="col-md-6 col-lg-3 ">
                <div class="help-card">
                    <div>
                        <div class="icon-box">
                            <i class="bi bi-box-arrow-in-right  text-danger"></i>
                        </div>
                        <h5 class="fw-bold">1. How to Login</h5>
                        <p class="text-muted small">
                            Enter your email and password on the login page. You can also sign in with Google for quick access.
                        </p>
                    </div>
                    <a href="login.php" class="btn btn-custom-outline w-100 mt-3">
                        Go to Login <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- 2. Upload Documents -->
            <div class="col-md-6 col-lg-3">
                <div class="help-card">
                    <div>
                        <div class="icon-box">
                            <i class="bi bi-upload text-success"></i>
                        </div>
                        <h5 class="fw-bold">2. How to Upload</h5>
                        <p class="text-muted small">
                            Click on 'Upload Docs' button in Messages/Dashboard. Choose your PDF or file and click upload to store safely.
                        </p>
                    </div>
                    <a href="upload.php" class="btn btn-custom-outline w-100 mt-3 txt">
                        Upload Now <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- 3. Messaging & Chat -->
            <div class="col-md-6 col-lg-3">
                <div class="help-card">
                    <div>
                        <div class="icon-box">
                            <i class="bi bi-chat-dots text-primary"></i>
                        </div>
                        <h5 class="fw-bold">3. How to Chat</h5>
                        <p class="text-muted small">
                            Go to Messages section to view new notifications, read unread messages, and send quick replies.
                        </p>
                    </div>
                    <a href="friends.php" class="btn btn-custom-outline w-100 mt-3">
                        Open Messages <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- 4. Save & Bookmark -->
            <div class="col-md-6 col-lg-3">
                <div class="help-card">
                    <div>
                        <div class="icon-box">
                            <i class="bi bi-bookmark-check text-danger"></i>
                        </div>
                        <h5 class="fw-bold">4. How to Save Items</h5>
                        <p class="text-muted small">
                            Click the heart or bookmark icon on any document card to save it for later. Access all from 'Saved Items'.
                        </p>
                    </div>
                    <a href="saved.php" class="btn btn-custom-outline w-100 mt-3">
                        View Saved <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

        </div>

        <!-- FAQ Section (Pure CSS Accordion) -->
        <div class="card p-4 border-2 border-secondary rounded-4 bg-white">
            <h4 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Frequently Asked Questions (FAQ)</h4>
            
            <!-- FAQ Item 1 -->
            <details class="custom-faq-item" open>
                <summary>How do I check or update my account details</summary>
                <div class="faq-content">
                    You can easily check or update your account from the Settings page. 
                    <br><a href="settings.php" class="fw-bold text-dark text-decoration-underline mt-2 d-inline-block">Go to Settings Page →</a>
                </div>
            </details>

            <!-- FAQ Item 2 -->
            <details class="custom-faq-item">
                <summary>What file formats are supported for upload?</summary>
                <div class="faq-content">
                    We currently support PDF files, Word documents (.docx), and common image files (PNG, JPG). Maximum allowed file size per upload is 25MB.
                </div>
            </details>

            <!-- FAQ Item 3 -->
            <details class="custom-faq-item">
                <summary>How do I update my remaining storage usage?</summary>
                <div class="faq-content">
                    Your Storage Usage bar is located directly on your Main Dashboard. It shows real-time percentage used.
                </div>
            </details>

        </div>

    </div>
  <? include "footer.php"?>
  
</body>

</html>
<? } ?>