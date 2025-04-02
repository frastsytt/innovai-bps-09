<?php
set_include_path($_SERVER['DOCUMENT_ROOT'].'/util');
include_once("auth.php");
include_once("conn.php");
?>
<style>

.table td, .table th {
    word-wrap: break-word;
    word-break: break-word;
    white-space: normal; 
}

.table td {
    max-width: 300px; 
}
</style>
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title"> Products Table </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Tables</a></li>
                <li class="breadcrumb-item active" aria-current="page">Products</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Products</h4>
                    <a href="?page=product_add.php" class="btn btn-gradient-primary me-2">Add Product</a>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                              try {
                                $stmt = $conn->query("SELECT id, name, category, price FROM products");
                                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($products as $product): 
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['name']); ?></td>
                                    <td><?= htmlspecialchars($product['category']); ?></td>
                                    <td><?= number_format($product['price'], 2); ?></td>
                                    <td>
                                        <a href="?page=product_info.php&id=<?= $product['id']; ?>" class="btn btn-info btn-sm">Details</a>
                                        <a href="api/product_delete.php?id=<?= $product['id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                    </td>
                                </tr>
                            <?php 
                                endforeach;
                              } catch (PDOException $e) {
                                echo "<tr><td colspan='4'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                              }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
