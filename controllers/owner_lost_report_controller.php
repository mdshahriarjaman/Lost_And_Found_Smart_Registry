<?php
session_start();
require_once "../models/ownerModel.php";

/* AUTH GUARD */
if (!isset($_SESSION["userId"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "OWNER") {
    header("Location: ../views/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../views/owners_view/owners_report.php");
    exit;
}

/* Collect data */
$title       = trim($_POST["title"] ?? "");
$category    = trim($_POST["category"] ?? "");
$color       = trim($_POST["color"] ?? "");
$lost_date   = trim($_POST["lost_date"] ?? "");
$location    = trim($_POST["location"] ?? "");
$description = trim($_POST["description"] ?? "");
$unique_mark = trim($_POST["unique_mark"] ?? "");

$hasErr = false;

/* Validation */
if ($title == "") {
    $_SESSION["titleErr"] = "Item name is required";
    $hasErr = true;
} else if (strlen($title) < 2) {
    $_SESSION["titleErr"] = "Item name is too short";
    $hasErr = true;
}

$allowedCats = ["Mobile","Wallet","Documents","ID Card","Keys","Bag","Other"];
if ($category == "") {
    $_SESSION["catErr"] = "Category is required";
    $hasErr = true;
} else if (!in_array($category, $allowedCats)) {
    $_SESSION["catErr"] = "Invalid category";
    $hasErr = true;
}

if ($lost_date == "") {
    $_SESSION["dateErr"] = "Lost date is required";
    $hasErr = true;
} else {
    // Date cannot be future
    $today = date("Y-m-d");
    if ($lost_date > $today) {
        $_SESSION["dateErr"] = "Lost date cannot be in the future";
        $hasErr = true;
    }
}

if ($location == "") {
    $_SESSION["locErr"] = "Location is required";
    $hasErr = true;
} else if (strlen($location) < 2) {
    $_SESSION["locErr"] = "Location is too short";
    $hasErr = true;
}

if ($description == "") {
    $_SESSION["descErr"] = "Description is required";
    $hasErr = true;
} else if (strlen($description) < 10) {
    $_SESSION["descErr"] = "Description must be at least 10 characters";
    $hasErr = true;
}

if ($color != "" && strlen($color) < 3) {
    $_SESSION["genErr"] = "Color is too short (optional field)";
    $hasErr = true;
}

if ($hasErr) {
    header("Location: ../views/owners_view/owners_report.php");
    exit;
}


$owner_id = (int)$_SESSION["userId"];
$newId = createLostReport($owner_id, $title, $category, $color, $lost_date, $location, $description, $unique_mark);

if ($newId > 0) {
    $_SESSION["okMsg"] = "Lost report submitted successfully!";
} else {
    $_SESSION["genErr"] = "Failed to submit. Try again.";
}

header("Location: ../views/owners_view/owners_report.php");
exit;
