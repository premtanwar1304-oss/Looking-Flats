<?php
session_start();
include 'db.php';

// Auth check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Fetch stats
$prop_count = $conn->query("SELECT COUNT(*) FROM properties")->fetch_row()[0];
$enq_count = $conn->query("SELECT COUNT(*) FROM enquiries")->fetch_row()[0];

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Lookingflats</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-layout {
            display: grid;
            grid-template-columns: 240px 1fr;
            min-height: 100vh;
        }
        .admin-sidebar {
            background: #0b1120;
            color: #fff;
            padding: 30px 20px;
        }
        .admin-sidebar h2 {
            font-size: 20px;
            margin-bottom: 30px;
            color: #22c55e;
        }
        .sidebar-nav a {
            display: block;
            color: #cbd5e1;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 8px;
            transition: 0.3s;
            font-size: 15px;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: #1e293b;
            color: #fff;
        }
        .admin-main {
            background: #f8fafc;
            padding: 40px;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: #fff;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h3 {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .stat-card p {
            font-size: 32px;
            font-weight: 700;
            color: #0b1120;
        }
        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }
        .action-card {
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
        }
        .action-card h3 {
            margin-bottom: 15px;
        }
        .btn-logout {
            color: #ef4444;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <h2>Lookingflats</h2>
            <nav class="sidebar-nav">
                <a href="admin.php" class="active">Dashboard</a>
                <a href="manage_properties.php">Properties</a>
                <a href="view_enquiries.php">Enquiries</a>
                <hr style="border:0; border-top:1px solid #1e293b; margin:20px 0;">
                <a href="admin.php?logout=1" class="btn-logout">Logout</a>
            </nav>
        </aside>
        <main class="admin-main">
            <header class="admin-header">
                <div>
                    <h1 style="font-size: 28px; margin-bottom:5px;">Dashboard</h1>
                    <p style="color:#64748b;">Welcome back, <?php echo $_SESSION['admin_username']; ?>!</p>
                </div>
                <a href="add_property.php" class="btn-primary btn-large">+ Add Property</a>
            </header>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Properties</h3>
                    <p><?php echo $prop_count; ?></p>
                </div>
                <div class="stat-card">
                    <h3>New Enquiries</h3>
                    <p><?php echo $enq_count; ?></p>
                </div>
            </div>

            <div class="action-grid">
                <div class="action-card">
                    <h3>Quick Actions</h3>
                    <p style="color:#64748b; margin-bottom:20px;">Manage your real estate inventory and buyer leads from here.</p>
                    <div style="display:flex; gap:10px;">
                        <a href="manage_properties.php" class="btn-secondary">Edit Properties</a>
                        <a href="view_enquiries.php" class="btn-secondary">View Leads</a>
                    </div>
                </div>
                <div class="action-card">
                    <h3>System Status</h3>
                    <p style="color:#64748b;">Database: Connected (lookingflats_db)</p>
                    <p style="color:#64748b;">Server: <?php echo $_SERVER['SERVER_NAME']; ?></p>
                    <p style="color:#64748b;">Last Sync: <?php echo date('d M, H:i'); ?></p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
