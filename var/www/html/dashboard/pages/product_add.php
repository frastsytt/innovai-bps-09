<?php
set_include_path($_SERVER['DOCUMENT_ROOT'].'/util');
include_once("auth.php");
include_once("conn.php");
?>
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title"> Add Product </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Product</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Add New Product</h4>
                    <form class="forms-sample" method="POST" action="api/product_add.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="productName">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="name" placeholder="Enter product name" required>
                        </div>
                        <div class="form-group">
                            <label for="productDescription">Description</label>
                            <textarea class="form-control" id="productDescription" name="description" rows="4" placeholder="Enter product description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="productCategory">Category</label>
                            <input type="text" class="form-control" id="productCategory" name="category" placeholder="Enter product category" required>
                        </div>
                        <div class="form-group">
                            <label for="productPrice">Price</label>
                            <input type="number" step="0.01" class="form-control" id="productPrice" name="price" placeholder="Enter product price">
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="img[]" class="file-upload-default" accept="image/*">
                            <div class="input-group col-xs-12">
                                <input type="text" class="form-control file-upload-info" disabled placeholder="Image">
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-gradient-primary py-3" type="button">Upload</button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Attributes</label>
                            <div id="attributes-container">
                                <!-- Dynamic key-value input pairs -->
                                <div class="attribute-row">
                                    <div class="row mb-2">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="attributes[key][]" placeholder="Key" required>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="attributes[value][]" placeholder="Value" required>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-remove-attribute">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="add-attribute" class="btn btn-success mt-2">Add Attribute</button>
                        </div>
                        <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                        <button type="button" class="btn btn-light" onclick="window.history.back();">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add script for dynamic attribute management -->
<script>
    document.getElementById('add-attribute').addEventListener('click', function() {
        const container = document.getElementById('attributes-container');
        const newRow = document.createElement('div');
        newRow.classList.add('attribute-row');
        newRow.innerHTML = `
            <div class="row mb-2">
                <div class="col-md-5">
                    <input type="text" class="form-control" name="attributes[key][]" placeholder="Key" required>
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control" name="attributes[value][]" placeholder="Value" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-remove-attribute">Remove</button>
                </div>
            </div>`;
        container.appendChild(newRow);
    });

    document.getElementById('attributes-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-attribute')) {
            e.target.closest('.attribute-row').remove();
        }
    });
</script>
