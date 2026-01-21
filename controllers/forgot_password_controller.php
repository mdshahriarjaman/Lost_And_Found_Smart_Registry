<?php
session_start();
require_once __DIR__ . "/../models/userModel.php";
$post = $_POST; 

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../views/forgot_password.php");
    exit;
}

$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : "";
$newPass = $_POST["new_password"] ?? "";
$confirmPass = $_POST["confirm_password"] ?? "";

$hasErr = false;

// validations
if ($email === "") {
    $_SESSION["emailErr"] = "Email cannot be empty";
    $hasErr = true;
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["emailErr"] = "Invalid email format";
    $hasErr = true;
}

if ($phone === "") {
    $_SESSION["phoneErr"] = "Phone cannot be empty";
    $hasErr = true;
} else if (!preg_match("/^[0-9]{10,15}$/", $phone)) {
    $_SESSION["phoneErr"] = "Phone number must be 10-15 digits";
    $hasErr = true;
}

if ($newPass === "") {
    $_SESSION["passErr"] = "New password cannot be empty";
    $hasErr = true;
} else if (strlen($newPass) < 6) {
    $_SESSION["passErr"] = "Password must be at least 6 characters";
    $hasErr = true;
}

if ($confirmPass === "") {
    $_SESSION["cpassErr"] = "Confirm password cannot be empty";
    $hasErr = true;
} else if ($newPass !== $confirmPass) {
    $_SESSION["cpassErr"] = "Passwords do not match";
    $hasErr = true;
}

if ($hasErr) {
    header("Location: ../views/forgot_password.php");
    exit;
}

// check user by email+phone
$user = findUserByEmailAndPhone($email, $phone);
if ($user === null) {
    $_SESSION["genErr"] = "No user found with this email and phone";
    header("Location: ../views/forgot_password.php");
    exit;
}

// update password
$newHash = password_hash($newPass, PASSWORD_DEFAULT);

$ok = updateUserPassword($email, $newHash);
if ($ok) {
    $_SESSION["genErr"] = "Password updated successfully. Please login.";
    header("Location: ../views/login.php");
    exit;
}

$_SESSION["genErr"] = "Password update failed. Try again.";
header("Location: ../views/forgot_password.php");
exit;
