<?php
require_once('connection.php');

$query = "SHOW COLUMNS FROM users LIKE 'PROFILE_IMG'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) == 0) {
    $sql = "ALTER TABLE users ADD COLUMN PROFILE_IMG VARCHAR(255) DEFAULT 'profile.png'";
    if (mysqli_query($con, $sql)) {
        echo "<h3>Successfully added PROFILE_IMG column to users table!</h3>";
        echo "<p>You can now upload profile pictures.</p>";
    } else {
        echo "Error adding column: " . mysqli_error($con);
    }
} else {
    echo "<h3>Column PROFILE_IMG already exists.</h3>";
}

// Check for DOB column
$query2 = "SHOW COLUMNS FROM users LIKE 'DOB'";
$result2 = mysqli_query($con, $query2);

if (mysqli_num_rows($result2) == 0) {
    $sql2 = "ALTER TABLE users ADD COLUMN DOB DATE DEFAULT NULL";
    if (mysqli_query($con, $sql2)) {
        echo "<h3>Successfully added DOB column to users table!</h3>";
    } else {
        echo "Error adding DOB column: " . mysqli_error($con);
    }
} else {
    echo "<h3>Column DOB already exists.</h3>";
}

echo '<br><a href="profile.php">Go to Profile Page</a>';
?>
