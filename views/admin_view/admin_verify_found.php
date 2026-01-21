<?php
session_start();
require_once "../../models/adminModel.php";

if (!isset($_SESSION["userId"]) || $_SESSION["role"] !== "ADMIN") {
    header("Location: ../login.php");
    exit;
}

$msg = $_SESSION["adminMsg"] ?? "";
unset($_SESSION["adminMsg"]);

$items = getAllFoundItems(true); // only unverified
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Verify Found Items</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:Arial;background:#f4f6f9;margin:0}
    .nav{background:#111;color:#fff;padding:15px;display:flex;justify-content:space-between}
    .nav a{color:#fff;margin:0 10px;text-decoration:none}
    .wrap{max-width:1100px;margin:20px auto;padding:15px}
    .msg{padding:10px;background:#e7ffe7;border-radius:8px;margin-bottom:10px}
    table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden}
    th,td{border:1px solid #ddd;padding:10px;text-align:left}
    th{background:#f0f0f0}
    .btn{padding:6px 10px;background:#0b6;color:#fff;text-decoration:none;border-radius:6px}
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
  <h2>Verify Found Items</h2>

  <?php if($msg!=""){ ?>
    <div class="msg"><?php echo htmlspecialchars($msg); ?></div>
  <?php } ?>

  <table>
    <thead>
      <tr>
        <th>ID</th><th>Finder</th><th>Item</th><th>Location</th><th>Date</th>  <th>Photo</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($items as $f){ ?>
        <tr>
          <td><?php echo (int)$f["id"]; ?></td>
          <td><?php echo htmlspecialchars($f["name"]); ?></td>
          <td><?php echo htmlspecialchars($f["item"]); ?></td>
          <td><?php echo htmlspecialchars($f["location"]); ?></td>
          <td><?php echo htmlspecialchars($f["found_date"]); ?></td>
              <td>
        <?php if (!empty($f["photo"])) { ?>
          <img 
            src="../../asset/uploads/<?php echo htmlspecialchars($f["photo"]); ?>"
            style="max-width:120px; max-height:120px; border-radius:6px; border:1px solid #ccc;"
            alt="Found item photo">
        <?php } else { ?>
          <span>No photo</span>
        <?php } ?>
      </td>
          <td>
            <a class="btn" href="../../controllers/admin_verify_controller.php?id=<?php echo (int)$f["id"]; ?>">
              Verify
            </a>
          </td>
        </tr>
      <?php } ?>
      <?php if(count($items)==0){ ?>
        <tr><td colspan="6">No pending found items to verify.</td></tr>
      <?php } ?>
    </tbody>
  </table>
</div>

</body>
</html>
