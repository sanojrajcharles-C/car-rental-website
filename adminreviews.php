<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Car Reviews</title>
    <link rel="stylesheet" href="css/admindash.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <?php
    require_once('connection.php');
    
    // Fetch reviews with car and user details
    $query = "SELECT r.*, c.CAR_NAME, c.CAR_IMG, u.FNAME, u.LNAME 
              FROM reviews r 
              JOIN cars c ON r.CAR_ID = c.CAR_ID 
              JOIN users u ON r.EMAIL = u.EMAIL 
              ORDER BY r.REV_DATE DESC";
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
                <li><a href="adminfeedbacks.php">FEEDBACKS</a></li>
                <li><a href="adminreviews.php" class="active">REVIEWS</a></li>
                <li><a href="adminbook.php">BOOKINGS</a></li>
                <li><a href="index.php" class="logout-btn">LOGOUT</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <div class="section-header">
            <h1 class="section-title">CAR REVIEWS & RATINGS</h1>
            <p style="color: rgba(255,255,255,0.5);">Total Reviews: <span
                    style="color: var(--primary); font-weight: 600;"><?php echo $num; ?></span></p>
        </div>

        <div class="table-container">
            <table class="content-table">
                <thead>
                    <tr>
                        <th>CAR</th>
                        <th>USER</th>
                        <th>RATING</th>
                        <th>COMMENT</th>
                        <th>DATE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($num == 0) {
                        echo "<tr><td colspan='5' style='text-align:center; padding: 40px;'>No reviews found yet.</td></tr>";
                    }
                    while ($res = mysqli_fetch_array($queryy)) {
                        ?>
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <img src="images/<?php echo $res['CAR_IMG']; ?>" style="width:50px; height:35px; object-fit:cover; border-radius:5px;">
                                    <span><?php echo $res['CAR_NAME']; ?></span>
                                </div>
                            </td>
                            <td><?php echo $res['FNAME'] . " " . $res['LNAME']; ?></td>
                            <td>
                                <div style="color: #ff7200;">
                                    <?php 
                                    for($i=1; $i<=5; $i++){
                                        if($i <= $res['STARS']) echo '<i class="fas fa-star"></i>';
                                        else echo '<i class="far fa-star"></i>';
                                    }
                                    ?>
                                </div>
                            </td>
                            <td><i style="color:rgba(255,255,255,0.6);">"<?php echo $res['COMMENT']; ?>"</i></td>
                            <td><?php echo date('d M, Y', strtotime($res['REV_DATE'])); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>

</html>
