<?php
session_start();
require_once __DIR__ . "/../models/userModel.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../views/register.php");
    exit;
}

$name    = isset($_POST["name"]) ? trim($_POST["name"]) : "";
$role    = isset($_POST["role"]) ? trim($_POST["role"]) : "";
$email   = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$phone   = isset($_POST["phone"]) ? trim($_POST["phone"]) : "";
$address = isset($_POST["address"]) ? trim($_POST["address"]) : "";
$pass    = $_POST["password"] ?? "";
$cpass   = $_POST["confirm_password"] ?? "";

$hasErr = false;


if ($name == "") {
    $_SESSION["nameErr"] = "Name cannot be empty";
    $hasErr = true;
}


if ($role == "") {
    $_SESSION["roleErr"] = "Role is required";
    $hasErr = true;
} else if ($role != "OWNER" && $role != "FINDER") {
    $_SESSION["roleErr"] = "Invalid role selected";
    $hasErr = true;
}


if ($email == "") {
    $_SESSION["emailErr"] = "Email cannot be empty";
    $hasErr = true;
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["emailErr"] = "Invalid email format";
    $hasErr = true;
} else if (emailExists($email)) {
    $_SESSION["emailErr"] = "Email already registered";
    $hasErr = true;
}


if ($phone != "" && !preg_match("/^[0-9]{10,15}$/", $phone)) {
    $_SESSION["phoneErr"] = "Phone number must be 10-15 digits";
    $hasErr = true;
}


if ($address != "" && strlen($address) < 5) {
    $_SESSION["addressErr"] = "Address too short";
    $hasErr = true;
}


if ($pass == "") {
    $_SESSION["passErr"] = "Password cannot be empty";
    $hasErr = true;
} else if (strlen($pass) < 6) {
    $_SESSION["passErr"] = "Password must be at least 6 characters";
    $hasErr = true;
} else if (!preg_match("/[A-Z]/", $pass)) {
    $_SESSION["passErr"] = "Password must contain at least one uppercase letter";
    $hasErr = true;
} else if (!preg_match("/[a-z]/", $pass)) {
    $_SESSION["passErr"] = "Password must contain at least one lowercase letter";
    $hasErr = true;
} else if (!preg_match("/[0-9]/", $pass)) {
    $_SESSION["passErr"] = "Password must contain at least one digit";
    $hasErr = true;
}


if ($cpass == "") {
    $_SESSION["cpassErr"] = "Confirm password cannot be empty";
    $hasErr = true;
} else if ($pass != $cpass) {
    $_SESSION["cpassErr"] = "Passwords do not match";
    $hasErr = true;
}

if ($hasErr) {
    header("Location: ../views/register.php");
    exit;
}

$hash = password_hash($pass, PASSWORD_DEFAULT);

$phoneToSave = ($phone == "") ? "" : $phone;
$addrToSave  = ($address == "") ? "" : $address;

$newId = createUser($name, $email, $phoneToSave, $addrToSave, $hash, $role);

if ($newId > 0) {
    $_SESSION["genErr"] = "Registration successful! Please login.";
    header("Location: ../views/login.php");
    exit;
}

$_SESSION["genErr"] = "Registration failed. Try again.";
header("Location: ../views/register.php");
exit;
