<?php
require_once "db.php";


function getFoundItems($q="", $location="", $found_date="")
{
    $conn = dbConnect();

    $q = mysqli_real_escape_string($conn, $q);
    $location = mysqli_real_escape_string($conn, $location);
    $found_date = mysqli_real_escape_string($conn, $found_date);

    $sql = "SELECT * FROM found_items WHERE 1=1";

   $sql .= " AND status='Found' AND verified=1";


    if ($q != "") {
        $sql .= " AND (item LIKE '%$q%' OR description LIKE '%$q%')";
    }
    if ($location != "") {
        $sql .= " AND location LIKE '%$location%'";
    }
    if ($found_date != "") {
        $sql .= " AND found_date='$found_date'";
    }

    $sql .= " ORDER BY id DESC";

    $result = mysqli_query($conn, $sql);

    $items = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
    }

    mysqli_close($conn);
    return $items;
}


function getFoundItemById($id)
{
    $conn = dbConnect();
    $id = (int)$id;

    $sql = "SELECT * FROM found_items WHERE id=$id LIMIT 1";
    $result = mysqli_query($conn, $sql);

    $row = null;
    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
    }

    mysqli_close($conn);
    return $row;
}

function createLostReport($owner_id, $title, $category, $color, $lost_date, $location, $description, $unique_mark)
{
    $conn = dbConnect();

    $owner_id = (int)$owner_id;
    $title = mysqli_real_escape_string($conn, $title);
    $category = mysqli_real_escape_string($conn, $category);
    $color = mysqli_real_escape_string($conn, $color);
    $lost_date = mysqli_real_escape_string($conn, $lost_date);
    $location = mysqli_real_escape_string($conn, $location);
    $description = mysqli_real_escape_string($conn, $description);
    $unique_mark = mysqli_real_escape_string($conn, $unique_mark);

    $sql = "INSERT INTO lost_reports (owner_id, title, category, color, lost_date, location, description, unique_mark, created_at)
            VALUES ($owner_id, '$title', '$category', '$color', '$lost_date', '$location', '$description', '$unique_mark', NOW())";

    $ok = mysqli_query($conn, $sql);

    $newId = 0;
    if ($ok) $newId = mysqli_insert_id($conn);

    mysqli_close($conn);
    return $newId;
}


function getOwnerClaims($owner_id)
{
    $conn = dbConnect();
    $owner_id = (int)$owner_id;

    $sql = "SELECT 
                c.id AS claim_id,
                c.status,
                c.created_at,
                f.item,
                f.location
            FROM claims c
            JOIN found_items f ON f.id = c.item_id
            WHERE c.owner_id = $owner_id
            ORDER BY c.id DESC";

    $result = mysqli_query($conn, $sql);

    $rows = [];
    if ($result) {
        while ($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }
    }

    mysqli_close($conn);
    return $rows;
}


function ownerAlreadyClaimed($owner_id, $item_id)
{
    $conn = dbConnect();
    $owner_id = (int)$owner_id;
    $item_id = (int)$item_id;

    $sql = "SELECT id FROM claims WHERE owner_id=$owner_id AND item_id=$item_id LIMIT 1";
    $result = mysqli_query($conn, $sql);

    $exists = ($result && mysqli_num_rows($result) > 0);

    mysqli_close($conn);
    return $exists;
}


function createClaimAndMarkItem($owner_id, $item_id, $message)
{
    $conn = dbConnect();

    $owner_id = (int)$owner_id;
    $item_id  = (int)$item_id;
    $message  = mysqli_real_escape_string($conn, $message);

   
    $sql1 = "INSERT INTO claims (item_id, owner_id, message, status, created_at)
             VALUES ($item_id, $owner_id, '$message', 'PENDING', NOW())";

    $ok1 = mysqli_query($conn, $sql1);

    if (!$ok1) {
        mysqli_close($conn);
        return 0;
    }

    $claimId = mysqli_insert_id($conn);

    
    $sql2 = "UPDATE found_items SET status='Claimed' WHERE id=$item_id";
    mysqli_query($conn, $sql2);

    mysqli_close($conn);
    return $claimId;
}
