<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <!-- Additional head elements or CSS links if needed -->
</head>
<body>
    <form method="post" action="index.php">
        <fieldset>
            <legend>Login Form</legend>
            
            <!-- Username input -->
            <label for="username">Username:</label>
            <input class="form-control" type="text" name="username" id="username" placeholder="Enter username">
            <br>

            <!-- Password input -->
            <label for="password">Password:</label>
            <input class="form-control" type="password" name="password" id="password" placeholder="Enter password">
            <br>

            <!-- Submit button -->
            <button class="btn btn-primary" type="submit" name="action" value="login/submit">Submit</button>
        </fieldset>
    </form>
    <br>

    <?php
    // Check if an error message is passed from the MainController
    if (isset($params['error'])) {
        echo "<p class='error'>" . htmlspecialchars($params['error']) . "</p>";
    }
    ?>
</body>
</html>
