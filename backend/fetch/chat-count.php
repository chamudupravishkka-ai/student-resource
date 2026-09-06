<?php
include '../sens/session-check.php';
include "../sens/sconn.php";
// add the session allow and apploaded(this is a part)

$sql = "SELECT COUNT(*) AS unread_count
        FROM friends
        WHERE to_id = $userid
        AND `read` = 0";

$result = $conn->query($sql);

$row = $result->fetch_assoc();

header("Content-Type: application/json");

echo json_encode([
    "count" => (int)$row['unread_count']
]);