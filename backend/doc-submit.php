<?
include "sens/session-check.php";
include "sens/sconn.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Invalid request");
}
var_dump($_POST['docTitle']);
$title = trim($_POST['docTitle']);
$description = trim($_POST['docDescription']);
$category = trim($_POST['category']);
$newCategory = trim($_POST['newCategory']);

if (empty($title)) {
    exit("Title cannot be empty");
}

if (strlen($title) > 255) {
    exit("Title maximum length is 255 characters");
}
if (strlen($description) < 200) {
    exit("Description minimum length is 200 characters");
}
if (strlen($description) > 2500) {
    exit("Description maximum length is 2500 characters");
}
if (!empty($newCategory)) {

    if (strlen($newCategory) > 20) {
        exit("Category name maximum length is 20");
    }

    // Insert new category

    $stmt = $conn->prepare(
        "INSERT INTO categories(name) VALUES(?)"
    );

    $stmt->bind_param("s",$newCategory);

    $stmt->execute();

    $categoryID = $conn->insert_id;


} else {

    if (empty($category)) {
        exit("Please select a category");
    }

    $categoryID = $category;

}

if (!isset($_FILES['fileUpload'])) {
    exit("File required");
}


$file = $_FILES['fileUpload'];

if ($file['error'] !== 0) {
    exit("File upload error");
}



$maxSize = 200 * 1024 * 1024; //200MB


if ($file['size'] > $maxSize) {
    exit("File size exceeds 200MB");
}



$allowed = [
    "pdf",
    "docx",
    "xlsx",
    "pptx",
    "txt",
    "zip",
    "png",
    "webp",
    "jpg",
    "jpeg"
];


$extension = strtolower(
    pathinfo($file['name'], PATHINFO_EXTENSION)
);



if (!in_array($extension,$allowed)) {
    exit("File type not allowed");
}

do {

    $randomName = "";

    for($i=0;$i<6;$i++){
        $randomName .= rand(0,9);
    }

    $newFileName = $randomName.".".$extension;


    $check = $conn->query(
        "SELECT id FROM documents WHERE filepath='$newFileName'"
    );


} while($check->num_rows > 0);




// =====================
// Upload folder
// =====================

$uploadDir = "resources/".$_SESSION['folder']."/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir,0777,true);
}


$path = $uploadDir.$newFileName;



if (!move_uploaded_file($file['tmp_name'],$path)) {
    exit("File moving failed");
}



// =====================
// Database insert
// =====================


$stmt = $conn->prepare(
"INSERT INTO documents
(title,description,url,cat_id,user_id)
VALUES(?,?,?,?,?)"
);


$stmt->bind_param(
"sssii",
$title,
$description,
$newFileName,
$categoryID,
$userid
);



if($stmt->execute()){

    echo "Upload successful";
    header("Location: index.php");

}else{

    echo "Database error: ".$conn->error;

}


?>
?>