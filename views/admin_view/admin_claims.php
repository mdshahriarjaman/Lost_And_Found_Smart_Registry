<?php
session_start();
require_once "../../models/adminModel.php";

if (!isset($_SESSION["userId"]) || $_SESSION["role"] !== "ADMIN") {
    header("Location: ../login.php");
    exit;
}

$msg = $_SESSION["adminMsg"] ?? "";
unset($_SESSION["adminMsg"]);

$claims = getAllClaims();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Manage Claims</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:Arial;
    background:#f4f6f9;
    margin:0}

    .nav{background:#111;
    color:#fff;
    padding:15px;
    display:flex;
    justify-content:space-between}

    .nav a{color:#fff;
    margin:0 10px;
    text-decoration:none}

    .wrap{max-width:1200px;
    margin:20px auto;
    padding:15px}

    .msg{padding:10px;
    background:#e7ffe7;
    border-radius:8px;
    margin-bottom:10px}

    table{width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:10px;
    overflow:hidden}

    th,td{border:1px solid #ddd;
    padding:10px;
    text-align:left;
    vertical-align:top}
    th{background:#f0f0f0}

    .btn{padding:6px 10px;
    text-decoration:none;
    border-radius:6px;color:#fff;
    margin-right:6px;
    
    display:inline-block}
    .a{background:#0b6}
    .r{background:#c33}
    .ret{background:#555}
    .tag{padding:4px 8px;border-radius:10px;background:#eee;display:inline-block}
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
  <h2>Claim Requests</h2>

  <?php if($msg!=""){ ?>
    <div class="msg"><?php echo htmlspecialchars($msg); ?></div>
  <?php } ?>

  <table>
    <thead>
      <tr>
        <th>ID</th><th>Owner</th><th>Item</th><th>Location</th><th>Claim Date</th><th>Status</th><th>Proof Message</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($claims as $c){ ?>
        <tr>
          <td><?php echo (int)$c["claim_id"]; ?></td>
          <td><?php echo htmlspecialchars($c["owner_name"]); ?><br><?php echo htmlspecialchars($c["owner_email"]); ?></td>
          <td><?php echo htmlspecialchars($c["item"]); ?></td>
          <td><?php echo htmlspecialchars($c["location"]); ?></td>
          <td><?php echo htmlspecialchars(date("Y-m-d", strtotime($c["created_at"]))); ?></td>
          <td><span class="tag"><?php echo htmlspecialchars($c["status"]); ?></span></td>
          <td><?php echo nl2br(htmlspecialchars($c["message"] ?? "")); ?></td>
          <td>
            <a class="btn a" href="../../controllers/admin_claim_controller.php?action=approve&id=<?php echo (int)$c["claim_id"]; ?>">Approve</a>
            <a class="btn r" href="../../controllers/admin_claim_controller.php?action=reject&id=<?php echo (int)$c["claim_id"]; ?>">Reject</a>
            <a class="btn ret" href="../../controllers/admin_claim_controller.php?action=return&id=<?php echo (int)$c["claim_id"]; ?>">Returned</a>
          </td>
        </tr>
      <?php } ?>
      <?php if(count($claims)==0){ ?>
        <tr><td colspan="8">No claims found.</td></tr>
      <?php } ?>
    </tbody>
  </table>
</div>

</body>
</html>
