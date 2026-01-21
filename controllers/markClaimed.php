<?php
session_start();
require_once "../models/foundItemModel.php";

header("Content-Type: application/json");

if (!isset($_SESSION["userId"]) || $_SESSION["role"] !== "FINDER") {
    echo json_encode(["ok" => false, "error" => "Unauthorized"]);
    exit;
}

$id = (int)($_POST["id"] ?? 0);
if ($id <= 0) {
    echo json_encode(["ok" => false, "error" => "Invalid id"]);
    exit;
}

$finder_id = (int)$_SESSION["userId"];
$ok = markItemAsClaimed($finder_id, $id);

echo json_encode(["ok" => $ok]);
exit;
