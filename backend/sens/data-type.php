<?
function filturl($url) {
    $parts = explode('.', $url);
    return count($parts) > 1 ? strtolower(end($parts)) : '';
}
 $types = [
    "docx" => ["primary", "Word","bi bi-file-earmark-word"],
    "pdf" => ["danger", "PDF","bi bi-file-earmark-pdf"],
    "xlsx" => ["success", "Excel","bi bi-file-earmark-excel"],
    "pptx" => ["warning", "Powerpoint","bi-file-earmark-ppt"],
    "zip"  => ["secondary", "Compressed","bi bi-file-earmark-zip"]
];

?>