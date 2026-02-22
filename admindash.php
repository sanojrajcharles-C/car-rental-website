<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Home</title>
    <link rel="stylesheet" href="css/admindash.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <?php
    require_once('connection.php');

    // Fetching Counts
    $cars_query = "SELECT * FROM cars";
    $cars_result = mysqli_query($con, $cars_query);
    $cars_count = mysqli_num_rows($cars_result);

    $users_query = "SELECT * FROM users";
    $users_result = mysqli_query($con, $users_query);
    $users_count = mysqli_num_rows($users_result);

    $bookings_query = "SELECT * FROM booking";
    $bookings_result = mysqli_query($con, $bookings_query);
    $bookings_count = mysqli_num_rows($bookings_result);

    $feedback_query = "SELECT * FROM feedback";
    $feedback_result = mysqli_query($con, $feedback_query);
    $feedback_count = mysqli_num_rows($feedback_result);

    $reviews_query = "SELECT * FROM reviews";
    $reviews_result = mysqli_query($con, $reviews_query);
    $reviews_count = mysqli_num_rows($reviews_result);
    ?>

    <header class="navbar">
        <div class="logo-container">
            <h2 class="logo">CaRs Admin</h2>
        </div>

        <nav>
            <ul class="nav-links">
                <li><a href="admindash.php" class="active">DASHBOARD</a></li>
                <li><a href="adminvehicle.php">VEHICLES</a></li>
                <li><a href="adminusers.php">USERS</a></li>
                <li><a href="adminfeedbacks.php">FEEDBACKS</a></li>
                <li><a href="adminreviews.php">REVIEWS</a></li>
                <li><a href="adminbook.php">BOOKINGS</a></li>
                <li><a href="index.php" class="logout-btn">LOGOUT</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <div class="section-header">
            <h1 class="section-title">DASHBOARD OVERVIEW</h1>
            <p style="color: rgba(255,255,255,0.5);">Welcome back, Admin</p>
        </div>

        <div class="dashboard-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(255, 114, 0, 0.1); color: #ff7200;">
                    <i class="fas fa-car"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Vehicles</h3>
                    <p><?php echo $cars_count; ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(46, 204, 113, 0.1); color: #2ecc71;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Registered Users</h3>
                    <p><?php echo $users_count; ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(52, 152, 219, 0.1); color: #3498db;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Bookings</h3>
                    <p><?php echo $bookings_count; ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(155, 89, 182, 0.1); color: #9b59b6;">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stat-info">
                    <h3>Feedbacks</h3>
                    <p><?php echo $feedback_count; ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(243, 156, 18, 0.1); color: #f39c12;">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-info">
                    <h3>Car Reviews</h3>
                    <p><?php echo $reviews_count; ?></p>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section (Optional expansion) -->
        <div style="margin-top: 50px;">
           <h2 class="section-title" style="font-size: 1.5rem; margin-bottom: 20px;">QUICK ACTIONS</h2>
           <div class="action-grid">
                <a href="addcar.php" class="action-btn">
                    <i class="fas fa-plus"></i> Add New Vehicle
                </a>
                <a href="adminbook.php" class="action-btn">
                    <i class="fas fa-calendar-check"></i> View Bookings
                </a>
                <a href="adminusers.php" class="action-btn">
                    <i class="fas fa-users"></i> View Users
                </a>
                <a href="adminfeedbacks.php" class="action-btn">
                    <i class="fas fa-comments"></i> View Feedbacks
                </a>
                <a href="adminreviews.php" class="action-btn">
                    <i class="fas fa-star"></i> View Reviews
                </a>
           </div>
        </div>

    </main>

</body>
</html>