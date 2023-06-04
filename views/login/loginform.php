<form  method="post" action="index.php">
    <fieldset>
        <legend>Login form</legend>
        <label for="username">Username:</label>
        <input  class="form-control" type="text" name="username" id="username" placeholder="Enter username">
        <br>
        <label for="password">Password:</label>
        <input  class="form-control" type="password" name="password" id="password" placeholder="Enter password" value="">
        <br>
        <button class="btn btn-primary" type="submit" name="action" <?php if (isset($_SESSION['username'])) { echo 'disabled'; } ?> value="login/submit">Submit</button>
    </fieldset>
</form>
<br>
<?php
if (isset($_SESSION['username'])) { echo '<p>Already logged!</p>'; } 
$username = $params['username'] ?? null;
$message = $params['message'] ?? null;
if (!is_null($message)) {
    echo sprintf("<p>%s</p>", $message);
}
?>