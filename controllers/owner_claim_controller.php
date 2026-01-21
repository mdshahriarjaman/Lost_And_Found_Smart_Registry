<?php
session_start();
require_once "../models/ownerModel.php";


if (!isset($_SESSION["userId"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "OWNER") {
    header("Location: ../views/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../views/owners_view/owners_found_item.php");
    exit;
}

$item_id = (int)($_POST["item_id"] ?? 0);
$message = trim($_POST["message"] ?? "");

if ($item_id <= 0) {
    $_SESSION["claimErr"] = "Invalid item selected.";
    header("Location: ../views/owners_view/owners_found_item.php");
    exit;
}

if ($message == "" || strlen($message) < 10) {
    $_SESSION["claimErr"] = "Please write at least 10 characters proof message.";
    header("Location: ../views/owners_view/found_item_details.php?id=" . $item_id . "#claim");
    exit;
}

$owner_id = (int)$_SESSION["userId"];


$item = getFoundItemById($item_id);
if (!$item) {
    $_SESSION["claimErr"] = "Item not found.";
    header("Location: ../views/owners_view/owners_found_item.php");
    exit;
}


if ($item["status"] !== "Found") {
    $_SESSION["claimErr"] = "This item is not available anymore.";
    header("Location: ../views/owners_view/found_item_details.php?id=" . $item_id);
    exit;
}


if (ownerAlreadyClaimed($owner_id, $item_id)) {
    $_SESSION["claimErr"] = "You already sent a claim for this item.";
    header("Location: ../views/owners_view/found_item_details.php?id=" . $item_id);
    exit;
}


$claimId = createClaimAndMarkItem($owner_id, $item_id, $message);

if ($claimId > 0) {
    $_SESSION["claimOk"] = "Claim submitted successfully!";
    header("Location: ../views/owners_view/owner_my_claim.php");
    exit;
}

$_SESSION["claimErr"] = "Failed to submit claim. Try again.";
header("Location: ../views/owners_view/found_item_details.php?id=" . $item_id . "#claim");
exit;
