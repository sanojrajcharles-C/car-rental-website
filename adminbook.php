<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bookings</title>
    <link rel="stylesheet" href="css/admindash.css">
    <style>
        .action-btn {
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 700;
            transition: all 0.3s ease;
            display: inline-block;
            margin: 2px;
            text-transform: uppercase;
        }

        .btn-approve {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border: 1px solid #2ecc71;
        }

        .btn-approve:hover {
            background: #2ecc71;
            color: #fff;
            box-shadow: 0 0 15px rgba(46, 204, 113, 0.4);
        }

        .btn-return {
            background: rgba(52, 152, 219, 0.2);
            color: #3498db;
            border: 1px solid #3498db;
        }

        .btn-return:hover {
            background: #3498db;
            color: #fff;
            box-shadow: 0 0 15px rgba(52, 152, 219, 0.4);
        }

        .btn-reject {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }

        .btn-reject:hover {
            background: #e74c3c;
            color: #fff;
            box-shadow: 0 0 15px rgba(231, 76, 60, 0.4);
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .status-approved {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border: 1px solid #2ecc71;
        }

        .status-pending {
            background: rgba(241, 196, 15, 0.2);
            color: #f1c40f;
            border: 1px solid #f1c40f;
        }

        .status-rejected {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }

        .status-returned {
            background: rgba(149, 165, 166, 0.2);
            color: #95a5a6;
            border: 1px solid #95a5a6;
        }

        .status-paid {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border: 1px solid #2ecc71;
        }

        .status-unpaid {
            background: rgba(230, 126, 34, 0.2);
            color: #e67e22;
            border: 1px solid #e67e22;
        }
    </style>
</head>

<body>

    <?php
    require_once('connection.php');
    $query = "SELECT booking.*, payment.PAY_ID FROM booking LEFT JOIN payment ON booking.BOOK_ID = payment.BOOK_ID ORDER BY booking.BOOK_ID DESC";
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
                <li><a href="adminreviews.php">REVIEWS</a></li>
                <li><a href="adminbook.php" class="active">BOOKINGS</a></li>
                <li><a href="index.php" class="logout-btn">LOGOUT</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <div class="section-header">
            <div>
                <h1 class="section-title">BOOKING REQUESTS</h1>
                <p style="color: rgba(255,255,255,0.5); padding-left: 15px; margin-top: 5px;">Total Logs: <span
                        style="color: var(--primary); font-weight: 600;"><?php echo $num; ?></span></p>
            </div>
        </div>

        <div class="table-container" style="overflow-x: auto;">
            <table class="content-table">
                <thead>
                    <tr>
                        <th>CAR</th>
                        <th>USER</th>
                        <th>PICKUP</th>
                        <th>DATE</th>
                        <th>DAYS</th>
                        <th>STATUS</th>
                        <th>PAYMENT</th>
                        <th style="text-align: center;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($num == 0) {
                        echo "<tr><td colspan='7' style='text-align:center; padding: 40px;'>No booking requests found.</td></tr>";
                    }
                    while ($res = mysqli_fetch_array($queryy)) {
                        $status = strtoupper($res['BOOK_STATUS']);
                        $statusClass = 'status-pending';
                        if ($status == 'APPROVED')
                            $statusClass = 'status-approved';
                        if ($status == 'REJECTED')
                            $statusClass = 'status-rejected';
                        if ($status == 'RETURNED')
                            $statusClass = 'status-returned';
                        ?>
                        <tr>
                            <td style="color: var(--primary); font-weight: 600;">#<?php echo $res['CAR_ID']; ?></td>
                            <td>
                                <span
                                    style="display: block; font-weight: 600; color: #fff;"><?php echo $res['EMAIL']; ?></span>
                                <small style="color: rgba(255,255,255,0.4);"><?php echo $res['PHONE_NUMBER']; ?></small>
                            </td>
                            <td>
                                <span style="display: block;"><?php echo $res['BOOK_PLACE']; ?></span>
                                <small style="color: rgba(255,255,255,0.4);">To: <?php echo $res['DESTINATION']; ?></small>
                            </td>
                            <td><?php echo date('d M, Y', strtotime($res['BOOK_DATE'])); ?></td>
                            <td><?php echo $res['DURATION']; ?> Days</td>
                            <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $status; ?></span></td>
                            <td>
                                <?php if ($res['PAY_ID']): ?>
                                    <span class="status-badge status-paid">PAID</span>
                                <?php else: ?>
                                    <span class="status-badge status-unpaid">NOT PAID</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center; white-space: nowrap;">
                                <?php if ($status == 'PENDING' || $status == 'UNDER PROCESSING'): ?>
                                    <a href="approve.php?id=<?php echo $res['BOOK_ID'] ?>"
                                        class="action-btn btn-approve">APPROVE</a>
                                    <a href="rejectbooking.php?id=<?php echo $res['BOOK_ID'] ?>"
                                        class="action-btn btn-reject">REJECT</a>
                                <?php endif; ?>

                                <?php if ($status == 'APPROVED'): ?>
                                    <a href="adminreturn.php?id=<?php echo $res['CAR_ID'] ?>&bookid=<?php echo $res['BOOK_ID'] ?>"
                                        class="action-btn btn-return">MARK RETURNED</a>
                                <?php endif; ?>
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