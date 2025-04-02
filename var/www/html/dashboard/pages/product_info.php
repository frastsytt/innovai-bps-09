<?php
set_include_path($_SERVER['DOCUMENT_ROOT'].'/util');
include_once("auth.php");
include_once("conn.php");

if (!isset($_GET['id'])) {
    echo "Invalid Product ID.";
    exit;
}

try {
    $stmt = $conn->query("SELECT * FROM products WHERE id = {$_GET['id']}");
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Product not found.";
        exit;
    }

    $attributes = unserialize($product['attributes']);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>
<style>
.table th img, .table td img {
    width: 50% !important;
    height: auto !important;
    border-radius: 0 !important;
}    
</style>
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title"> Product Details </h3>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Details of <?= htmlspecialchars($product['name']) ?></h4>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td><?= htmlspecialchars($product['description']) ?></td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td><?= htmlspecialchars($product['category']) ?></td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td>$<?= number_format($product['price'], 2) ?></td>
                            </tr>
                            <tr>
                                <th>Image</th>
                                <td><img width="50%" src="../uploads/<?= $product['img'] ?>"></td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td><?= htmlspecialchars($product['created_at']) ?></td>
                            </tr>
                            <tr>
                                <th>Attributes</th>
                                <td>
                                    <ul>
                                        <?php foreach ($attributes as $key => $value): ?>
                                            <li><strong><?= htmlspecialchars($key) ?>:</strong> <?= htmlspecialchars($value) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-light mt-2" onclick="window.history.back();">Back</button>
                </div>
            </div>
        </div>
    </div>
</div>
