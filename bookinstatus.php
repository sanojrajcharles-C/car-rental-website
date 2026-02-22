<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Luxury Rental</title>
    <link rel="stylesheet" href="css/bookinstatus.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <?php
    require_once('connection.php');
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: index.php");
        exit();
    }

    $email = $_SESSION['email'];
    
    // Get User Info
    $user_sql = "SELECT * FROM users WHERE EMAIL='$email'";
    $user_res = mysqli_query($con, $user_sql);
    $user_data = mysqli_fetch_assoc($user_res);
    $profile_img = !empty($user_data['PROFILE_IMG']) ? "images/" . $user_data['PROFILE_IMG'] : "images/profile.png";

    // Get ALL bookings for this user, joined with cars, payment status and reviews
    $query = "SELECT b.*, c.CAR_NAME, c.CAR_IMG, p.PAY_ID, r.REV_ID 
              FROM booking b 
              JOIN cars c ON b.CAR_ID = c.CAR_ID 
              LEFT JOIN payment p ON b.BOOK_ID = p.BOOK_ID 
              LEFT JOIN reviews r ON b.BOOK_ID = r.BOOK_ID
              WHERE b.EMAIL='$email' 
              ORDER BY b.BOOK_ID DESC";
    $bookings_res = mysqli_query($con, $query);
    $total_bookings = mysqli_num_rows($bookings_res);
    ?>

    <header class="navbar">
        <div class="logo-container">
            <h2 class="logo">CaRs</h2>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="cardetails.php">HOME</a></li>
                <li><a href="bookinstatus.php" class="active">MY BOOKINGS</a></li>
                <li><a href="feedback/Feedbacks.php">CONTACT US</a></li>
                <li class="user-profile" onclick="window.location.href='profile.php'">
                    <img src="<?php echo $profile_img; ?>" class="profile-img" alt="Profile">
                    <p class="phello">HI, <span><?php echo strtoupper($user_data['FNAME']); ?></span></p>
                </li>
                <li><a href="index.php" style="color: var(--primary); font-weight: 700;">LOGOUT</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <div class="section-header">
            <h1 class="section-title">BOOKING HISTORY</h1>
            <p style="color: rgba(255,255,255,0.6);">View and manage your luxury car reservations</p>
        </div>

        <?php if ($total_bookings == 0): ?>
            <div class="status-card empty-state">
                <i class="fas fa-calendar-times" style="font-size: 4rem; color: var(--primary); margin-bottom: 20px;"></i>
                <h2>No Bookings Found</h2>
                <p style="color: rgba(255,255,255,0.6); margin: 15px 0 30px;">It looks like you haven't made any reservations yet.</p>
                <a href="cardetails.php" class="btn btn-primary">BOOK YOUR FIRST CAR</a>
            </div>
        <?php else: ?>
            <div class="booking-grid">
                <?php while ($book = mysqli_fetch_assoc($bookings_res)): 
                    $status = strtoupper($book['BOOK_STATUS']);
                    $statusClass = 'status-pending';
                    if ($status == 'APPROVED') $statusClass = 'status-approved';
                    if ($status == 'REJECTED') $statusClass = 'status-rejected';
                    if ($status == 'RETURNED') $statusClass = 'status-returned';
                    
                    $is_paid = !empty($book['PAY_ID']);
                ?>
                <div class="booking-card">
                    <div class="card-left">
                        <img src="images/<?php echo $book['CAR_IMG']; ?>" alt="Car Image">
                        <div class="car-info-mini">
                            <h3><?php echo $book['CAR_NAME']; ?></h3>
                            <span class="booking-id">ID: #<?php echo $book['BOOK_ID']; ?></span>
                        </div>
                    </div>
                    
                    <div class="card-middle">
                        <div class="info-group">
                            <label><i class="fas fa-calendar-alt"></i> Dates</label>
                            <p><?php echo date('d M', strtotime($book['BOOK_DATE'])); ?> - <?php echo date('d M, Y', strtotime($book['RETURN_DATE'])); ?></p>
                        </div>
                        <div class="info-group">
                            <label><i class="fas fa-map-marker-alt"></i> Pickup</label>
                            <p><?php echo $book['BOOK_PLACE']; ?></p>
                        </div>
                    </div>

                    <div class="card-right">
                        <div class="price-info">
                            <small>Total Price</small>
                            <div class="amount">₹<?php echo $book['PRICE']; ?></div>
                        </div>
                        
                        <div class="status-group">
                            <span class="status-badge <?php echo $statusClass; ?>"><?php echo $status; ?></span>
                            <?php if ($is_paid): ?>
                                <span class="payment-badge status-paid"><i class="fas fa-check-circle"></i> PAID</span>
                            <?php else: ?>
                                <span class="payment-badge status-unpaid"><i class="fas fa-clock"></i> UNPAID</span>
                            <?php endif; ?>
                        </div>

                        <div class="action-zone">
                            <?php if ($status == 'APPROVED' && !$is_paid): ?>
                                <a href="payment.php?id=<?php echo $book['BOOK_ID']; ?>" class="pay-btn">PAY NOW</a>
                            <?php elseif ($status == 'PENDING' || $status == 'UNDER PROCESSING'): ?>
                                <p class="waiting-text">Awaiting Approval...</p>
                            <?php elseif ($status == 'REJECTED'): ?>
                                <p class="error-text">Booking Postponed/Rejected</p>
                            <?php elseif ($is_paid): ?>
                                <div style="display:flex; flex-direction:column; gap:8px;">
                                    <p class="success-text">Booking Confirmed <i class="fas fa-heart"></i></p>
                                    <div style="display:flex; gap:8px;">
                                        <a href="invoice.php?id=<?php echo $book['BOOK_ID']; ?>" target="_blank" class="pay-btn" style="background:#2ecc71; flex:1;">
                                            <i class="fas fa-file-invoice"></i> INVOICE
                                        </a>
                                        <?php if ($status == 'RETURNED' && empty($book['REV_ID'])): ?>
                                            <a href="rate_car.php?id=<?php echo $book['BOOK_ID']; ?>&car=<?php echo $book['CAR_ID']; ?>" class="pay-btn" style="background:var(--primary); flex:1;">
                                                <i class="fas fa-star"></i> REVIEW
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </main>

    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>