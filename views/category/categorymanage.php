<h2>Category Management page</h2>

<?php if (isset($params['message'])): ?>
<div class='alert alert-warning'>
<strong><?php echo $params['message']; ?></strong>
</div>
<?php endif ?>

<form method="post">
<div class="row g-3 align-items-center">
    <span class="col-auto">
        <a href="index.php?action=category/add" class="btn btn-primary">Add</a>
    </span>
</div>

</form>

<?php
$list = $params['list'] ?? null;

if (isset($list)) {
    echo <<<EOT
        <table class="table table-sm table-bordered table-striped table-hover caption-top table-responsive-sm">
        <caption>List of Categories</caption>
        <thead class='table-dark'>
        <tr>
            <th>Code</th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody>
EOT;
    foreach ($list as $elem) {
        echo <<<EOT
            <tr>
                <td><a href="index.php?action=category/edit&id={$elem->getId()}">{$elem->getCode()}</a></td>
                <td>{$elem->getDescription()}</td>
            </tr>               
EOT;
    }
    echo "</tbody>";
    echo "</table>";
    echo "<div class='alert alert-info' role='alert'>";
    echo count($list), " elements found.";
    echo "</div>";   
} else {
    echo "No data found";
}
?>
