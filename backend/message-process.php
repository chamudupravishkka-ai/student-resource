<?
include "sens/session-check.php";
if($allow){
  header("Location: login.php");

}else{
  $apploaded = true;
  if(isset($_POST['message'])){
  	$message = $_POST['message'];
  }else{
  	header("Location: index.php");
  }
  if(isset($_GET['id'])){
  	$friend = $_GET['id'];
  }else{
  	header("Location: index.php");
  }
  
  include "sens/sconn.php";
try {
  $userdata = ["userID"=>$userid,"to_id"=>$friend,"message"=>$message,"read"=>0];
  $data = setData($conn,"friends",$userdata);
 }catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<?}?>