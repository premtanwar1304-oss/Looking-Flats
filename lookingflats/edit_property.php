<?php
session_start();
include 'db.php';

// Auth check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = "";

// Handle Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
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
    $image_sql = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "c:/xampp/htdocs/property/loookinflats/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]);
            $image_sql = ", image = '$image'";
        }
    }

    // Gallery handling
    $gallery_sql = "";
    if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
        $gallery_images = [];
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
        if (!empty($gallery_images)) {
            $gallery_str = implode(',', $gallery_images);
            $gallery_sql = ", gallery = '$gallery_str'";
        }
    }

    $sql = "UPDATE properties SET 
            title = '$title', price = '$price', location = '$location', 
            type = '$type', area = '$area', status = '$status', 
            tag = '$tag', description = '$description', highlights = '$highlights'
            $image_sql $gallery_sql 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        $message = "Property updated successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch property
$sql = "SELECT * FROM properties WHERE id = $id";
$result = $conn->query($sql);
$property = $result->fetch_assoc();

if (!$property) {
    header("Location: manage_properties.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Property - Lookingflats Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-layout { display: grid; grid-template-columns: 240px 1fr; min-height: 100vh; }
        .admin-sidebar { background: #0b1120; color: #fff; padding: 30px 20px; }
        .sidebar-nav a { display: block; color: #cbd5e1; text-decoration: none; padding: 12px 15px; border-radius: 8px; margin-bottom: 8px; }
        .admin-main { background: #f8fafc; padding: 40px; }
        .form-container { background: #fff; padding: 30px; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); max-width: 800px; margin: 0 auto; }
        .msg-box { padding: 15px; border-radius: 8px; margin-bottom: 20px; background: #dcfce7; color: #166534; border: 1px solid #22c55e; }
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
            <h1 style="margin-bottom:30px;">Edit Property</h1>
            
            <div class="form-container">
                <?php if ($message): ?>
                    <div class="msg-box"><?php echo $message; ?></div>
                <?php endif; ?>
                
                <form action="edit_property.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" class="contact-form">
                    <input type="hidden" name="id" value="<?php echo $property['id']; ?>">
                    
                    <div class="form-row">
                        <label>Property Title</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($property['title']); ?>" required>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-row">
                            <label>Price</label>
                            <input type="text" name="price" value="<?php echo htmlspecialchars($property['price']); ?>" required>
                        </div>
                        <div class="form-row">
                            <label>Location</label>
                            <input type="text" name="location" value="<?php echo htmlspecialchars($property['location']); ?>" required>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-row">
                            <label>Property Type</label>
                            <input type="text" name="type" value="<?php echo htmlspecialchars($property['type']); ?>" required>
                        </div>
                        <div class="form-row">
                            <label>Area Size</label>
                            <input type="text" name="area" value="<?php echo htmlspecialchars($property['area']); ?>" required>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-row">
                            <label>Status</label>
                            <input type="text" name="status" value="<?php echo htmlspecialchars($property['status']); ?>" required>
                        </div>
                        <div class="form-row">
                            <label>Tag Badge</label>
                            <input type="text" name="tag" value="<?php echo htmlspecialchars($property['tag']); ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <label>Highlights (Comma separated)</label>
                        <input type="text" name="highlights" value="<?php echo htmlspecialchars($property['highlights']); ?>" required>
                    </div>

                    <div class="form-grid">
                        <div class="form-row">
                            <label>Thumbnail Image (Leave blank to keep current)</label>
                            <input type="file" name="image" accept="image/*">
                            <p style="font-size:12px; color:#64748b; margin-top:5px;">Current: <?php echo $property['image']; ?></p>
                        </div>
                        <div class="form-row">
                            <label>Gallery Images (Uploading new will replace old)</label>
                            <input type="file" name="gallery[]" accept="image/*" multiple>
                            <p style="font-size:12px; color:#64748b; margin-top:5px;">Current: <?php echo $property['gallery'] ?: 'None'; ?></p>
                        </div>
                    </div>

                    <div class="form-row">
                        <label>Description</label>
                        <textarea name="description" rows="4" required><?php echo htmlspecialchars($property['description']); ?></textarea>
                    </div>

                    <div style="display:flex; gap:10px; margin-top:20px;">
                        <button type="submit" class="btn-primary" style="padding:12px 30px;">Update Property</button>
                        <a href="manage_properties.php" class="btn-secondary" style="padding:12px 30px;">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
