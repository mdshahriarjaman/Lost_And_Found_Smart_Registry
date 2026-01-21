<?php
session_start();
require_once "../../models/adminModel.php";

if (!isset($_SESSION["userId"]) || $_SESSION["role"] !== "ADMIN") {
    header("Location: ../login.php");
    exit;
}

$lost = getAllLostReports();
$found = getAllFoundItems(false);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>All Reports</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:Arial;background:#f4f6f9;margin:0}
    .nav{background:#111;color:#fff;padding:15px;display:flex;justify-content:space-between}
    .nav a{color:#fff;margin:0 10px;text-decoration:none}
    .wrap{max-width:1100px;margin:20px auto;padding:15px}
    table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;margin-bottom:20px}
    th,td{border:1px solid #ddd;padding:10px;text-align:left}
    th{background:#f0f0f0}
  </style>
</head>
<body>

<div class="nav">
  <div><b>Admin</b></div>
  <div>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_items.php">All Reports</a>
    <a href="admin_verify_found.php">Verify Found</a>
    <a href="admin_claims.php">Claims</a>
    <a href="../../controllers/logout.php">Logout</a>
  </div>
</div>

<div class="wrap">
  <h2>All Lost Reports</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th><th>Owner</th><th>Title</th><th>Category</th><th>Lost Date</th><th>Location</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($lost as $r){ ?>
        <tr>
          <td><?php echo (int)$r["id"]; ?></td>
          <td><?php echo htmlspecialchars($r["full_name"]); ?></td>
          <td><?php echo htmlspecialchars($r["title"]); ?></td>
          <td><?php echo htmlspecialchars($r["category"]); ?></td>
          <td><?php echo htmlspecialchars($r["lost_date"]); ?></td>
          <td><?php echo htmlspecialchars($r["location"]); ?></td>
        </tr>
      <?php } ?>
      <?php if(count($lost)==0){ ?>
        <tr><td colspan="6">No lost reports.</td></tr>
      <?php } ?>
    </tbody>
  </table>

  <h2>All Found Reports</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th><th>Finder Name</th><th>Item</th><th>Location</th><th>Found Date</th><th>Verified</th><th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($found as $f){ ?>
        <tr>
          <td><?php echo (int)$f["id"]; ?></td>
          <td><?php echo htmlspecialchars($f["name"]); ?></td>
          <td><?php echo htmlspecialchars($f["item"]); ?></td>
          <td><?php echo htmlspecialchars($f["location"]); ?></td>
          <td><?php echo htmlspecialchars($f["found_date"]); ?></td>
          <td><?php echo ((int)$f["verified"]==1) ? "YES" : "NO"; ?></td>
          <td><?php echo htmlspecialchars($f["status"]); ?></td>
        </tr>
      <?php } ?>
      <?php if(count($found)==0){ ?>
        <tr><td colspan="7">No found reports.</td></tr>
      <?php } ?>
    </tbody>
  </table>
</div>

</body>
</html>
