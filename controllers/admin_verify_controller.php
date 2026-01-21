<?php
session_start();
require_once "../models/adminModel.php";


if (!isset($_SESSION["userId"]) || $_SESSION["role"] !== "ADMIN") {
    header("Location: ../views/login.php");
    exit;
}

$id = (int)($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: ../views/admin_view/admin_verify_found.php");
    exit;
}

$ok = verifyFoundItem($id);

$_SESSION["adminMsg"] = $ok ? "Item verified successfully!" : "Failed to verify.";
header("Location: ../views/admin_view/admin_verify_found.php");
exit;
