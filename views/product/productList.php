<h2>Order Item Search</h2>

<?php 
    echo "<pre>";
    echo "POST Data: ";
    print_r($_POST);
    echo "Session Data: ";
    print_r($_SESSION);
    echo "</pre>";
?>

<!-- Order Item Re-Search Form -->
<form method="post" action="index.php?action=order/itemSearch">
    <div class="row g-3 align-items-center">
        <span class="col-auto">
            <label for="description" class="col-form-label">Product Description</label>
        </span>
        <span class="col-auto">
            <input type="text" id="description" name="description" class="form-control" value="<?php echo $_POST['description'] ?? ''; ?>">
        </span>
        <span class="col-auto">
            <button class="btn btn-primary" type="submit">Search</button>
        </span>
    </div>
</form>

<!-- Display Order Items Search Results -->
<?php if (!empty($params['orderItems'])): ?>
    <table class="table table-bordered table-striped">
        <caption><strong>List of Order Items Found</strong></caption>
        <thead class="table-dark">
            <tr>
                <th>Code</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($params['orderItems'] as $item): ?>
                <tr>
                    <td><?php echo $item->getCode(); ?></td>
                    <td><?php echo $item->getDescription(); ?></td>
                    <td><?php echo $item->getPrice(); ?></td>
                    <td>
                        <form method="post" action="index.php?action=order/addItemToCart">
                            <input type="number" name="quantity" min="1" value="1">
                            <input type="hidden" name="product_id" value="<?php echo $item->getId(); ?>">
                            <button type="submit" class="btn btn-primary">Add to Cart</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class='alert alert-info'>No matching items found.</div>
<?php endif; ?>

<div class="mt-3">
    <a href="index.php?action=order/manageOrderItems" class="btn btn-primary">Back to Order Items Management</a>
</div>
