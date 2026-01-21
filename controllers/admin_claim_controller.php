<?php
session_start();
require_once "../models/adminModel.php";


if (!isset($_SESSION["userId"]) || ($_SESSION["role"] ?? "") !== "ADMIN") {
    header("Location: ../views/login.php");
    exit;
}

$claim_id = (int)($_GET["id"] ?? 0);
$action   = strtolower(trim($_GET["action"] ?? "")); 

if ($claim_id <= 0) {
    $_SESSION["adminMsg"] = "Invalid claim id.";
    header("Location: ../views/admin_view/admin_claims.php");
    exit;
}

$map = [
    "approve" => "APPROVED",
    "reject"  => "REJECTED",
    "return"  => "RETURNED",
];

if (!isset($map[$action])) {
    $_SESSION["adminMsg"] = "Invalid action.";
    header("Location: ../views/admin_view/admin_claims.php");
    exit;
}

$ok = updateClaimStatus($claim_id, $map[$action]);

$_SESSION["adminMsg"] = $ok ? "Claim updated!" : "Failed to update claim.";
header("Location: ../views/admin_view/admin_claims.php");
exit;
