<?php
require_once 'lib/Renderer.php';
require_once 'model/Product.php';

use proven\store\model\Product;
echo "<p>Product detail page</p>";
$product = $params['product'] ?? new Product();
$product_id = $product->getId();
echo "<form method='post' action=\"index.php?action=product/modify&id={$product_id}\">";
echo proven\lib\views\Renderer::renderProductFields($product);
echo "<button type='submit' name='action' value='product/modify'>Modify</button>";
echo "</form>";
