<?php

session_start();

header('Content-Type: application/json');

include 'sens/sconn.php';

$user = trim($_POST['user'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($user === '' &&  $email === '' && $password === '') {
    echo json_encode([
        'success' => false,
        'error' => 'All fields are required.'
    ]);
    exit;
}

if (strlen($user) < 4 || strlen($user) > 15) {
    echo json_encode([
        'success' => false,
        'error' => 'Username must be 4–15 characters long.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid email address.'
    ]);
    exit;
}

if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
    echo json_encode([
        'success' => false,
        'error' => 'Password must be at least 8 characters and include uppercase, lowercase, and a number.'
    ]);
    exit;
}
$existingUser = getData(
    $conn,
    "users",
    ["email"],
    [$email],
    "email = ?",
    "",
    0
);

if (!empty($existingUser)) {
    echo json_encode([
        'success' => false,
        'error' => 'Email is already registered.'
    ]);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$iconName = '';

if (isset($_FILES['userIcon'])) {

    if ($_FILES['userIcon']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode([
            'success' => false,
            'error' => 'Profile image upload failed.'
        ]);
        exit;
    }

    $tmpPath = $_FILES['userIcon']['tmp_name'];

    $uploadDir = __DIR__ . '/user-icon/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $iconName = bin2hex(random_bytes(12));

    $destination = $uploadDir . $iconName;

    if (!move_uploaded_file($tmpPath, $destination)) {
        echo json_encode([
            'success' => false,
            'error' => 'Failed to save profile image.'
        ]);
        exit;
    }
}

$verifyCode = random_int(100000, 999999);

$udate = date('Y-m-d H:i:s');

$sql = "INSERT INTO `temp-sign`
(username, email, password, verify, udate, icon)
VALUES (?, ?, ?, ?, ?, ?)";
$insertData = [
    "username" => $user,
    "email" => $email,
    "password" => $hashedPassword,
    "verify" => $verifyCode,
    "udate" => $udate,
    "icon" => $iconName
];

$result = setData($conn, "temp-sign", $insertData);

if (!$result) {
    if ($iconName !== '') {
        $filePath = $uploadDir . $iconName;

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    echo json_encode([
        'success' => false,
        'error' => 'Registration failed.'
    ]);
    exit;
}
$debugUrl = 'http://localhost/backened/verify.php?e='
    . urlencode($email)
    . '&code='
    . urlencode($verifyCode);

echo json_encode([
    'success' => true,
    'message' => 'Registration initiated! Please check your email.',
    'debug_url' => $debugUrl,
    'icon' => $iconName
]);

?>
