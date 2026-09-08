<?php

include 'sens/sconn.php';

$email = trim($_GET['e'] ?? '');
$code = trim($_GET['code'] ?? '');

if ($email === '' || $code === '') {
    echo "Invalid verification link.";
    exit;
}

$tempRecords = getData(
    $conn,
    "temp-sign",
    ["username", "email", "password", "verify", "udate", "icon"],
    [$email, $code],
    "email = ? AND verify = ?","",0
);

if (empty($tempRecords)) {
    echo "Invalid or already used verification link.";
    exit;
}

$temp = $tempRecords[0];

$createdTime = strtotime($temp['udate']);
$currentTime = time();

if ($createdTime === false || ($currentTime - $createdTime) > 60000) {

    $conn->query(
        "DELETE FROM `temp-sign` WHERE email = '" .
        $conn->real_escape_string($email) .
        "' AND verify = '" .
        $conn->real_escape_string($code) .
        "'"
    );

    echo "This verification link has expired.";
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

    $conn->query(
        "DELETE FROM temp-sign WHERE email = '" .
        $conn->real_escape_string($email) .
        "' AND verify = '" .
        $conn->real_escape_string($code) .
        "'"
    );

    echo "This account already exists.";
    exit;
}

do {
    $resourceFolder = (string) random_int(10000000, 99999999);
    $resourcePath = __DIR__ . "/resources/" . $resourceFolder;
} while (is_dir($resourcePath));

if (!is_dir(__DIR__ . "/resources/")) {
    mkdir(__DIR__ . "/resources/", 0755, true);
}

if (!mkdir($resourcePath, 0755, true)) {
    echo "Failed to create account resources folder.";
    exit;
}

$userData = [
    "username" => $temp['username'],
    "email" => $temp['email'],
    "password" => $temp['password'],
    "icon" => $temp['icon'],
    "folder" => $resourceFolder
];

try {

    $userId = setData(
        $conn,
        "users",
        $userData
    );
var_dump($userId);
echo "<br>";
    if (!$userId) {
        rmdir($resourcePath);
        echo "Failed to create account.";
        exit;
    }

    $stmt = $conn->prepare(
        "DELETE FROM `temp-sign` WHERE email = ? AND verify = ?"
    );

    if (!$stmt) {
        throw new Exception("Delete prepare failed.");
    }

    $stmt->bind_param("ss", $email, $code);
    $stmt->execute();
    $stmt->close();

    header("Location: login.php");
    exit;

} catch (Exception $e) {

    if (is_dir($resourcePath)) {
        rmdir($resourcePath);
    }
    var_dump($e);
    echo "Account creation failed.";
    exit;
}

?>
