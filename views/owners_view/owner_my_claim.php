<?php
session_start();


if (!isset($_SESSION["userId"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "OWNER") {
    header("Location: ../login.php");
    exit;
}

require_once "../../models/ownerModel.php";

$ownerName = $_SESSION["name"] ?? "Owner";
$ownerId   = (int)$_SESSION["userId"];

$claims = getOwnerClaims($ownerId);

function claimStatusClass($status)
{
    if ($status == "PENDING") return "pending";
    if ($status == "APPROVED") return "approved";
    if ($status == "REJECTED") return "rejected";
    if ($status == "RETURNED") return "returned";
    return "pending";
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Claims</title>
    <link rel="stylesheet" href="owner_my_claim_style.css">
</head>
<body>

<header>
    <div class="navbar">
        <div class="nav-left">
            <span class="logo">Lost & Found</span>
        </div>

        <nav class="nav-center">
            
            <a href="owners_report.php">Report Lost</a>
            <a href="owners_found_item.php">Found Items</a>
            <a href="owner_my_claim.php" class="active">My Claims</a>
        </nav>

        <div class="nav-right">
            <span class="user">Hi, <?php echo htmlspecialchars($ownerName); ?></span>
            <a href="../../controllers/logout.php" class="logout">Logout</a>
        </div>
    </div>
</header>

<main class="content">
  <div class="page-box">
    <h2 class="page-title">My Claims</h2>
    <p class="page-subtitle">Track the status of your submitted claim requests.</p>

    <div class="table-card">

      <?php if (count($claims) == 0) { ?>
        <div class="empty-state">
          <p>You have not submitted any claim requests yet.</p>
          <a href="owners_found_item.php" class="btn-primary">Browse Found Items</a>
        </div>
      <?php } else { ?>

      <table class="claims-table">
        <thead>
          <tr>
            <th>Item</th>
            <th>Category</th>
            <th>Found Location</th>
            <th>Claim Date</th>
            <th>Status</th>
            
          </tr>
        </thead>

        <tbody>
          <?php foreach ($claims as $c) { 
              $status = $c["status"];
              $cls = claimStatusClass($status);
              $claimDate = date("Y-m-d", strtotime($c["created_at"]));
          ?>
            <tr>
              <td><?php echo htmlspecialchars($c["item"]); ?></td>
              <td>—</td>
              <td><?php echo htmlspecialchars($c["location"]); ?></td>
              <td><?php echo htmlspecialchars($claimDate); ?></td>
              <td><span class="status <?php echo htmlspecialchars($cls); ?>"><?php echo htmlspecialchars($status); ?></span></td>
             
            </tr>
          <?php } ?>
        </tbody>
      </table>

      <?php } ?>

    </div>
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
