<?php
session_start();


if (!isset($_SESSION["userId"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "OWNER") {
    header("Location: ../login.php");
    exit;
}

require_once "../../models/ownerModel.php";

$ownerName = $_SESSION["name"] ?? "Owner";

$q = $_GET["q"] ?? "";
$location = $_GET["location"] ?? "";
$found_date = $_GET["found_date"] ?? "";

$items = getFoundItems($q, $location, $found_date);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Found Items</title>
    <link rel="stylesheet" href="owners_found_item_style.css">
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
  <div class="page-box">
    <h2 class="page-title">Found Items</h2>
    <p class="page-subtitle">Browse found items and submit a claim if it matches yours.</p>

    <div class="filter-card">
      <form class="filter-form" action="" method="get">

        <div class="filter-row">
          <label for="q">Keyword</label>
          <input type="text" id="q" name="q" value="<?php echo htmlspecialchars($q); ?>"
                 placeholder="Search by item name or description">
        </div>

        <div class="filter-row">
          <label for="location">Location</label>
          <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>"
                 placeholder="e.g. Library, Canteen">
        </div>

        <div class="filter-row">
          <label for="found_date">Found Date</label>
          <input type="date" id="found_date" name="found_date" value="<?php echo htmlspecialchars($found_date); ?>">
        </div>

        <div class="filter-actions">
          <button type="submit" class="btn-primary">Search</button>
          <a href="owners_found_item.php" class="btn-secondary">Reset</a>
        </div>

      </form>
    </div>

    <div class="cards">
      <?php if (count($items) == 0) { ?>
        <div class="empty-state">
          No matching found items available. Try adjusting your search.
        </div>
      <?php } else { ?>
        <?php foreach ($items as $it) { ?>
          <div class="item-card">
            <div class="card-top">
              <h3 class="item-title"><?php echo htmlspecialchars($it["item"]); ?></h3>
              <span class="badge available">Available</span>
            </div>

            <div class="meta">
              <p><strong>Found by:</strong> <?php echo htmlspecialchars($it["name"]); ?></p>
              <p><strong>Found at:</strong> <?php echo htmlspecialchars($it["location"]); ?></p>
              <p><strong>Date:</strong> <?php echo htmlspecialchars($it["found_date"]); ?></p>
            </div>

            <p class="desc"><?php echo htmlspecialchars($it["description"] ?? ""); ?></p>

            <div class="card-actions">
              <a href="found_item_details.php?id=<?php echo (int)$it["id"]; ?>" class="btn-outline">View Details</a>
              <a href="found_item_details.php?id=<?php echo (int)$it["id"]; ?>#claim" class="btn-primary">Claim</a>
            </div>
          </div>
        <?php } ?>
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
