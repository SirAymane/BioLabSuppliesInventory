<h2>Edit Product</h2>

<?php
$product = $params['product'] ?? new \SirAymane\ecommerce\model\Product($id, '', '', 0.0);
$product_id = $product->getId();
?>

<form method='post' action='index.php?action=product/edit'>
  <input type='hidden' name='id' value='<?php echo $product_id; ?>'>
  <div class="mb-3">
    <label for="code" class="form-label">Code</label>
    <input type="text" class="form-control" id="code" name="code" value="<?php echo htmlspecialchars($product->getCode()); ?>" required>
  </div>
  <div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <input type="text" class="form-control" id="description" name="description" value="<?php echo htmlspecialchars($product->getDescription()); ?>" required>
  </div>
  <div class="mb-3">
    <label for="price" class="form-label">Price</label>
    <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product->getPrice()); ?>" required step="0.01">
  </div>
  <button type="submit" class="btn btn-primary" name="action" value="product/edit">Save Changes</button>
  <a href="index.php?action=product/manage" class="btn btn-secondary">Cancel</a>
</form>
