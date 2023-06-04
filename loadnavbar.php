<?php
/**
 * navigation bar 
 */

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$menupath = "views/mainmenu.php"; //default value.
if (isset($_SESSION['userrole'])) {
    $userrole = filter_var($_SESSION['userrole'], FILTER_DEFAULT);
    if ($userrole) {
        switch ($userrole) {
            case "admin":
                $menupath = "views/admin/adminmenu.php";
                break;    
        }
    }
}
//ensure to show admin menu (only for testing).
$menupath = "views/admin/adminmenu.php";
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <nav>
        <!-- Include your menu -->
        <?php include $menupath; ?>
    </nav>
    <!-- Your content goes here -->
</body>
</html>
