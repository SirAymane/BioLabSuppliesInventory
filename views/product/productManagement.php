<h2>Product Management Page</h2>

<!-- Product Search Form -->
<form method="post" action="index.php">
    <div class="row g-3 align-items-center">
        <span class="col-auto">
            <label for="description" class="col-form-label">Product Description</label>
        </span>
        <span class="col-auto">
            <input type="text" id="description" name="description" class="form-control">
        </span>
        <span class="col-auto">
            <button class="btn btn-primary" type="submit" name="action" value="product/search">Search</button>
        </span>
        <span class="col-auto">
            <button class="btn btn-primary" type="submit" name="action" value="product/addForm">Add Product</button>
        </span>
    </div>
</form>

<!-- Display Messages -->
<?php if (isset($params['message'])) : ?>
    <br>
    <div class='alert alert-warning'>
        <strong><?php echo $params['message']; ?></strong>
    </div>
<?php endif ?>

<!-- Product List Table -->
<?php
$list = $params['products'] ?? null;
if (isset($list) && count($list) > 0) {
    echo "<table class=\"table table-sm table-bordered table-striped table-hover caption-top table-responsive-sm\">";
    echo "<caption>List of Products</caption>";
    echo "<thead class='table-dark'>";
    echo "<tr>";
    echo "<th>Code</th>";
    echo "<th>Description</th>";
    echo "<th>Price</th>";
    echo "<th>Actions</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach ($list as $product) {
        echo "<tr>";
        echo "<td>{$product->getCode()}</td>";
        echo "<td>{$product->getDescription()}</td>";
        echo "<td>{$product->getPrice()}</td>";
        echo "<td>";
        echo "<a href='index.php?action=product/editForm&id={$product->getId()}' class='btn btn-warning'>Edit</a>&emsp;";
        echo "<a href='index.php?action=product/delete&id={$product->getId()}' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this product?\");'>Delete</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "<div class='alert alert-info' role='alert'>";
    echo count($list) . " products found.";
    echo "</div>";
} else {
    echo "<div class='alert alert-info' role='alert'>No products found.</div>";
}
?>
