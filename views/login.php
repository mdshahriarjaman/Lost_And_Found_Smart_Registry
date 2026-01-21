<?php
session_start();

$emailErr = "";
$passErr  = "";
$genErr   = "";

if (isset($_SESSION["emailErr"])) $emailErr = $_SESSION["emailErr"];
if (isset($_SESSION["passErr"]))  $passErr  = $_SESSION["passErr"];
if (isset($_SESSION["genErr"]))   $genErr   = $_SESSION["genErr"];

unset($_SESSION["emailErr"], $_SESSION["passErr"], $_SESSION["genErr"]);
?>

<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="login_style.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
</head>

<body>
<div class="container">
    <h2>Login</h2>
    <p class="role">Login for <strong>Admin / Finder / Owner</strong></p>

    <form action="../controllers/login_controllers.php" method="post">

        <label>Email:</label>
        <input type="text" name="email"  id="email" placeholder="Enter your email">
        <span class="err"><?php echo htmlspecialchars($emailErr); ?></span>
        <br>

        <label>Password:</label>
        <input type="password" name="password" id="password" placeholder="Enter your password">
        <span class="err"><?php echo htmlspecialchars($passErr); ?></span>
        <br>

        <div class="showpass">
            <input type="checkbox" id="showpassword">
            <label for="showpassword"> Show Password</label>
        </div>
        <br>

        <input class="btn" type="submit" name="submit" value="Login">

        <p class="signup">
            Don't have an account? <a href="register.php">Sign Up</a>
        </p>

        <p class="signup">
        <a href="forgot_password.php">Forgot Password?</a>
</p>
    </form>

    <span class="err gen"><?php echo htmlspecialchars($genErr); ?></span>
</div>

<script>
document.getElementById("showpassword").addEventListener("change", function () {
    var passwordField = document.getElementById("password");
    passwordField.type = this.checked ? "text" : "password";
});
</script>

</body>
</html>
