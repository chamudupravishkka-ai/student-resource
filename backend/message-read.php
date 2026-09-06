<?
include "sens/session-check.php";
if($allow){
  header("Location: login.php");
}else{
  $apploaded = true;
  if(isset($_GET['id'])){
  	$frid = $_GET['id'];
  }else{
  	header("Location: index.php");
  }
  include "sens/sconn.php";
  try {
    $conn->begin_transaction();
    $selectSql = "SELECT `ID`, `userID`, `message`, `udate` FROM `friends` WHERE `userID` = ? AND `to_id` = ? AND `read` = ? ORDER BY `udate` LIMIT 30";
    
    $stmt = $conn->prepare($selectSql);
    if (!$stmt) {
        throw new Exception("Prepare failed (SELECT): " . $conn->error);
    }
    $unreadStatus = 0;

    // Bind parameters: i = integer, adjust if your IDs are strings (s)
    $stmt->bind_param("iii", $frid, $userid, $unreadStatus);
    $stmt->execute();
    $result = $stmt->get_result();

    $chats = [];
    $messageIds = [];

    while ($row = $result->fetch_assoc()) {
        $chats[] = $row;
        $messageIds[] = $row['ID']; // Collect IDs to mark as read
    }
    $stmt->close();

    // 3. UPDATE the selected messages to read = 1 (if any were found)
    if (!empty($messageIds)) {
        // Create dynamic placeholders for the IN clause (e.g., (?, ?, ?))
        $placeholders = implode(',', array_fill(0, count($messageIds), '?'));
        
        $updateSql = "UPDATE `friends` SET `read` = 1 WHERE `ID` IN ($placeholders)";
        
        $updateStmt = $conn->prepare($updateSql);
        if (!$updateStmt) {
            throw new Exception("Prepare failed (UPDATE): " . $conn->error);
        }

        // Dynamically bind the message IDs
        $types = str_repeat('i', count($messageIds)); // assuming ID is integer
        $updateStmt->bind_param($types, ...$messageIds);
        $updateStmt->execute();
        $updateStmt->close();
    }

    // 4. Commit the transaction
    $conn->commit();

    echo json_encode($chats);
    exit;

} catch (Exception $e) {
    // Rollback changes if anything goes wrong
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}
?>

<?}?>