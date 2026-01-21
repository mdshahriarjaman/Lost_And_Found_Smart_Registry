<?php

require_once "db.php";

function findUserByEmail($email)
{
    $conn = dbConnect();
    $email = mysqli_real_escape_string($conn, $email);

    $query  = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    $user = null;
    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
    }

    mysqli_close($conn);
    return $user;
}

function emailExists($email)
{
    return findUserByEmail($email) != null;
}

function createUser($name, $email, $phone, $address, $hash, $role)
{
    $conn = dbConnect();

    $name    = mysqli_real_escape_string($conn, $name);
    $email   = mysqli_real_escape_string($conn, $email);
    $phone   = mysqli_real_escape_string($conn, $phone);
    $address = mysqli_real_escape_string($conn, $address);
    $hash    = mysqli_real_escape_string($conn, $hash);
    $role    = mysqli_real_escape_string($conn, $role);

    $query = "INSERT INTO users (full_name, email, phone, address, password_hash, role, status, created_at)
              VALUES ('$name', '$email', '$phone', '$address', '$hash', '$role', 'ACTIVE', NOW())";

    $result = mysqli_query($conn, $query);

    $id = 0;
    if ($result) {
        $id = mysqli_insert_id($conn);
    }

    mysqli_close($conn);
    return $id;
}
function updateUserPassword($email, $newHash)
{
    $conn = dbConnect();

    $email = mysqli_real_escape_string($conn, $email);
    $newHash = mysqli_real_escape_string($conn, $newHash);

    $sql = "UPDATE users SET password_hash='$newHash' WHERE email='$email' LIMIT 1";
    $ok = mysqli_query($conn, $sql);

    mysqli_close($conn);
    return $ok ? true : false;
}

function findUserByEmailAndPhone($email, $phone)
{
    $conn = dbConnect();

    $email = mysqli_real_escape_string($conn, $email);
    $phone = mysqli_real_escape_string($conn, $phone);

    $sql = "SELECT id, email, phone FROM users WHERE email='$email' AND phone='$phone' LIMIT 1";
    $res = mysqli_query($conn, $sql);

    $user = null;
    if ($res && mysqli_num_rows($res) == 1) {
        $user = mysqli_fetch_assoc($res);
    }

    mysqli_close($conn);
    return $user;
}
