<?
session_start();
$timeout = 1800; // 30 minutes

if (isset($_SESSION['login_time']) &&
    (time() - $_SESSION['login_time']) > $timeout) {

    session_unset();
    session_destroy();

    header("Location: login.php");
    exit;
}

$_SESSION['login_time'] = time();
$allow = true;
$apploaded = false;

if(isset($_SESSION['user']) && isset($_SESSION['logo']) && isset($_SESSION['email'])){
	$userid = $_SESSION['id'];
	$username = $_SESSION['user'];
	$userimg = $_SESSION['logo'];
	$email = $_SESSION['email'];

	$allow = false;
}

?>
