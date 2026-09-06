<?php
header('Content-Type: application/json; charset=utf-8');

include "sens/sconn.php";
include "sens/data-type.php";

try {
    $cards = getDataWithJoins($conn, 'documents', ['documents.*', 'users.username', 'users.icon'], [
        'INNER JOIN users ON documents.user_id = users.ID'
    ], [], '', 'documents.date DESC', 8);

    $response = [];

    if ($cards) {
        foreach ($cards as $c) {
            $shortText = mb_substr($c['description'], 0, 200) . (mb_strlen($c['description']) > 200 ? '...' : '');
            $response[] = [
                'id'             => $c['ID'],
                'title'          => $c['title'],
                'description'    => $shortText,
                'fileIcon'       => $types[filturl($c['url'])][2],
                'fileCol'  		 => $types[filturl($c['url'])][0],
                'fileName'		 => $types[filturl($c['url'])][1],
                'uploadDate'     => $c['date'],
                'uploaderName'   => $c['username'],
                'uploaderAvatar' => $c['icon']
            ];
        }
    }

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>