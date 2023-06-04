<?php
echo <<<EOT
<nav class="navbar navbar-default navbar-expand-sm navbar-light bg-primary">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php">Store</a>
    </div>
    <div>
    <ul class="nav navbar-nav">
      <li class="active"><a class="nav-link" href="index.php?action=home">Home</a></li>
EOT;

if (isset($_SESSION['userrole']) && $_SESSION['userrole'] === 'admin') {
    echo '<li><a class="nav-link" href="index.php?action=user">Users</a></li>';
}

echo <<<EOT
      <li><a class="nav-link" href="index.php?action=category">Categories</a></li>
      <li><a class="nav-link" href="index.php?action=product">Products</a></li>
      <li><a class="nav-link" href="index.php?action=warehouse">Warehouses</a></li>
    </ul>
    </div>
EOT;


if (isset($_SESSION['username'])) { // After the session has started
  echo "<p class='navbar-text'> Welcome, " . htmlspecialchars($_SESSION['fullname'], ENT_QUOTES, 'UTF-8') . "</p>"; 
  echo "<a class='btn btn-danger navbar-btn' href='index.php?action=logout'>Logout</a>"; // If we have a session, the user has already logged.
} else {
    echo "<a class='btn btn-info navbar-btn' href='index.php?action=loginform'>Login</a>"; //Otherwise if we have no values in the session, login shows
}

echo <<<EOT
  </div>
</nav>
EOT;
