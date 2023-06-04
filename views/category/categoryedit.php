<?php
require_once 'lib/Renderer.php';
require_once 'model/Category.php';
use proven\store\model\Category;
echo "<p>Category detail page</p>";
$addDisable = "";
$editDisable = "disabled";
if ($params['mode']!='add') {
    $addDisable = "disabled";
    $editDisable = "";
}
$mode = "category/{$params['mode']}";
$message = $params['message'] ?? "";
printf("<p>%s</p>", $message);
if (isset($params['mode'])) {
    printf("<p>mode: %s</p>", $mode);
}
$category = $params['category'] ?? new Category();
echo "<form method='post' action=\"index.php?action=category/{$params['mode']}\">";
echo proven\lib\views\Renderer::renderCategoryFields($category);
echo "<button class='btn btn-primary' type='submit' name='action' value='category/add' $addDisable>Add</button>";
echo "<button class='btn btn-warning' type='submit' name='action' value='category/modify' $editDisable>Modify</button>";
echo "<button class='btn btn-danger' type='submit' name='action' value='category/remove' $editDisable>Remove</button>";
echo "</form>";
