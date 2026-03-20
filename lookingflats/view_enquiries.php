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
    $sql = "DELETE FROM enquiries WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "Enquiry deleted successfully!";
    }
}

// Fetch enquiries
$sql = "SELECT * FROM enquiries ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Enquiries - Lookingflats Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-layout { display: grid; grid-template-columns: 240px 1fr; min-height: 100vh; }
        .admin-sidebar { background: #0b1120; color: #fff; padding: 30px 20px; }
        .sidebar-nav a { display: block; color: #cbd5e1; text-decoration: none; padding: 12px 15px; border-radius: 8px; margin-bottom: 8px; }
        .sidebar-nav a.active { background: #1e293b; color: #fff; }
        .admin-main { background: #f8fafc; padding: 40px; }
        .enquiry-card { background: #fff; padding: 25px; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 20px; border: 1px solid #e2e8f0; }
        .enquiry-header { display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; }
        .enquiry-info { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; font-size: 14px; }
        .label { color: #64748b; font-weight: 500; margin-right: 5px; }
        .btn-delete { color: #ef4444; font-size: 13px; text-decoration: none; }
        .msg-box { padding: 15px; border-radius: 8px; margin-bottom: 20px; background: #dcfce7; color: #166534; border: 1px solid #22c55e; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <h2>Lookingflats</h2>
            <nav class="sidebar-nav">
                <a href="admin.php">Dashboard</a>
                <a href="manage_properties.php">Properties</a>
                <a href="view_enquiries.php" class="active">Enquiries</a>
                <a href="admin.php?logout=1" style="color:#ef4444; margin-top:20px; display:block;">Logout</a>
            </nav>
        </aside>
        <main class="admin-main">
            <h1 style="margin-bottom:30px;">Buyer Enquiries (Leads)</h1>

            <?php if ($message): ?>
                <div class="msg-box"><?php echo $message; ?></div>
            <?php endif; ?>

            <div class="enquiries-list">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="enquiry-card">
                            <div class="enquiry-header">
                                <span style="font-weight:700; color:#0b1120;"><?php echo htmlspecialchars($row['name']); ?></span>
                                <span style="font-size:12px; color:#64748b;"><?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?></span>
                            </div>
                            <div class="enquiry-info">
                                <div><span class="label">Phone:</span> <?php echo htmlspecialchars($row['phone']); ?></div>
                                <div><span class="label">Interested City:</span> <?php echo htmlspecialchars($row['city']); ?></div>
                            </div>
                            <div style="margin-top:15px; font-size:14px; color:#4b5563; background:#f8fafc; padding:15px; border-radius:8px;">
                                <span class="label" style="display:block; margin-bottom:5px;">Message:</span>
                                <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                            </div>
                            <div style="margin-top:15px; text-align:right;">
                                <a href="https://wa.me/91<?php echo $row['phone']; ?>" target="_blank" class="btn-secondary" style="font-size:12px; padding:6px 12px; margin-right:10px;">Reply on WhatsApp</a>
                                <a href="view_enquiries.php?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Bhai, lead delete kar dein?')">Delete Lead</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="text-align:center; padding:50px; background:#fff; border-radius:16px;">
                        <p style="color:#64748b;">No enquiries yet. Leads will appear here when buyers fill the contact form.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
