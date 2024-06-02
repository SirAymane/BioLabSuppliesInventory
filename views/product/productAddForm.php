<h2>Add New Product</h2>

<?php
// Display error message if set
if (isset($params['error'])) {
    echo "<div class='alert alert-danger'>" . htmlspecialchars($params['error']) . "</div>";
}

// Retain previously submitted values if available
$codeValue = isset($params['code']) ? htmlspecialchars($params['code']) : '';
$descriptionValue = isset($params['description']) ? htmlspecialchars($params['description']) : '';
$priceValue = isset($params['price']) ? htmlspecialchars($params['price']) : '';
?>

<form method='post' action='index.php?action=product/add'>
    <div class="mb-3">
        <label for="code" class="form-label">Code</label>
        <input type="text" class="form-control" id="code" name="code" placeholder="Enter product code" value="<?= $codeValue ?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <input type="text" class="form-control" id="description" name="description" placeholder="Enter product description" value="<?= $descriptionValue ?>" required>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="number" class="form-control" id="price" name="price" placeholder="Enter product price" value="<?= $priceValue ?>" required step="0.01">
    </div>
    <button type="submit" class="btn btn-primary" name="action" value="product/add">Add Product</button>
    <a href="index.php?action=product/manage" class="btn btn-secondary">Cancel</a>
</form>
