<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lookingflats_db";

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // 1. Connect to MySQL without database
    $conn = new mysqli($servername, $username, $password);
    echo "<h3>Database Initialization</h3>";
    echo "Connected to MySQL server... OK<br>";

    // 2. Create Database if not exists
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql) === TRUE) {
        echo "Database '$dbname' created or already exists... OK<br>";
    }

    // 3. Select the database
    $conn->select_db($dbname);

    // Check if gallery column exists, if not add it
    $result = $conn->query("SHOW COLUMNS FROM properties LIKE 'gallery'");
    if ($result->num_rows == 0) {
        $conn->query("ALTER TABLE properties ADD COLUMN gallery TEXT AFTER image");
        echo "Column 'gallery' added to properties table... OK<br>";
    }

    // 4. Read and execute setup_db.sql
    $sql_file = 'setup_db.sql';
    if (file_exists($sql_file)) {
        $sql_content = file_get_contents($sql_file);
        
        // Remove comments and execute multi-query
        // Note: Simple split by semicolon might fail with complex triggers/procs, 
        // but for this simple schema it works.
        $queries = explode(';', $sql_content);
        
        $count = 0;
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                $conn->query($query);
                $count++;
            }
        }
        echo "Executed $count queries from $sql_file... OK<br>";
    } else {
        echo "Error: $sql_file not found!<br>";
    }

    echo "<br><div style='padding:15px; background:#dcfce7; color:#166534; border-radius:8px; border:1px solid #22c55e;'>
            <strong>Success!</strong> Database set up ho gaya hai.<br>
            Ab aap <a href='index.php'>Home Page</a> par ja sakte hain.
          </div>";

} catch (mysqli_sql_exception $e) {
    echo "<br><div style='padding:15px; background:#fee2e2; color:#b91c1c; border-radius:8px; border:1px solid #ef4444;'>
            <strong>Error:</strong> " . $e->getMessage() . "<br>
            Bhai, check karo ki XAMPP me <strong>MySQL</strong> 'Start' hai ya nahi.
          </div>";
}

$conn->close();
?>
