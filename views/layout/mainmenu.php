<nav class="navbar navbar-default navbar-expand-sm navbar-light bg-primary">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php?action=home">Web Store</a>
    </div>
    <div>
      <ul class="nav navbar-nav">
        <li class="active"><a class="nav-link" href="index.php?action=home">Home</a></li>
      </ul>
      <?php
      if (isset($_SESSION['username'])) {
          // Display logout button if the user is logged in
          echo '<a class="btn btn-danger navbar-btn" href="index.php?action=logout">Logout</a>';
          echo '<a class="nav-link" href="index.php?action=order/manageOrderItems">Order an Item</a>';
          echo '<a class="nav-link" href="index.php?action=order/shoppingCart">Shopping Cart</a>';
          // Display the user's name
          echo '<p class="navbar-text">Welcome, ' . $_SESSION['username'] . '</p>';
      } else {
          // Display login button if the user is not logged in
          echo '<a class="btn btn-info navbar-btn" href="index.php?action=loginform">Login</a>';
      }
      ?>
    </div>
  </div>
</nav>
