<?php
set_include_path($_SERVER['DOCUMENT_ROOT'].'/util');
include_once("auth.php");
include_once("conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $attributes = serialize(array_combine($_POST['attributes']['key'], $_POST['attributes']['value']));

    $img_dir = $_SERVER['DOCUMENT_ROOT']."/uploads/";
    if(!empty($_FILES['img']['tmp_name'][0])){
        $mime_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['img']['tmp_name'][0]);
    }else{
        die("Image file is required!!");
    }
    $allowed_file_types = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/bmp',
        'image/webp',
        'image/svg+xml',
        'image/tiff',
    ];
    if (! in_array($mime_type, $allowed_file_types)) {
        die("File type not be allowed!!");
    }
    $uploadfile  = $img_dir . gen_uid() . ".webp";
    if (move_uploaded_file($_FILES['img']['tmp_name'][0], $uploadfile)) {
        $img = basename($uploadfile);
    } else {
        header("Location: /dashboard/?page=products.php");
        exit;
    }
  
    $stmt = $conn->prepare("INSERT INTO products (name, description, category, price, img, attributes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $description, $category, $price, $img, $attributes]);
  
    header("Location: /dashboard/?page=products.php");
    exit;
}
?>
