<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Ride - Luxury Rental</title>
    <link rel="stylesheet" href="css/booking.css">
    <script type="text/javascript">
        function preventBack() { window.history.forward(); }
        setTimeout("preventBack()", 0);
        window.onunload = function () { null };
    </script>
</head>

<body>

    <?php
    require_once('connection.php');
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: index.php");
        exit();
    }

    $carid = $_GET['id'];

    // Get Car Details
    $sql = "SELECT * FROM cars WHERE CAR_ID='$carid'";
    $cres = mysqli_query($con, $sql);
    $car = mysqli_fetch_assoc($cres);

    // Get User Details
    $value = $_SESSION['email'];
    $sql = "SELECT * FROM users WHERE EMAIL='$value'";
    $ures = mysqli_query($con, $sql);
    $user = mysqli_fetch_assoc($ures);

    $uemail = $user['EMAIL'];
    $carprice = $car['PRICE'];

    if (isset($_POST['book'])) {
        $bplace = mysqli_real_escape_string($con, $_POST['place']);
        $bdate = date('Y-m-d', strtotime($_POST['date']));
        $dur = mysqli_real_escape_string($con, $_POST['dur']);
        $phno = mysqli_real_escape_string($con, $_POST['ph']);
        $des = mysqli_real_escape_string($con, $_POST['des']);
        $rdate = date('Y-m-d', strtotime($_POST['rdate']));

        // BACKEND PHONE VALIDATION
        if (!preg_match('/^[0-9]{10}$/', $phno)) {
            echo '<script>alert("Incorrect Phone Number (must be exactly 10 digits)")</script>';
        } elseif (empty($bplace) || empty($bdate) || empty($dur) || empty($phno) || empty($des) || empty($rdate)) {
            echo '<script>alert("Please fill all fields")</script>';
        } else {
            if ($bdate < $rdate) {
                $total_price = ($dur * $carprice);
                $sql = "INSERT INTO booking (CAR_ID, EMAIL, BOOK_PLACE, BOOK_DATE, DURATION, PHONE_NUMBER, DESTINATION, PRICE, RETURN_DATE) 
                        VALUES ($carid, '$uemail', '$bplace', '$bdate', $dur, $phno, '$des', $total_price, '$rdate')";
                $result = mysqli_query($con, $sql);

                if ($result) {
                    $_SESSION['email'] = $uemail;
                    header("Location: payment.php");
                    exit();
                } else {
                    echo '<script>alert("Connection error, please try again")</script>';
                }
            } else {
                echo '<script>alert("Return date must be after booking date")</script>';
            }
        }
    }
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
                <li><a href="index.php" style="color: var(--primary); font-weight: 700;">LOGOUT</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <div class="booking-wrapper">
            <!-- Sidebar: Car Summary -->
            <div class="car-summary">
                <img src="images/<?php echo $car['CAR_IMG'] ?>" class="summary-img" alt="Car Image">
                <h2 class="summary-title"><?php echo strtoupper($car['CAR_NAME']) ?></h2>
                <p class="summary-price">₹<span><?php echo $car['PRICE'] ?></span> / day</p>
                <div
                    style="text-align: left; width: 100%; padding-top: 20px; border-top: 1px solid var(--glass-border);">
                    <p style="font-size: 0.9rem; color: rgba(255,255,255,0.6);">Fuel: <span
                            style="color: #fff;"><?php echo $car['FUEL_TYPE'] ?></span></p>
                    <p style="font-size: 0.9rem; color: rgba(255,255,255,0.6);">Capacity: <span
                            style="color: #fff;"><?php echo $car['CAPACITY'] ?> Persons</span></p>
                </div>
            </div>

            <!-- Main: Booking Form -->
            <div class="booking-form-section">
                <div class="form-header">
                    <h2>Reserve Now</h2>
                    <p>Complete the form below to book your luxury ride.</p>
                </div>

                <form id="booking-form" method="POST" onsubmit="return validatePhone();">
                    <div class="input-grid">
                        <div class="form-group full-width">
                            <label>Pickup Location</label>
                            <input type="text" name="place" placeholder="Where should we pick up?" required>
                        </div>

                        <div class="form-group">
                            <label>Pickup Date</label>
                            <input type="date" name="date" id="datefield" required>
                        </div>

                        <div class="form-group">
                            <label>Return Date</label>
                            <input type="date" name="rdate" id="dfield" required>
                        </div>

                        <div class="form-group">
                            <label>Duration (Days)</label>
                            <input type="number" name="dur" min="1" max="30" placeholder="Number of days" required>
                        </div>

                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="ph" id="phone" maxlength="10" placeholder="10-digit number" required>
                            <small id="phoneError" style="color:red;"></small>
                        </div>

                        <div class="form-group full-width">
                            <label>Final Destination</label>
                            <input type="text" name="des" placeholder="Where are you heading?" required>
                        </div>
                    </div>

                    <button type="submit" name="book" class="submit-btn">CONFIRM BOOKING</button>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Set min/max dates for booking
        var today = new Date().toISOString().split('T')[0];
        document.getElementById("datefield").setAttribute("min", today);
        document.getElementById("dfield").setAttribute("min", today);

        // Auto-calculate duration if dates change (Optional enhancement)
        const d1 = document.getElementById("datefield");
        const d2 = document.getElementById("dfield");
        const dur = document.getElementsByName("dur")[0];

        function calc() {
            if (d1.value && d2.value) {
                const start = new Date(d1.value);
                const end = new Date(d2.value);
                const diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                if (diff > 0) dur.value = diff;
            }
        }
        d1.addEventListener('change', calc);
        d2.addEventListener('change', calc);

        // Phone validation on submit
        function validatePhone() {
            let phone = document.getElementById("phone").value;
            let error = document.getElementById("phoneError");

            if (!/^[0-9]{10}$/.test(phone)) {
                error.innerText = " Incorrect Phone Number (must be exactly 10 digits)";
                return false;
            }
            error.innerText = "";
            return true;
        }
    </script>

</body>

</html>
