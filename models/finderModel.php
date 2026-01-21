<?php
require_once "db.php";

function insertFoundItem($finder_id, $name, $item, $location, $foundDate, $description, $photo)
{
    $conn = dbConnect();

    $finder_id  = (int)$finder_id;
    $name       = mysqli_real_escape_string($conn, $name);
    $item       = mysqli_real_escape_string($conn, $item);
    $location   = mysqli_real_escape_string($conn, $location);
    $foundDate  = mysqli_real_escape_string($conn, $foundDate);
    $description= mysqli_real_escape_string($conn, $description);
    $photo      = mysqli_real_escape_string($conn, $photo);

    $sql = "INSERT INTO found_items 
            (finder_id, name, item, location, found_date, description, photo, status, verified)
            VALUES
            ($finder_id, '$name', '$item', '$location', '$foundDate', '$description', '$photo', 'Found', 0)";

    $ok = mysqli_query($conn, $sql);
    mysqli_close($conn);

    return $ok ? true : false;
}


function loadMyReportedItem($finder_id)
{
    $conn = dbConnect();
    $finder_id = (int)$finder_id;

    $sql = "SELECT id, item, location, found_date, status
            FROM found_items
            WHERE finder_id = $finder_id
            ORDER BY found_date DESC";

    $res = mysqli_query($conn, $sql);

    $items = [];
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $items[] = $row;
        }
    }

    mysqli_close($conn);
    return $items;
}


function markItemAsClaimed($finder_id, $id)
{
    $conn = dbConnect();
    $finder_id = (int)$finder_id;
    $id = (int)$id;

    $sql = "UPDATE found_items 
            SET status='Claimed'
            WHERE id=$id AND finder_id=$finder_id";

    $ok = mysqli_query($conn, $sql);
    mysqli_close($conn);

    return $ok ? true : false;
}
