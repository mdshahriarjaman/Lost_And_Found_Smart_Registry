<?php
require_once "db.php";


function getAdminStats()
{
    $conn = dbConnect();

    $stats = [
        "total_users" => 0,
        "total_lost" => 0,
        "total_found" => 0,
        "pending_verify" => 0,
        "pending_claims" => 0,
        "returned_claims" => 0
    ];

    $q = mysqli_query($conn, "SELECT COUNT(*) AS c FROM users");
    if ($q) $stats["total_users"] = (int)mysqli_fetch_assoc($q)["c"];

    $q = mysqli_query($conn, "SELECT COUNT(*) AS c FROM lost_reports");
    if ($q) $stats["total_lost"] = (int)mysqli_fetch_assoc($q)["c"];

    $q = mysqli_query($conn, "SELECT COUNT(*) AS c FROM found_items");
    if ($q) $stats["total_found"] = (int)mysqli_fetch_assoc($q)["c"];

    $q = mysqli_query($conn, "SELECT COUNT(*) AS c FROM found_items WHERE verified=0");
    if ($q) $stats["pending_verify"] = (int)mysqli_fetch_assoc($q)["c"];

    $q = mysqli_query($conn, "SELECT COUNT(*) AS c FROM claims WHERE status='PENDING'");
    if ($q) $stats["pending_claims"] = (int)mysqli_fetch_assoc($q)["c"];

    $q = mysqli_query($conn, "SELECT COUNT(*) AS c FROM claims WHERE status='RETURNED'");
    if ($q) $stats["returned_claims"] = (int)mysqli_fetch_assoc($q)["c"];

    mysqli_close($conn);
    return $stats;
}

function getAllLostReports()
{
    $conn = dbConnect();
    $sql = "SELECT lr.*, u.full_name 
            FROM lost_reports lr
            JOIN users u ON u.id = lr.owner_id
            ORDER BY lr.id DESC";

    $res = mysqli_query($conn, $sql);
    $rows = [];
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
    }
    mysqli_close($conn);
    return $rows;
}


function getAllFoundItems($onlyUnverified=false)
{
    $conn = dbConnect();

    $sql = "SELECT * FROM found_items";
    if ($onlyUnverified) $sql .= " WHERE verified=0";
    $sql .= " ORDER BY id DESC";

    $res = mysqli_query($conn, $sql);
    $rows = [];
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
    }
    mysqli_close($conn);
    return $rows;
}


function verifyFoundItem($id)
{
    $conn = dbConnect();
    $id = (int)$id;

    $ok = mysqli_query($conn, "UPDATE found_items SET verified=1 WHERE id=$id");
    mysqli_close($conn);

    return $ok ? true : false;
}


function getAllClaims()
{
    $conn = dbConnect();

    $sql = "SELECT 
                c.id AS claim_id, c.status, c.created_at, c.message,
                u.full_name AS owner_name, u.email AS owner_email,
                f.item, f.location, f.found_date
            FROM claims c
            JOIN users u ON u.id = c.owner_id
            JOIN found_items f ON f.id = c.item_id
            ORDER BY c.id DESC";

    $res = mysqli_query($conn, $sql);
    $rows = [];
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
    }
    mysqli_close($conn);
    return $rows;
}

function updateClaimStatus($claim_id, $newStatus)
{
    $conn = dbConnect();
    $claim_id = (int)$claim_id;

    $allowed = ["APPROVED","REJECTED","RETURNED","PENDING"];
    if (!in_array($newStatus, $allowed)) {
        mysqli_close($conn);
        return false;
    }

    $ok = mysqli_query($conn, "UPDATE claims SET status='$newStatus' WHERE id=$claim_id");
    mysqli_close($conn);
    return $ok ? true : false;
}
