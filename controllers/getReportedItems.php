<?php
session_start();
require_once "../models/finderModel.php";

header("Content-Type: application/json");

if (!isset($_SESSION["userId"]) || $_SESSION["role"] !== "FINDER") {
    echo json_encode(["ok" => false, "error" => "Unauthorized"]);
    exit;
}

$finder_id = (int)$_SESSION["userId"];
$items = loadMyReportedItem($finder_id);

echo json_encode(["ok" => true, "items" => $items]);
exit;
