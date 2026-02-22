<?php
require_once('connection.php');
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['email'];

// --- SELF-HEALING DATABASE: Ensure PROFILE_IMG column exists ---
$col_check = mysqli_query($con, "SHOW COLUMNS FROM users LIKE 'PROFILE_IMG'");
if (mysqli_num_rows($col_check) == 0) {
    mysqli_query($con, "ALTER TABLE users ADD COLUMN PROFILE_IMG VARCHAR(255) DEFAULT 'profile.png'");
}

$col_check_dob = mysqli_query($con, "SHOW COLUMNS FROM users LIKE 'DOB'");
if (mysqli_num_rows($col_check_dob) == 0) {
    mysqli_query($con, "ALTER TABLE users ADD COLUMN DOB DATE DEFAULT NULL");
}
// -------------------------------------------------------------

$message = "";

// Handle Form Submission
if (isset($_POST['update'])) {
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);
    $lic_num = mysqli_real_escape_string($con, $_POST['lic_num']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $dob = mysqli_real_escape_string($con, $_POST['dob']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    
    // Optional: Handle Password Update
    $password_query = "";
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        $password_query = ", PASSWORD='$password'";
    }

    // Handle Image Upload
    $image_query = "";
    if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] == 0) {
        $img_name = $_FILES['profile_img']['name'];
        $tmp_name = $_FILES['profile_img']['tmp_name'];
        $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
        $img_ex_lc = strtolower($img_ex);
        $allowed_exs = array("jpg", "jpeg", "png", "webp");

        if (in_array($img_ex_lc, $allowed_exs)) {
            $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
            $img_upload_path = 'images/' . $new_img_name;
            move_uploaded_file($tmp_name, $img_upload_path);
            $image_query = ", PROFILE_IMG='$new_img_name'";
        } else {
             echo '<script>alert("You can only upload jpg, jpeg, png or webp files");</script>';
        }
    }

    $update_sql = "UPDATE users SET FNAME='$fname', LNAME='$lname', LIC_NUM='$lic_num', PHONE_NUMBER='$phone', DOB='$dob', GENDER='$gender' $password_query $image_query WHERE EMAIL='$email'";
    
    if (mysqli_query($con, $update_sql)) {
        echo '<script>alert("Profile Updated Successfully!");</script>';
        // Refresh to show new image
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo '<script>alert("Error Updating Profile: ' . mysqli_error($con) . '");</script>';
    }
}

// Fetch User Data
$sql = "SELECT * FROM users WHERE EMAIL='$email'";
$result = mysqli_query($con, $sql);
$user = mysqli_fetch_assoc($result);

// Default image if none set
$profile_img = !empty($user['PROFILE_IMG']) ? "images/" . $user['PROFILE_IMG'] : "images/profile.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - CaRs Rental</title>
    <link rel="stylesheet" href="css/profile.css">
    <script src="https://kit.fontawesome.com/dbed6b6114.js" crossorigin="anonymous"></script>
</head>
<body>

    <header class="navbar">
        <div class="logo-container">
            <h2 class="logo">CaRs</h2>
        </div>

        <nav>
            <ul class="nav-links">
                <li><a href="cardetails.php">HOME</a></li>
                <li><a href="bookinstatus.php">MY BOOKINGS</a></li>
                <li><a href="feedback/Feedbacks.php">CONTACT US</a></li>
                <li class="user-profile">
                    <img src="<?php echo $profile_img; ?>" class="profile-img" alt="Profile">
                    <p class="phello">HI, <span><?php echo strtoupper($user['FNAME']); ?></span></p>
                </li>
                <li><a href="index.php" class="logout-btn">LOGOUT</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-pic-wrapper">
                    <img src="<?php echo $profile_img; ?>" alt="Profile Picture">
                </div>
                <h2>My Profile</h2>
                <p>Manage your account settings</p>
            </div>

            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group" style="text-align: center;">
                    <label for="file-upload" class="custom-file-upload">
                        <i class="fas fa-camera"></i> Change Photo
                    </label>
                    <input id="file-upload" type="file" name="profile_img" style="display: none;">
                </div>

                <div class="form-group">
                    <label>Email Address (Cannot change)</label>
                    <input type="email" class="form-control" value="<?php echo $user['EMAIL']; ?>" readonly>
                </div>

                <div class="form-group" style="display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label>First Name</label>
                        <input type="text" name="fname" class="form-control" value="<?php echo $user['FNAME']; ?>" required>
                    </div>
                    <div style="flex: 1;">
                        <label>Last Name</label>
                        <input type="text" name="lname" class="form-control" value="<?php echo $user['LNAME']; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>License Number</label>
                    <input type="text" name="lic_num" class="form-control" value="<?php echo $user['LIC_NUM']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="number" name="phone" class="form-control" value="<?php echo $user['PHONE_NUMBER']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" class="form-control" value="<?php echo $user['DOB']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <div class="gender-group">
                        <label class="gender-radio">
                            <input type="radio" name="gender" value="male" <?php echo ($user['GENDER'] == 'male') ? 'checked' : ''; ?>> Male
                        </label>
                        <label class="gender-radio">
                            <input type="radio" name="gender" value="female" <?php echo ($user['GENDER'] == 'female') ? 'checked' : ''; ?>> Female
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>New Password (Leave blank to keep current)</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter new password">
                </div>

                <button type="submit" name="update" class="btn-update">UPDATE PROFILE</button>
            </form>

            <a href="cardetails.php" class="back-link">Back to Home</a>
        </div>
    </main>

</body>
</html>
