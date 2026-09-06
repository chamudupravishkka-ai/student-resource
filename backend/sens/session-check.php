<?
session_start();
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