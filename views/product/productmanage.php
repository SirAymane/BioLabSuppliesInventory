<h2>Product management page</h2>

<form method="post">
  <div class="row g-3 align-items-center">
    <span class="col-auto">
      <label for="category" class="col-form-label">Category to search</label>
    </span>
    <span class="col-auto">
      <input type="text" id="category" name="category" class="form-control">
    </span>
    <span class="col-auto">
      <button class="btn btn-primary" type="submit" name="action" value="product/search">Search</button>
    </span>
    <span class="col-auto">
      <button class="btn btn-primary" type="submit" name="action" value="product/add">Add</button>
    </span>
  </div>
</form>


<?php if (isset($params['message'])) : ?>
  <br>
  <div class='alert alert-warning'>
    <strong><?php echo $params['message']; ?></strong>
  </div>
<?php endif ?>

<?php
$list = $params['list'] ?? null;
if (isset($list)) {
  echo <<<EOT
        <table class="table table-sm table-bordered table-striped table-hover caption-top table-responsive-sm">
        <caption>List of users</caption>
        <thead class='table-dark'>
        <tr>
            <th>Code</th>
            <th>Description</th>
            <th>Price</th>
            <th>Category ID</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
EOT;
  foreach ($list as $elem) {
    echo <<<EOT
        <tr>
          <td><a href="index.php?action=product/edit&id={$elem->getId()}">{$elem->getCode()}</a></td>
          <td>{$elem->getDescription()}</td>
          <td>{$elem->getPrice()}</td>
            <td>{$elem->getCategoryId()}</td>
            <td>
                <a href='index.php?action=product/showStocks&id={$elem->getId()}' class='btn btn-primary'>Check Stocks</a>&emsp;
                <a href='index.php?action=product/edit&id={$elem->getId()}' class='btn btn-warning'>Edit</a>&emsp;
                <a href='index.php?action=product/delete&id={$elem->getId()}' class='btn btn-danger' onclick='return confirm("Are you sure you want to delete this product?");'>Delete</a>
            </td>
        </tr>               
  EOT;
  }
  echo "</tbody>";
  echo "</table>";
  echo "<div class='alert alert-info' role='alert'>";
  echo count($list), " elements found.";
  echo "</div>";
}
?>
