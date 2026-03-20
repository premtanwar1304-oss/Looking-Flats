<?php
session_start();
include 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        // For demo using direct comparison, in production use password_hash/verify
        if ($password === $admin['password']) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: admin.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Admin not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Lookingflats</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .login-container h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #0b1120;
        }
        .error-msg {
            background: #fee2e2;
            color: #b91c1c;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body style="background: #f1f5f9;">
    <div class="login-container">
        <h1>Admin Login</h1>
        <?php if ($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST" class="contact-form">
            <div class="form-row">
                <label>Username</label>
                <input type="text" name="username" placeholder="Admin username" required>
            </div>
            <div class="form-row">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn-primary full-width">Login</button>
        </form>
        <p style="text-align:center; margin-top:20px; font-size:14px; color:#64748b;">
            Default: admin / admin123
        </p>
    </div>
</body>
</html>
