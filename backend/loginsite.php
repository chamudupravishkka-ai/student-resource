<?
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$rawEmail = $_POST['email'] ?? '';
    $rawPassword = $_POST['password'] ?? '';
    $sanitizedEmail = filter_var(trim($rawEmail), FILTER_SANITIZE_EMAIL);

    if (!filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        //echo json_encode(['error' => 'Invalid email format provided.']);
        exit;
    }

    if (mb_strlen($rawPassword) < 8) {
        http_response_code(400);
        echo json_encode(['error' => 'Password must be at least 8 characters long.']);
        exit;
    }

    
    $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

    include "sens/sconn.php";

$userdata = getData(
    $conn,
    "users",
    ["username", "email", "password", "icon", "folder","ID"],
    [$sanitizedEmail],
    "email = ?"
);

foreach ($userdata as $u) {

    if (password_verify($rawPassword, $u['password'])) {
        $_SESSION['id'] = $u['ID'];
        $_SESSION['user'] = $u['username'];
        $_SESSION['logo'] = $u['icon'];
        $_SESSION['email'] = $u['email'];
        $_SESSION['folder'] = $u['folder'];
        header('Location: index.php');
        exit;
    }else{
        header("Location: index.php");
    }
}
}else{
        header("Location: index.php");
    }
?>