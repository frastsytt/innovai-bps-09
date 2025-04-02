<?php
set_include_path($_SERVER['DOCUMENT_ROOT'].'/util');
include_once("auth.php");
include_once("conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    $stmt = $conn->prepare("delete from products where id=?");
    $stmt->execute([$id]);
    header("Location: /dashboard/?page=products.php");
    exit;
}
