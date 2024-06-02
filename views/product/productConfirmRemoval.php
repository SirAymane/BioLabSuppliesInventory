<h2>Confirm Product Deletion</h2>

<?php
$product = $params['product'] ?? new \SirAymane\ecommerce\model\Product($id, '', '', 0.0);
$product_id = $product->getId();
?>

<div class="alert alert-warning" role="alert">
    <h4 class="alert-heading">Warning!</h4>
    <p>You are about to delete the following product:</p>
    <hr>
    <p class="mb-0"><b>Code:</b> <?php echo htmlspecialchars($product->getCode()); ?></p>
    <p class="mb-0"><b>Description:</b> <?php echo htmlspecialchars($product->getDescription()); ?></p>
    <p class="mb-0"><b>Price:</b> <?php echo htmlspecialchars($product->getPrice()); ?></p>
    <p class="mb-3">This action is irreversible and may also affect related data (e.g., order items).</p>
</div>

<form method='post' action='index.php?action=product/delete'>
    <input type='hidden' name='id' value='<?php echo $product_id; ?>'>
    <button class='btn btn-danger' type='submit'>Confirm Deletion</button>
    <a href="index.php?action=product/manage" class="btn btn-secondary">Cancel</a>
</form>