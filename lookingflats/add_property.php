<?php
session_start();
include 'db.php';

// Auth check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $price = $conn->real_escape_string($_POST['price']);
    $location = $conn->real_escape_string($_POST['location']);
    $type = $conn->real_escape_string($_POST['type']);
    $area = $conn->real_escape_string($_POST['area']);
    $status = $conn->real_escape_string($_POST['status']);
    $tag = $conn->real_escape_string($_POST['tag']);
    $description = $conn->real_escape_string($_POST['description']);
    $highlights = $conn->real_escape_string($_POST['highlights']);
    
    // Image handling
    $image = "img1.jpg"; // Default
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "c:/xampp/htdocs/property/loookinflats/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]);
        }
    }

    // Gallery Images handling
    $gallery_images = [];
    if (isset($_FILES['gallery'])) {
        $target_dir = "c:/xampp/htdocs/property/loookinflats/";
        foreach ($_FILES['gallery']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['gallery']['error'][$key] == 0) {
                $file_name = basename($_FILES['gallery']['name'][$key]);
                $target_file = $target_dir . $file_name;
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $gallery_images[] = $file_name;
                }
            }
        }
    }
    $gallery_str = implode(',', $gallery_images);

    $sql = "INSERT INTO properties (title, price, location, type, area, status, tag, description, highlights, image, gallery) 
            VALUES ('$title', '$price', '$location', '$type', '$area', '$status', '$tag', '$description', '$highlights', '$image', '$gallery_str')";

    if ($conn->query($sql) === TRUE) {
        $message = "Property added successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property - Lookingflats Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-layout { display: grid; grid-template-columns: 240px 1fr; min-height: 100vh; }
        .admin-sidebar { background: #0b1120; color: #fff; padding: 30px 20px; }
        .sidebar-nav a { display: block; color: #cbd5e1; text-decoration: none; padding: 12px 15px; border-radius: 8px; margin-bottom: 8px; }
        .sidebar-nav a.active { background: #1e293b; color: #fff; }
        .admin-main { background: #f8fafc; padding: 40px; }
        .form-container { background: #fff; padding: 30px; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); max-width: 800px; margin: 0 auto; }
        .msg-box { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .msg-success { background: #dcfce7; color: #166534; border: 1px solid #22c55e; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <h2>Lookingflats</h2>
            <nav class="sidebar-nav">
                <a href="admin.php">Dashboard</a>
                <a href="manage_properties.php" class="active">Properties</a>
                <a href="view_enquiries.php">Enquiries</a>
                <a href="admin.php?logout=1" style="color:#ef4444; margin-top:20px; display:block;">Logout</a>
            </nav>
        </aside>
        <main class="admin-main">
            <h1 style="margin-bottom:30px;">Add New Property</h1>
            
            <div class="form-container">
                <?php if ($message): ?>
                    <div class="msg-box msg-success"><?php echo $message; ?></div>
                <?php endif; ?>
                
                <form action="add_property.php" method="POST" enctype="multipart/form-data" class="contact-form">
                    <div class="form-row">
                        <label>Property Title</label>
                        <input type="text" name="title" placeholder="e.g. 2 BHK Apartment in Noida" required>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-row">
                            <label>Price</label>
                            <input type="text" name="price" placeholder="e.g. ₹82,00,000" required>
                        </div>
                        <div class="form-row">
                            <label>Location</label>
                            <input type="text" name="location" placeholder="e.g. Sector 150, Noida" required>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-row">
                            <label>Property Type</label>
                            <input type="text" name="type" placeholder="e.g. 2 BHK • Residential" required>
                        </div>
                        <div class="form-row">
                            <label>Area Size</label>
                            <input type="text" name="area" placeholder="e.g. 720 sq.ft" required>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-row">
                            <label>Status</label>
                            <input type="text" name="status" placeholder="e.g. Ready to move" required>
                        </div>
                        <div class="form-row">
                            <label>Tag Badge</label>
                            <input type="text" name="tag" placeholder="e.g. Hot listing" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <label>Highlights (Comma separated)</label>
                        <input type="text" name="highlights" placeholder="Feature 1, Feature 2, Feature 3" required>
                    </div>

                    <div class="form-grid">
                        <div class="form-row">
                            <label>Thumbnail Image</label>
                            <input type="file" name="image" accept="image/*">
                        </div>
                        <div class="form-row">
                            <label>Gallery Images (Select multiple)</label>
                            <input type="file" name="gallery[]" accept="image/*" multiple>
                        </div>
                    </div>

                    <div class="form-row">
                        <label>Description</label>
                        <textarea name="description" rows="4" placeholder="Brief details about the property..." required></textarea>
                    </div>

                    <div style="display:flex; gap:10px; margin-top:20px;">
                        <button type="submit" class="btn-primary" style="padding:12px 30px;">Save Property</button>
                        <a href="admin.php" class="btn-secondary" style="padding:12px 30px;">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
