<?php
set_include_path($_SERVER['DOCUMENT_ROOT'].'/util');
include_once("auth.php");
include_once("conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("UPDATE users SET email = ?, password = ? WHERE username = ?");
        $stmt->execute([$email, $password, $auth->getUsername()]);
        
        $auth = new User($auth->getUsername(), $email, 1);
        $_SESSION["auth"] = serialize($auth);
        header("Location: /dashboard/?page=setting.php");
        exit;
    } catch (PDOException $e) {
        //SECURITY FIX: we Log error securely instead of displaying it
        error_log("Database error: " . $e->getMessage());
        http_response_code(500);
        exit("An error occurred while updating settings.");
    }
}