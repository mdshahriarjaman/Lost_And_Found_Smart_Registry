<?php
session_start();
require_once __DIR__ . "/../models/userModel.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../views/login.php");
    exit;
}

$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$pass  = $_POST["password"] ?? "";

$hasErr = false;


if ($email == "") {
    $_SESSION["emailErr"] = "Email cannot be empty";
    $hasErr = true;
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["emailErr"] = "Invalid email format";
    $hasErr = true;
}


if ($pass == "") {
    $_SESSION["passErr"] = "Password cannot be empty";
    $hasErr = true;
} else if (strlen($pass) < 6) {
    $_SESSION["passErr"] = "Password must be at least 6 characters long";
    $hasErr = true;
}

if ($hasErr) {
    header("Location: ../views/login.php");
    exit;
}

$user = findUserByEmail($email);

if (!$user) {
    $_SESSION["genErr"] = "Email or password didn't match";
    header("Location: ../views/login.php");
    exit;
}

if (isset($user["status"]) && $user["status"] === "BLOCKED") {
    $_SESSION["genErr"] = "Your account is blocked. Contact admin.";
    header("Location: ../views/login.php");
    exit;
}

if (!password_verify($pass, $user["password_hash"])) {
    $_SESSION["genErr"] = "Email or password didn't match";
    header("Location: ../views/login.php");
    exit;
}


$_SESSION["userId"] = $user["id"];
$_SESSION["role"]   = $user["role"];
$_SESSION["name"]   = $user["full_name"];


if ($user["role"] == "ADMIN") {
    header("Location: ../views/admin_view/admin_dashboard.php");
    exit;
}
if ($user["role"] == "FINDER") {
    header("Location: ../views/finders_view/reportFoundItem.php");
    exit;
}
if ($user["role"] == "OWNER") {
    header("Location: ../views/owners_view/owners_report.php");
    exit;
}

$_SESSION["genErr"] = "Invalid role";
header("Location: ../views/login.php");
exit;
