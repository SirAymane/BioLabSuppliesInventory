<?php
require_once 'lib/Renderer.php';
require_once 'model/Product.php';
use proven\store\model\Product;
echo "<p class='fs-2'><b>Product deletion page</b></p>";
$product = $params['product'] ?? new Product();
echo "<form method='post' action=\"index.php\">";
echo proven\lib\views\Renderer::renderProductDeletionFields($product);
echo "<p><b>You sure you want to delete this object. It will be unrecoverable and will also delete all stocks</b></p>";
echo "<button class='btn btn-danger' type='submit' name='action' value='product/remove'>Remove</button>";
echo "</form>";

