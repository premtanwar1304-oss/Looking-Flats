<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lookingflats_db";

// Dealer Settings
$dealer_email = "pradeep.kashyap@example.com"; // Bhai, yahan apna asli email dal dena
$site_name = "Lookingflats";

// Enable error reporting for MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1049) {
        die("<div style='background:#fee2e2; color:#b91c1c; padding:20px; border:1px solid #ef4444; border-radius:10px; font-family:sans-serif;'>
                <strong>Database Error!</strong><br>
                Bhai, database '<strong>$dbname</strong>' nahi mil raha hai.<br><br>
                <strong>Solution:</strong><br>
                1. phpMyAdmin kholo (http://localhost/phpmyadmin)<br>
                2. Naya database banao jiska naam '<strong>$dbname</strong>' ho.<br>
                3. Phir <strong>setup_db.sql</strong> file ko usme import kar do.
             </div>");
    } else {
        die("Connection failed: " . $e->getMessage());
    }
}
?>
