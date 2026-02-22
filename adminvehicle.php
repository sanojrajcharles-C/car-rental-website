<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Vehicles</title>
    <link rel="stylesheet" href="css/admindash.css?v=<?php echo time(); ?>">
    <style>
        .add-car-btn {
            background: var(--primary);
            color: #fff;
            padding: 10px 25px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 10px 20px rgba(255, 114, 0, 0.2);
        }

        .add-car-btn:hover {
            background: #fff;
            color: var(--primary);
            transform: translateY(-2px);
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .status-yes {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border: 1px solid #2ecc71;
        }

        .status-no {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }
    </style>
</head>

<body>

    <?php
    require_once('connection.php');
    $query = "SELECT * FROM cars ORDER BY CAR_ID DESC";
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
                <li><a href="adminvehicle.php" class="active">VEHICLES</a></li>
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
            <div>
                <h1 class="section-title">FLEET MANAGEMENT</h1>
                <p style="color: rgba(255,255,255,0.5); padding-left: 15px; margin-top: 5px;">Total Vehicles: <span
                        style="color: var(--primary); font-weight: 600;"><?php echo $num; ?></span></p>
            </div>
            <a href="addcar.php" class="add-car-btn">
                <ion-icon name="add-circle-outline" style="font-size: 1.2rem;"></ion-icon>
                ADD NEW VEHICLE
            </a>
        </div>

        <div class="table-container">
            <table class="content-table">
                <thead>
                    <tr>
                        <th width="10%">ID</th>
                        <th width="25%">VEHICLE NAME</th>
                        <th width="15%">FUEL TYPE</th>
                        <th width="10%">CAPACITY</th>
                        <th width="15%">PRICE / DAY</th>
                        <th width="15%">AVAILABILITY</th>
                        <th width="15%" style="text-align: center;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($num == 0) {
                        echo "<tr><td colspan='7' style='text-align:center; padding: 40px;'>No vehicles in the fleet.</td></tr>";
                    }
                    while ($res = mysqli_fetch_array($queryy)) {
                        ?>
                        <tr>
                            <td>#<?php echo $res['CAR_ID']; ?></td>
                            <td style="font-weight: 600; color: #fff;"><?php echo strtoupper($res['CAR_NAME']); ?></td>
                            <td><?php echo strtoupper($res['FUEL_TYPE']); ?></td>
                            <td><?php echo $res['CAPACITY']; ?> Seater</td>
                            <td style="color: var(--primary); font-weight: 600;">₹<?php echo $res['PRICE']; ?></td>
                            <td>
                                <?php if ($res['AVAILABLE'] == 'Y'): ?>
                                    <span class="status-badge status-yes">AVAILABLE</span>
                                <?php else: ?>
                                    <span class="status-badge status-no">BUSY</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-btn-group">
                                    <a href="editcar.php?id=<?php echo $res['CAR_ID'] ?>" class="edit-btn">EDIT</a>
                                    <a href="deletecar.php?id=<?php echo $res['CAR_ID'] ?>" class="delete-btn"
                                        onclick="return confirm('Remove this vehicle from fleet?')">DELETE</a>
                                </div>
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