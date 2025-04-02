<?php

$servername = "127.0.0.1";
$username = "web";
$password = "webGt2025";
$dbname = "shop_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    // PDO error mode
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "connection error: " . $e->getMessage();
}
?>
