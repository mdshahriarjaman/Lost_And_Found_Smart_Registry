<?php
session_start();

$err = $_SESSION["fpErr"] ?? "";
$msg = $_SESSION["fpMsg"] ?? "";
unset($_SESSION["fpErr"], $_SESSION["fpMsg"]);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Forgot Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="forgot_password.css">

</head>
<body>
<div class="fp-card">

  <h2>Forgot Password</h2>

  <?php if($err!=""){ ?>
    <div class="fp-error"><?php echo htmlspecialchars($err); ?></div>
  <?php } ?>

  <?php if($msg!=""){ ?>
    <div class="fp-success"><?php echo htmlspecialchars($msg); ?></div>
  <?php } ?>

  <form action="../controllers/forgot_password_controller.php" method="post">

    <label>Email</label>
    <input type="text" name="email" placeholder="Enter your email">

    <label>Phone</label>
    <input type="text" name="phone" placeholder="Enter your phone">

    <label>New Password</label>
    <input type="password" name="new_password">

    <label>Confirm Password</label>
    <input type="password" name="confirm_password">

    <button type="submit">Reset Password</button>
  </form>

  <div class="back-link">
    <a href="login.php">Back to Login</a>
  </div>

</div>


</body>
</html>
