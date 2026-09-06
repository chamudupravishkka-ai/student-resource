<?
include "sens/session-check.php";
if(!$allow){
	if(isset($_GET['q'])){
		$data = $_GET['q'];

		include "sens/sconn.php";
		
$searchTerm = "%" . $data . "%";
$search = getData(
    $conn, 
    "users", 
    ["username", "icon", "ID"], 
    [$searchTerm,$userid],
    "username LIKE ? AND ID != ?",
    "", 
    10
);
		echo json_encode($search);
	}
	
}
?>