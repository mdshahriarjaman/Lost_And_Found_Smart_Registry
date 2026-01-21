<?php
session_start();


if (!isset($_SESSION["userId"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "OWNER") {
    header("Location: ../login.php");
    exit;
}

require_once "../../models/ownerModel.php";

$ownerName = $_SESSION["name"] ?? "Owner";
$id = (int)($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: owners_found_item.php");
    exit;
}

$item = getFoundItemById($id);
if (!$item) {
    header("Location: owners_found_item.php");
    exit;
}

$claimErr = $_SESSION["claimErr"] ?? "";
$claimOk  = $_SESSION["claimOk"] ?? "";
unset($_SESSION["claimErr"], $_SESSION["claimOk"]);

$isAvailable = ($item["status"] === "Found");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Found Item Details</title>
  <link rel="stylesheet" href="owners_found_item_style.css">
  <style>
    .details-box{max-width:900px;margin:20px auto;background:#fff;padding:20px;border-radius:12px}
    .details-row{margin:8px 0}
    .msg{margin:10px 0;padding:10px;border-radius:8px}
    .msg.err{background:#ffe5e5;color:#a10000}
    .msg.ok{background:#e7ffe7;color:#0f6a0f}
    textarea{width:100%;min-height:120px}
  </style>
</head>
<body>

<header>
  <div class="navbar">
    <div class="nav-left"><span class="logo">Lost & Found</span></div>

    <nav class="nav-center">
    
      <a href="owners_report.php">Report Lost</a>
      <a href="owners_found_item.php" class="active">Found Items</a>
      <a href="owner_my_claim.php">My Claims</a>
    </nav>

    <div class="nav-right">
      <span class="user">Hi, <?php echo htmlspecialchars($ownerName); ?></span>
      <a href="../../controllers/logout.php" class="logout">Logout</a>
    </div>
  </div>
</header>

<main class="content">
  <div class="details-box">
    <h2><?php echo htmlspecialchars($item["item"]); ?></h2>

    <div class="details-row"><strong>Found by:</strong> <?php echo htmlspecialchars($item["name"]); ?></div>
    <div class="details-row"><strong>Location:</strong> <?php echo htmlspecialchars($item["location"]); ?></div>
    <div class="details-row"><strong>Found Date:</strong> <?php echo htmlspecialchars($item["found_date"]); ?></div>
    <div class="details-row"><strong>Status:</strong> <?php echo htmlspecialchars($item["status"]); ?></div>

    <div class="details-row">
      <strong>Description:</strong><br>
      <?php echo nl2br(htmlspecialchars($item["description"] ?? "")); ?>
    </div>

    <?php if (!empty($item["photo"])) { ?>
      <div class="details-row">
        <strong>Photo:</strong><br>
        <img src="../../asset/uploads/<?php echo htmlspecialchars($item["photo"]); ?>" style="max-width:300px;border-radius:10px;">
      </div>
    <?php } ?>

    <hr>

    <h3 id="claim">Submit Claim</h3>

    <?php if ($claimErr != "") { ?>
      <div class="msg err"><?php echo htmlspecialchars($claimErr); ?></div>
    <?php } ?>

    <?php if ($claimOk != "") { ?>
      <div class="msg ok"><?php echo htmlspecialchars($claimOk); ?></div>
    <?php } ?>

    <?php if ($isAvailable) { ?>
      <form action="../../controllers/owner_claim_controller.php" method="post">
        <input type="hidden" name="item_id" value="<?php echo (int)$item["id"]; ?>">

        <label><strong>Proof / Message (required)</strong></label><br>
        <textarea name="message" placeholder="Write proof: color, brand, unique marks, what inside, where you lost it..." required></textarea>
        <br><br>

        <button type="submit" class="btn-primary">Submit Claim</button>
        <a href="owners_found_item.php" class="btn-secondary">Back</a>
      </form>
    <?php } else { ?>
      <p>This item is not available for claim.</p>
      <a href="owners_found_item.php" class="btn-secondary">Back</a>
    <?php } ?>
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
