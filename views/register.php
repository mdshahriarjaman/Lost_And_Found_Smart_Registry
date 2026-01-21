<?php
session_start();

$nameErr = $roleErr = $emailErr = $phoneErr = $addressErr = $passErr = $cpassErr = $genErr = "";

if(isset($_SESSION["nameErr"]))   $nameErr   = $_SESSION["nameErr"];
if(isset($_SESSION["roleErr"]))   $roleErr   = $_SESSION["roleErr"];
if(isset($_SESSION["emailErr"]))  $emailErr  = $_SESSION["emailErr"];
if(isset($_SESSION["phoneErr"]))  $phoneErr  = $_SESSION["phoneErr"];
if(isset($_SESSION["addressErr"]))$addressErr= $_SESSION["addressErr"];
if(isset($_SESSION["passErr"]))   $passErr   = $_SESSION["passErr"];
if(isset($_SESSION["cpassErr"]))  $cpassErr  = $_SESSION["cpassErr"];
if(isset($_SESSION["genErr"]))    $genErr    = $_SESSION["genErr"];

unset($_SESSION["nameErr"], $_SESSION["roleErr"], $_SESSION["emailErr"], $_SESSION["phoneErr"],
      $_SESSION["addressErr"], $_SESSION["passErr"], $_SESSION["cpassErr"], $_SESSION["genErr"]);
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link rel="stylesheet" href="register_style.css">
</head>

<body>

<div class="Panel">
    <div class="image"></div>

    <div class="container">
        <form action="../controllers/register_controllers.php" method="post">
            <h2>Registration</h2>

            <label>Full Name</label>
            <input type="text" name="name">
            <span class="err"><?php echo htmlspecialchars($nameErr); ?></span>

            <label>Role</label>
            <select name="role">
                <option value="">-- Select Role --</option>
                <option value="OWNER">Owner</option>
                <option value="FINDER">Finder</option>
            </select>
            <span class="err"><?php echo htmlspecialchars($roleErr); ?></span>

            <label>Email</label>
            <input type="text" name="email">
            <span class="err"><?php echo htmlspecialchars($emailErr); ?></span>

            <label>Phone</label>
            <input type="text" name="phone">
            <span class="err"><?php echo htmlspecialchars($phoneErr); ?></span>

            <label>Address</label>
            <textarea name="address"></textarea>
            <span class="err"><?php echo htmlspecialchars($addressErr); ?></span>

            <label>Password</label>
            
            <input type="password" id="password" name="password">
            <span class="err"><?php echo htmlspecialchars($passErr); ?></span>

            <label>Confirm Password</label>
          
            <input type="password" id="confirm_password" name="confirm_password">
            <span class="err"><?php echo htmlspecialchars($cpassErr); ?></span>

            <div class="showpass">
                <input type="checkbox" id="showpassword">
                <label for="showpassword"> Show Password</label>
            </div>

            <input class="btn" type="submit" name="submit" value="Register">

            <p class="signup">
                Already have an account?
                <a href="login.php">Login here</a>
            </p>

            <span class="err gen"><?php echo htmlspecialchars($genErr); ?></span>
        </form>
    </div>
</div>

<script>
document.getElementById("showpassword").addEventListener("change", function () {
    var t = this.checked ? "text" : "password";
    document.getElementById("password").type = t;
    document.getElementById("confirm_password").type = t;
});
</script>

</body>
</html>
