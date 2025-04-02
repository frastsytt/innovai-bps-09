<?php
set_include_path($_SERVER['DOCUMENT_ROOT'].'/util');
include_once("auth.php");
include_once("conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    die($email.$password);
    $stmt = $conn->prepare("update users set email=?, password=? where username=?");
    $stmt->execute([$email, $password, $auth->getUsername()]);
    $auth = new User($auth->getUsername(), $email, 1);
    $_SESSION["auth"] = serialize($auth);
    header("Location: /dashboard/?page=setting.php");
    exit;
}
