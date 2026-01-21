<?php
session_start();
require_once "../../models/adminModel.php";

if (!isset($_SESSION["userId"]) || $_SESSION["role"] !== "ADMIN") {
    header("Location: ../login.php");
    exit;
}

$stats = getAdminStats();
$adminName = $_SESSION["name"] ?? "Admin";
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:Arial;background:#f4f6f9;margin:0}
    .nav{background:#111;color:#fff;padding:15px;display:flex;justify-content:space-between}
    .nav a{color:#fff;margin:0 10px;text-decoration:none}
    .wrap{max-width:1000px;margin:20px auto;padding:15px}
    .grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
    .card{background:#fff;padding:18px;border-radius:10px}
    .big{font-size:26px;font-weight:bold}
  </style>
</head>
<body>

<div class="nav">
  <div><b>Lost & Found - Admin</b></div>
  <div>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_items.php">All Reports</a>
    <a href="admin_verify_found.php">Verify Found</a>
    <a href="admin_claims.php">Claims</a>
    <a href="../../controllers/logout.php">Logout</a>
  </div>
</div>

<div class="wrap">
  <h2>Welcome, <?php echo htmlspecialchars($adminName); ?></h2>

  <div class="grid">
    <div class="card"><div class="big"><?php echo $stats["total_users"]; ?></div>Total Users</div>
    <div class="card"><div class="big"><?php echo $stats["total_lost"]; ?></div>Lost Reports</div>
    <div class="card"><div class="big"><?php echo $stats["total_found"]; ?></div>Found Reports</div>
    <div class="card"><div class="big"><?php echo $stats["pending_verify"]; ?></div>Pending Verification</div>
    <div class="card"><div class="big"><?php echo $stats["pending_claims"]; ?></div>Pending Claims</div>
    <div class="card"><div class="big"><?php echo $stats["returned_claims"]; ?></div>Returned</div>
  </div>
</div>
</body>
</html>
