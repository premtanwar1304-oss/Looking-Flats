<?php
session_start();
include 'db.php';

// Auth check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$message = "";

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $sql = "DELETE FROM properties WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "Property deleted successfully!";
    } else {
        $message = "Error deleting property: " . $conn->error;
    }
}

// Fetch properties
$sql = "SELECT * FROM properties ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Properties - Lookingflats Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-layout { display: grid; grid-template-columns: 240px 1fr; min-height: 100vh; }
        .admin-sidebar { background: #0b1120; color: #fff; padding: 30px 20px; }
        .sidebar-nav a { display: block; color: #cbd5e1; text-decoration: none; padding: 12px 15px; border-radius: 8px; margin-bottom: 8px; }
        .sidebar-nav a.active { background: #1e293b; color: #fff; }
        .admin-main { background: #f8fafc; padding: 40px; }
        .table-container { background: #fff; padding: 20px; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { text-align: left; padding: 12px; border-bottom: 2px solid #f1f5f9; color: #64748b; font-size: 14px; text-transform: uppercase; }
        td { padding: 15px 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .btn-edit { color: #2563eb; text-decoration: none; margin-right: 15px; }
        .btn-delete { color: #ef4444; text-decoration: none; }
        .msg-box { padding: 15px; border-radius: 8px; margin-bottom: 20px; background: #dcfce7; color: #166534; border: 1px solid #22c55e; }
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
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
                <h1>Manage Properties</h1>
                <a href="add_property.php" class="btn-primary">+ Add New</a>
            </div>

            <?php if ($message): ?>
                <div class="msg-box"><?php echo $message; ?></div>
            <?php endif; ?>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Property Title</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><img src="<?php echo htmlspecialchars($row['image']); ?>" width="60" style="border-radius:4px;"></td>
                                    <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                                    <td>
                                        <a href="edit_property.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                                        <a href="manage_properties.php?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Bhai, kya aap pakka is property ko delete karna chahte ho?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align:center; padding:30px;">No properties found. Add some first!</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
