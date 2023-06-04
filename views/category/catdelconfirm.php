<?php
require_once 'lib/Renderer.php';
require_once 'model/Category.php';

use proven\store\model\Category;

echo "<p class='fs-2'><b>Category deletion page</b></p>";
$category = $params['category'] ?? new Category();
echo "<form method='post' action=\"index.php\">";
echo proven\lib\views\Renderer::renderCategoryDeletionFields($category);
echo "<p><b>You sure you want to delete this object. It will be unrecoverable</b></p>";
echo "<button class='btn btn-danger' type='submit' name='action' value='category/remove'>Remove</button>";
echo "</form>";
