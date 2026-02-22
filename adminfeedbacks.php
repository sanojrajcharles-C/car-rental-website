<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Feedbacks</title>
    <link rel="stylesheet" href="css/admindash.css?v=<?php echo time(); ?>">
</head>

<body>

    <?php
    require_once('connection.php');
    $query = "SELECT * FROM feedback ORDER BY FED_ID DESC";
    $queryy = mysqli_query($con, $query);
    $num = mysqli_num_rows($queryy);
    ?>

    <header class="navbar">
        <div class="logo-container">
            <h2 class="logo">CaRs Admin</h2>
        </div>

        <nav>
            <ul class="nav-links">
                <li><a href="admindash.php">DASHBOARD</a></li>
                <li><a href="adminvehicle.php">VEHICLES</a></li>
                <li><a href="adminusers.php">USERS</a></li>
                <li><a href="adminfeedbacks.php" class="active">FEEDBACKS</a></li>
                <li><a href="adminreviews.php">REVIEWS</a></li>
                <li><a href="adminbook.php">BOOKINGS</a></li>
                <li><a href="index.php" class="logout-btn">LOGOUT</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <div class="section-header">
            <h1 class="section-title">USER FEEDBACKS</h1>
            <p style="color: rgba(255,255,255,0.5);">Total Feedbacks: <span
                    style="color: var(--primary); font-weight: 600;"><?php echo $num; ?></span></p>
        </div>

        <div class="table-container">
            <table class="content-table">
                <thead>
                    <tr>
                        <th width="15%">ID</th>
                        <th width="30%">USER EMAIL</th>
                        <th width="55%">COMMENT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($num == 0) {
                        echo "<tr><td colspan='3' style='text-align:center; padding: 40px;'>No feedbacks found.</td></tr>";
                    }
                    while ($res = mysqli_fetch_array($queryy)) {
                        ?>
                        <tr>
                            <td>#<?php echo $res['FED_ID']; ?></td>
                            <td style="color: var(--primary); font-weight: 500;"><?php echo $res['EMAIL']; ?></td>
                            <td><?php echo $res['COMMENT']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>

</html>