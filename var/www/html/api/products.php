<?php
include '../util/conn.php';

try {
    $stmt = $conn->query("SELECT * FROM products");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
} catch (PDOException $e) {
    echo htmlspecialchars($e->getMessage());
}