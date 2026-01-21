<?php
session_start();
require_once "../models/finderModel.php";


if (!isset($_SESSION["userId"]) || $_SESSION["role"] !== "FINDER") {
    header("Location: ../views/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../views/finders_view/reportFoundItem.php");
    exit;
}


$finder_id = (int)$_SESSION["userId"];
$name = $_SESSION["name"] ?? "";
if ($name === "") {
    $name = trim($_POST["name"] ?? "Finder");
}


$item = trim($_POST["item"] ?? "");
$location = trim($_POST["location"] ?? "");
$foundDate = $_POST["foundDate"] ?? "";
$description = trim($_POST["description"] ?? "");


if ($item === "Others") {
    $itemOther = trim($_POST["itemOther"] ?? "");
    if ($itemOther === "")
        {
        echo "<script>alert('Please enter other item'); window.history.back();</script>";
        exit;
    }
else if (is_numeric($itemOther)) {
        echo "<script>alert('Invalid other item'); window.history.back();</script>";
        exit;
    }

    $item = $itemOther;
}
if ($location === "Others") {
    $locationOther = trim($_POST["locationOther"] ?? "");
    if ($locationOther === "") {
        echo "<script>alert('Please enter other location'); window.history.back();</script>";
        exit;
    }
    else if (is_numeric($locationOther)) {
        echo "<script>alert('Invalid other location'); window.history.back();</script>";
        exit;
    }
    
    $location = $locationOther;
}


if ($item === "") { echo "<script>alert('Item is required'); window.history.back();</script>"; exit; }
if ($location === "") { echo "<script>alert('Location is required'); window.history.back();</script>"; exit; }
if ($foundDate === "") { echo "<script>alert('Found date is required'); window.history.back();</script>"; exit; }
if ($description === "" || strlen($description) < 5) {
    echo "<script>alert('Description is required (min 5 chars)'); window.history.back();</script>";
    exit;
}


$photo = "";
if (!empty($_FILES["photo"]["name"])) {
    $ext = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
    $allowed = ["jpg","jpeg","png","webp"];

    if (!in_array($ext, $allowed)) {
        echo "<script>alert('Invalid image type'); window.history.back();</script>";
        exit;
    }

    
    $photo = uniqid("item_") . "." . $ext;

    $dest = "../asset/uploads/" . $photo;

    if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $dest)) {
        echo "<script>alert('Photo upload failed'); window.history.back();</script>";
        exit;
    }
}


$ok = insertFoundItem($finder_id, $name, $item, $location, $foundDate, $description, $photo);

if ($ok) {
    echo "<script>
                window.location.href = '../views/finders_view/viewMyReportedItem.php';
          </script>";   
    exit;
}

echo "<script>alert('Database error'); window.history.back();</script>";
exit;
