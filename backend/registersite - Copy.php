<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawUser = $_POST['user'] ?? '';
    $rawEmail = $_POST['email'] ?? '';
    $rawPassword = $_POST['password'] ?? '';

    // Sanitize & Validate Username
    $sanitizedUser = trim($rawUser);
    if (mb_strlen($sanitizedUser) < 3 || mb_strlen($sanitizedUser) > 50) {
        http_response_code(400);
        echo json_encode(['error' => 'Username must be between 3 and 50 characters.']);
        exit;
    }

    // Sanitize & Validate Email
    $sanitizedEmail = filter_var(trim($rawEmail), FILTER_SANITIZE_EMAIL);
    if (!filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid email format provided.']);
        exit;
    }

    // Validate Password Length
    if (mb_strlen($rawPassword) < 8) {
        http_response_code(400);
        echo json_encode(['error' => 'Password must be at least 8 characters long.']);
        exit;
    }

    // Securely hash the password
    $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

    // Include your database connection and helper functions
    include "sens/sconn.php";

    // 1. Check if the email already exists in the main 'users' table
    $existingUser = getData(
        $conn,
        "users",
        ["email"],
        [$sanitizedEmail],
        "email = ?", "", 0
    );

    if (!empty($existingUser)) {
        http_response_code(400);
        echo json_encode(['error' => 'This email is already registered. Please log in instead.']);
        exit;
    }

    // 2. Handle Image Upload & Convert to WebP
    $iconName = ''; 
    
    if (isset($_FILES['userIcon'])) {
        // Debugging: check upload error code
        $uploadError = $_FILES['userIcon']['error'];
        
        if ($uploadError === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['userIcon']['tmp_name'];
            $fileType = mime_content_type($fileTmpPath);
            
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/jpg'];
            if (in_array($fileType, $allowedTypes)) {
                $randomName = bin2hex(random_bytes(12)); 
                $uploadDir = 'user-icon/';
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $destPath = $uploadDir . $randomName . '.webp';
                
                $image = null;
                if ($fileType === 'image/jpeg' || $fileType === 'image/jpg') {
                    $image = @imagecreatefromjpeg($fileTmpPath);
                } elseif ($fileType === 'image/png') {
                    $image = @imagecreatefrompng($fileTmpPath);
                    if ($image) {
                        imagepalettetotruecolor($image);
                        imagealphablending($image, true);
                        imagesavealpha($image, true);
                    }
                } elseif ($fileType === 'image/webp') {
                    $image = @imagecreatefromwebp($fileTmpPath);
                } elseif ($fileType === 'image/gif') {
                    $image = @imagecreatefromgif($fileTmpPath);
                }
                
                if ($image) {
                    if (imagewebp($image, $destPath, 80)) {
                        $iconName = $randomName; // Success!
                    }
                    imagedestroy($image);
                } else {
                    // GD failed to read the image resource
                    http_response_code(400);
                    echo json_encode(['error' => 'Failed to process image. Make sure GD library is enabled in PHP.']);
                    exit;
                }
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid image type: ' . $fileType]);
                exit;
            }
        } elseif ($uploadError !== UPLOAD_ERR_NO_FILE) {
            // An actual upload error occurred other than "no file chosen"
            http_response_code(400);
            echo json_encode(['error' => 'File upload error code: ' . $uploadError]);
            exit;
        }
    }
    // 3. Generate temporary verification parameters
    $randCode = rand(100000, 999999);
    $currentTime = date('Y-m-d H:i:s');

    // 4. Insert into 'temp_sign' with explicit error capture
    $sql = "INSERT INTO `temp-sign` (`username`, `email`, `password`, `verify`, `udate`, `icon`) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(['error' => 'SQL Prepare Failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("sssiss", $sanitizedUser, $sanitizedEmail, $hashedPassword, $randCode, $currentTime, $iconName);
    
    if ($stmt->execute()) {
        $stmt->close();

        $verifyUrl = "http://localhost/projects/simple-chat-php/verify.php?e=" . urlencode($sanitizedEmail) . "&code=" . $randCode;

        echo json_encode([
            'success' => true, 
            'message' => 'Registration initiated! Please check your email.',
            'debug_url' => $verifyUrl
        ]);
        exit;
    } else {
        $errorMsg = $stmt->error;
        $stmt->close();
        http_response_code(500);
        echo json_encode(['error' => 'SQL Execute Failed: ' . $errorMsg]);
        exit;
    }
}
?>