<?php
session_start();


if (!isset($_SESSION["userId"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "OWNER") {
    header("Location: ../login.php");
    exit;
}


$titleErr = $_SESSION["titleErr"] ?? "";
$catErr   = $_SESSION["catErr"] ?? "";
$dateErr  = $_SESSION["dateErr"] ?? "";
$locErr   = $_SESSION["locErr"] ?? "";
$descErr  = $_SESSION["descErr"] ?? "";
$genErr   = $_SESSION["genErr"] ?? "";
$okMsg    = $_SESSION["okMsg"] ?? "";

unset($_SESSION["titleErr"], $_SESSION["catErr"], $_SESSION["dateErr"], $_SESSION["locErr"],
      $_SESSION["descErr"], $_SESSION["genErr"], $_SESSION["okMsg"]);

$ownerName = $_SESSION["name"] ?? "Owner";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Report Lost</title>
    <link rel="stylesheet" href="owners_report_style.css">
</head>
<body>

<header>
    <div class="navbar">
        <div class="nav-left"><span class="logo">Lost & Found</span></div>

        <nav class="nav-center">
           
            <a href="owners_report.php" class="active">Report Lost</a>
            <a href="owners_found_item.php">Found Items</a>
            <a href="owner_my_claim.php">My Claims</a>
        </nav>

        <div class="nav-right">
            <span class="user">Hi, <?php echo htmlspecialchars($ownerName); ?></span>
            
            <a href="../../controllers/logout.php" class="logout">Logout</a>
        </div>
    </div>
</header>

<main class="content">
  <div class="page-box">
    <h2 class="page-title">Report Lost Item</h2>
    <p class="page-subtitle">Fill in the details carefully so your item can be matched faster.</p>

    <?php if ($genErr != "") { ?>
      <p style="color:red;"><?php echo htmlspecialchars($genErr); ?></p>
    <?php } ?>

    <?php if ($okMsg != "") { ?>
      <p style="color:green;"><?php echo htmlspecialchars($okMsg); ?></p>
    <?php } ?>

    <form class="form-card" action="../../controllers/owner_lost_report_controller.php" method="post">

      <div class="form-row">

        <label for="title">Item Name <span class="req">*</span></label>
        <input type="text" id="title" name="title">
        <span style="color:red;"><?php echo htmlspecialchars($titleErr); ?></span>

        <label for="category">Category <span class="req">*</span></label>
        <select id="category" name="category">
          <option value="">-- Select Category --</option>
          <option value="Mobile">Mobile</option>
          <option value="Wallet">Wallet</option>
          <option value="Documents">Documents</option>
          <option value="ID Card">ID Card</option>
          <option value="Keys">Keys</option>
          <option value="Bag">Bag</option>
          <option value="Other">Other</option>
        </select>
        <span style="color:red;"><?php echo htmlspecialchars($catErr); ?></span>

        <label for="color">Color</label>
        <input type="text" id="color" name="color" placeholder="Like-Black, Blue, Brown">

        <label for="lost_date">Date Lost <span class="req">*</span></label>
        <input type="date" id="lost_date" name="lost_date">
        <span style="color:red;"><?php echo htmlspecialchars($dateErr); ?></span>

        <label for="location">Last Seen Location <span class="req">*</span></label>
        <input type="text" id="location" name="location">
        <span style="color:red;"><?php echo htmlspecialchars($locErr); ?></span>

        <label for="description">Description <span class="req">*</span></label>
        <textarea id="description" name="description" placeholder="Write identifying details"></textarea>
        <span style="color:red;"><?php echo htmlspecialchars($descErr); ?></span>

        <label for="unique_mark">Unique Identification (optional)</label>
        <input type="text" id="unique_mark" name="unique_mark">

      </div>

      <div class="btn-row">
        <input class="btn-primary" type="submit" value="Submit Lost Report">
        <a class="btn-secondary" href="owner_dashboard.php">Cancel</a>
      </div>

    </form>
  </div>
</main>

<footer>
    <div class="footer-content">
        <p><strong>Lost & Found Smart Registry</strong></p>
        <p>Helping people recover lost belongings efficiently.</p>
        <p>&copy; 2026 Lost & Found System | All Rights Reserved</p>
    </div>
</footer>

</body>
</html>
