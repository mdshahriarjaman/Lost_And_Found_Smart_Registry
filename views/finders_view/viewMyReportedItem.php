<?php
session_start();
if (!isset($_SESSION["userId"]) || $_SESSION["role"] !== "FINDER") {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Reported Items</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../../asset/css/viewMyReportedItem.css">
</head>
<body>

<h1>My Reported Items</h1>
    <div class="top-right">
        <a href="../../controllers/logout.php" class="logout-btn">Logout</a>
    </div>
<div class="card">
    <table>
        <tr>
            <th>Item Name</th>
            <th>Location</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <tbody id="itemsTable"></tbody>
    </table>
</div>

<footer>
    <div class="footer-container">
        <div class="footer-box">
            <h4>Site</h4><p>Lost</p><p>Report Lost</p><p>Found</p><p>Report Found</p>
        </div>
        <div class="footer-box">
            <h4>Help</h4><p>Customer Support</p><p>Terms & Conditions</p><p>Privacy Policy</p>
        </div>
        <div class="footer-box">
            <h4>Links</h4>
            <p><a href="https://www.linkedin.com">LinkedIn</a></p>
            <p><a href="https://www.facebook.com">Facebook</a></p>
            <p><a href="https://www.youtube.com">YouTube</a></p>
            <p><a href="aboutUs.php">About Us</a></p>
        </div>
        <div class="footer-box">
            <h4>Contact</h4><p>cell: +8801712345678</p><p>Email: lostAndFound@aiub.edu</p>
        </div>
    </div>
    <div class="copyright">© Copyright 2026 Lost and Found <br>All Right Reserved</div>
</footer>

<script>
function escapeHtml(str){
  return String(str ?? "").replace(/[&<>"']/g, m => ({
    "&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#039;"
  }[m]));
}

function loadReportedItems() {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "../../controllers/getReportedItems.php", true);

    xhr.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            let data = JSON.parse(this.responseText);
            let tbody = document.getElementById("itemsTable");
            tbody.innerHTML = "";

            if(!data.ok){
                tbody.innerHTML = `<tr><td colspan="5">${escapeHtml(data.error || "Error")}</td></tr>`;
                return;
            }

            let items = data.items;

            if(items.length === 0){
                tbody.innerHTML = `<tr><td colspan="5">No items reported yet.</td></tr>`;
                return;
            }

            items.forEach(item => {
                let tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${escapeHtml(item.item)}</td>
                    <td>${escapeHtml(item.location)}</td>
                    <td>${escapeHtml(item.found_date)}</td>
                    <td class="status ${item.status === 'Found' ? 'status-found' : 'status-claimed'}">${escapeHtml(item.status)}</td>
                    <td>${
                        item.status === 'Found'
                        ? '<button class="btn btn-claim" onclick="markClaimed(this,'+item.id+')">Mark as Claimed</button>'
                        : '<button class="btn btn-disabled" disabled>Claimed</button>'
                    }</td>
                `;
                tbody.appendChild(tr);
            });
        }
    };

    xhr.send();
}

function markClaimed(button, itemId){
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../../controllers/markClaimed.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            let data = JSON.parse(this.responseText);
            if(data.ok){
              
                let row = button.parentElement.parentElement;
                row.querySelector(".status").innerHTML = "Claimed";
                row.querySelector(".status").className = "status status-claimed";
                button.disabled = true;
                button.className = "btn btn-disabled";
                button.innerHTML = "Claimed";
            }else{
                alert(data.error || "Failed");
            }
        }
    };

    xhr.send("id=" + encodeURIComponent(itemId));
}

window.onload = loadReportedItems;
</script>

</body>
</html>
