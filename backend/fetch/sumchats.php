<?php
include '../sens/session-check.php';
include "../sens/sconn.php";

$sql = "SELECT
    u.id,
    u.username,
    u.icon,
    m.message AS latest_message,
    m.udate AS latest_date,

    (
        SELECT COUNT(*)
        FROM friends um
        WHERE um.userID = u.id
          AND um.to_id = $userid
          AND um.read = 0
    ) AS unread_count

FROM users u

LEFT JOIN friends m
    ON m.id = (
        SELECT m2.id
        FROM friends m2
        WHERE
            (m2.userID = $userid AND m2.to_id = u.id)
            OR
            (m2.userID = u.id AND m2.to_id = $userid)
        ORDER BY m2.udate DESC
        LIMIT 1
    )

WHERE u.id IN (
    SELECT userID
    FROM friends
    WHERE to_id = $userid

    UNION

    SELECT to_id
    FROM friends
    WHERE userID = $userid
)

AND u.id != $userid

ORDER BY m.udate DESC";

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode([
        "error" => $conn->error
    ]);
    exit;
}

$chats = [];

while ($row = $result->fetch_assoc()) {
    $chats[] = $row;
}

header("Content-Type: application/json");

echo json_encode($chats);
?>