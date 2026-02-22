<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Cars - Luxury Rental</title>
    <link rel="stylesheet" href="css/cardetails.css?v=<?php echo time(); ?>">
</head>

<body>

    <?php
    require_once('connection.php');
    session_start();

    // --- SELF-HEALING DATABASE: Ensure reviews table exists ---
    $check_table = mysqli_query($con, "SHOW TABLES LIKE 'reviews'");
    if (mysqli_num_rows($check_table) == 0) {
        $create_table = "CREATE TABLE reviews (
            REV_ID INT AUTO_INCREMENT PRIMARY KEY,
            CAR_ID INT,
            BOOK_ID INT,
            EMAIL VARCHAR(255),
            STARS INT,
            COMMENT TEXT,
            REV_DATE DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (CAR_ID) REFERENCES cars(CAR_ID) ON DELETE CASCADE,
            FOREIGN KEY (EMAIL) REFERENCES users(EMAIL) ON DELETE CASCADE,
            FOREIGN KEY (BOOK_ID) REFERENCES booking(BOOK_ID) ON DELETE CASCADE
        )";
        mysqli_query($con, $create_table);
    }
    // ---------------------------------------------------------

    if (!isset($_SESSION['email'])) {
        header("Location: index.php");
        exit();
    }

    $value = $_SESSION['email'];

    $sql = "SELECT * FROM users WHERE EMAIL='$value'";
    $name = mysqli_query($con, $sql);
    $rows = mysqli_fetch_assoc($name);
    
    // Default image if none set
    $profile_img = !empty($rows['PROFILE_IMG']) ? "images/" . $rows['PROFILE_IMG'] : "images/profile.png";

    // Get All Available Cars with their Average Rating
    $sql2 = "SELECT c.*, AVG(r.STARS) as avg_rating, COUNT(r.REV_ID) as review_count 
             FROM cars c 
             LEFT JOIN reviews r ON c.CAR_ID = r.CAR_ID 
             WHERE c.AVAILABLE='Y' 
             GROUP BY c.CAR_ID";
    $cars = mysqli_query($con, $sql2);
    ?>

    <header class="navbar">
        <div class="logo-container">
            <h2 class="logo">CaRs</h2>
        </div>

        <nav>
            <ul class="nav-links">
                <li><a href="cardetails.php">HOME</a></li>
                <li><a href="bookinstatus.php">MY BOOKINGS</a></li>
                <li><a href="feedback/Feedbacks.php">CONTACT US</a></li>
                <li class="user-profile" onclick="window.location.href='profile.php'">
                    <img src="<?php echo $profile_img; ?>" class="profile-img" alt="Profile">
                    <p class="phello">HI, <span><?php echo strtoupper($rows['FNAME']); ?></span></p>
                </li>
                <li><a href="index.php" class="logout-btn">LOGOUT</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <h1 class="section-title">CHOOSE YOUR PERFORMANCE</h1>

        <!-- Search and Filter Section -->
        <div class="filter-section">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="carSearch" placeholder="Search by car name..." onkeyup="filterCars()">
            </div>
            
            <div class="filter-group">
                <div class="filter-item">
                    <select id="fuelFilter" onchange="filterCars()">
                        <option value="">All Fuel Types</option>
                        <option value="PETROL">Petrol</option>
                        <option value="DIESEL">Diesel</option>
                        <option value="ELECTRIC">Electric</option>
                        <option value="GAS">Gas</option>
                    </select>
                </div>
                
                <div class="filter-item">
                    <select id="capacityFilter" onchange="filterCars()">
                        <option value="">All Capacities</option>
                        <option value="4">4 Seater</option>
                        <option value="5">5 Seater</option>
                        <option value="6">6 Seater</option>
                        <option value="7">7+ Seater</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="car-grid">
            <?php
            while ($result = mysqli_fetch_array($cars)) {
                $res = $result['CAR_ID'];
                ?>
                <div class="car-card" 
                     data-name="<?php echo strtoupper($result['CAR_NAME']); ?>" 
                     data-fuel="<?php echo strtoupper($result['FUEL_TYPE']); ?>" 
                     data-capacity="<?php echo $result['CAPACITY']; ?>">
                    <div class="car-img-wrapper">
                        <img src="images/<?php echo $result['CAR_IMG'] ?>" alt="<?php echo $result['CAR_NAME'] ?>">
                    </div>
                    <div class="car-info">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                            <h3 class="car-name" style="margin-bottom: 0;"><?php echo $result['CAR_NAME'] ?></h3>
                            <?php if ($result['review_count'] > 0): ?>
                                <div class="rating-display" style="background: rgba(255,114,0,0.1); padding: 4px 8px; border-radius: 8px; border: 1px solid rgba(255,114,0,0.2);">
                                    <span style="color: #ff7200; font-weight: 700; font-size: 0.85rem;">
                                        <i class="fas fa-star"></i> <?php echo number_format($result['avg_rating'], 1); ?>
                                    </span>
                                    <small style="font-size: 0.65rem; opacity: 0.5; margin-left: 2px;">(<?php echo $result['review_count']; ?>)</small>
                                </div>
                            <?php else: ?>
                                <small style="font-size: 0.7rem; opacity: 0.4;">NEW ARRIVAL</small>
                            <?php endif; ?>
                        </div>

                        <div class="car-details">
                            <div class="detail-item">
                                <span>FUEL TYPE</span>
                                <span><?php echo $result['FUEL_TYPE'] ?></span>
                            </div>
                            <div class="detail-item">
                                <span>CAPACITY</span>
                                <span><?php echo $result['CAPACITY'] ?> SEATER</span>
                            </div>
                        </div>

                        <div class="price-tag">
                            <span>₹<?php echo $result['PRICE'] ?> <small>/ DAY</small></span>
                        </div>

                        <a href="booking.php?id=<?php echo $res; ?>" class="book-btn">BOOK NOW</a>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </main>

    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        function filterCars() {
            const searchInput = document.getElementById('carSearch').value.toUpperCase();
            const fuelFilter = document.getElementById('fuelFilter').value.toUpperCase();
            const capacityFilter = document.getElementById('capacityFilter').value;
            
            const carCards = document.getElementsByClassName('car-card');
            
            for (let i = 0; i < carCards.length; i++) {
                const card = carCards[i];
                const carName = card.getAttribute('data-name');
                const carFuel = card.getAttribute('data-fuel');
                const carCapacity = parseInt(card.getAttribute('data-capacity'));
                
                let showCard = true;
                
                // Name Search
                if (searchInput && !carName.includes(searchInput)) {
                    showCard = false;
                }
                
                // Fuel Filter
                if (fuelFilter && carFuel !== fuelFilter) {
                    showCard = false;
                }
                
                // Capacity Filter (exact or 7+)
                if (capacityFilter) {
                    if (capacityFilter === "7") {
                        if (carCapacity < 7) showCard = false;
                    } else if (carCapacity !== parseInt(capacityFilter)) {
                        showCard = false;
                    }
                }
                
                if (showCard) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            }
        }
    </script>
</body>

</html>