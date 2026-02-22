<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Users</title>
    <link rel="stylesheet" href="css/admindash.css">
    <style>
        .delete-btn {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid #e74c3c;
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .delete-btn:hover {
            background: #e74c3c;
            color: #fff;
            box-shadow: 0 0 15px rgba(231, 76, 60, 0.4);
        }
    </style>
</head>

<body>

    <?php
    require_once('connection.php');
    $query = "SELECT * FROM users";
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
                <li><a href="adminusers.php" class="active">USERS</a></li>
                <li><a href="adminfeedbacks.php">FEEDBACKS</a></li>
                <li><a href="adminreviews.php">REVIEWS</a></li>
                <li><a href="adminbook.php">BOOKINGS</a></li>
                <li><a href="index.php" class="logout-btn">LOGOUT</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <div class="section-header">
            <h1 class="section-title">REGISTERED USERS</h1>
            <p style="color: rgba(255,255,255,0.5);">Total Users: <span
                    style="color: var(--primary); font-weight: 600;"><?php echo $num; ?></span></p>
        </div>

        <div class="table-container">
            <table class="content-table">
                <thead>
                    <tr>
                        <th>NAME</th>
                        <th>EMAIL</th>
                        <th>LICENSE NO</th>
                        <th>PHONE</th>
                        <th>DOB</th>
                        <th>GENDER</th>
                        <th style="text-align: center;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($num == 0) {
                        echo "<tr><td colspan='6' style='text-align:center; padding: 40px;'>No users registered yet.</td></tr>";
                    }
                    while ($res = mysqli_fetch_array($queryy)) {
                        ?>
                        <tr>
                            <td style="font-weight: 600; color: #fff;">
                                <?php echo strtoupper($res['FNAME'] . " " . $res['LNAME']); ?></td>
                            <td style="color: var(--primary);"><?php echo $res['EMAIL']; ?></td>
                            <td><?php echo $res['LIC_NUM']; ?></td>
                            <td><?php echo $res['PHONE_NUMBER']; ?></td>
                            <td><?php echo $res['DOB'] ? date('d M Y', strtotime($res['DOB'])) : 'N/A'; ?></td>
                            <td><?php echo strtoupper($res['GENDER']); ?></td>
                            <td style="text-align: center;">
                                <a href="deleteuser.php?id=<?php echo $res['EMAIL'] ?>" class="delete-btn"
                                    onclick="return confirm('Are you sure you want to delete this user?')">DELETE</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>

</html>