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
<title>Report Found Item</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../../asset/css/reportFoundItem.css">
</head>

<body>

<h1>Report Found Item</h1>
<div style="margin:auto; padding:auto" class="top-right logout-btn"> 
    <div >
        <a href="../../controllers/logout.php" class="logout-btn">Logout</a>
    </div>

    <div >
        <a href="viewMyReportedItem.php" class="logout-btn">My Reported Items</a>
    </div>
</div>
    


<div class="card">
   <form action="../../controllers/reportFoundItemController.php" method="POST" enctype="multipart/form-data">
    <div class="row">
        <label>Name :</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($_SESSION["name"] ?? ""); ?>" required>
    </div>

    <div class="row">
        <label>Item :</label>
        <select name="item" id="itemSelect" onchange="checkOthers('item')">
            <option value="">Select Item</option>
            <option value="Wallet">Wallet</option>
            <option value="Phone">Phone</option>
            <option value="ID Card">ID Card</option>
            <option value="Bag">Bag</option>
            <option value="Keys">Keys</option>
            <option value="Others">Others</option>
        </select>
        <input type="text" id="itemOther" name="itemOther" placeholder="Enter other item" style="display:none; margin-top:5px;">
    </div>

    <div class="row">
        <label>Location :</label>
        <select name="location" id="locationSelect" onchange="checkOthers('location')">
            <option value="">Select Location</option>
            <option value="Library">Library</option>
            <option value="Cafeteria">Cafeteria</option>
            <option value="Gate">Gate</option>
            <option value="Others">Others</option>
        </select>
        <input type="text" id="locationOther" name="locationOther" placeholder="Enter other location" style="display:none; margin-top:5px;">
    </div>

    <div class="row">
        <label>Date :</label>
        <input type="date" name="foundDate" required>
    </div>

    <div class="row">
        <label>Item Description :</label>
        <textarea name="description" placeholder="e.g., Black wallet with ID card inside" required></textarea>
    </div>

    <div class="row">
        <label>Upload Photo :</label>
        <input type="file" name="photo" accept="image/*">
    </div>

    <div class="buttons">
        <button class="submit-btn" type="submit">Submit</button>
        <button class="reset-btn" type="reset">Reset</button>
    </div>
</form>

<script>
function checkOthers(type) {
    if(type === 'item'){
        var select = document.getElementById('itemSelect');
        var otherInput = document.getElementById('itemOther');
    } else {
        var select = document.getElementById('locationSelect');
        var otherInput = document.getElementById('locationOther');
    }

    if(select.value === 'Others'){
        otherInput.style.display = 'block';
        otherInput.focus();
    } else {
        otherInput.style.display = 'none';
        otherInput.value = '';
    }
}
</script>
</div>


<footer>
    <div class="footer-container">
        <div class="footer-box">
            <h4>Site</h4>
            <p>Lost</p>
            <p>Report Lost</p>
            <p>Found</p>
            <p>Report Found</p>
        </div>
        <div class="footer-box">
            <h4>Help</h4>
            <p>Customer Support</p>
            <p>Terms & Conditions</p>
            <p>Privacy Policy</p>
        </div>
        <div class="footer-box">
            <h4>Links</h4>
            <p> <a href="https://www.linkedin.com">LinkedIn</a></p>
            <p> <a href="https://www.facebook.com">Facebook</a></p>
            <p> <a href="https://www.youtube.com">YouTube</a></p>
            <p> <a href="aboutUs.php">About Us</a></p>
        </div>
        <div class="footer-box">
            <h4>Contact</h4>
            <p>cell: +8801712345678</p>
            <p>Email: lostAndFound@aiub.edu</p>
        </div>
    </div>
    <div class="copyright">
        © Copyright 2026 Lost and Found <br>
        All Right Reserved
    </div>
</footer>

</body>
</html>
