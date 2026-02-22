<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Secured Payment | Luxury Rental</title>
    <link rel="stylesheet" href="css/pay.css" />

    <script type="text/javascript">
        function preventBack() { window.history.forward(); }
        setTimeout(preventBack, 0);
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

$u_email = $_SESSION['email'];
$sql = "SELECT * FROM booking WHERE EMAIL='$u_email' ORDER BY BOOK_ID DESC LIMIT 1";
$res = mysqli_query($con, $sql);
$booking = mysqli_fetch_assoc($res);

if (!$booking) {
    header("Location: cardetails.php");
    exit();
}

$bid = $booking['BOOK_ID'];
$_SESSION['bid'] = $bid;
$price = $booking['PRICE'];

if (isset($_POST['pay'])) {

    $cardno = mysqli_real_escape_string($con, $_POST['cardno']);
    $exp    = mysqli_real_escape_string($con, $_POST['exp']);
    $cvv    = mysqli_real_escape_string($con, $_POST['cvv']);

    // BACKEND VALIDATION
    if (!preg_match('/^[0-9]{16}$/', $cardno)) {
        echo "<script>alert('Invalid Card Number')</script>";
    } elseif (!preg_match('/^[0-9]{3}$/', $cvv)) {
        echo "<script>alert('Invalid CVV')</script>";
    } else {

        $check = mysqli_query($con, "SELECT * FROM payment WHERE BOOK_ID=$bid");
        if (mysqli_num_rows($check) > 0) {
            header("Location: psucess.php");
            exit();
        }

        $sql2 = "INSERT INTO payment (BOOK_ID, CARD_NO, EXP_DATE, CVV, PRICE)
                 VALUES ($bid, '$cardno', '$exp', $cvv, $price)";
        if (mysqli_query($con, $sql2)) {
            header("Location: psucess.php");
            exit();
        } else {
            echo "<script>alert('Payment Failed')</script>";
        }
    }
}
?>

<div class="payment-container">

    <!-- LEFT CARD -->
    <div class="card">
        <h1 class="card__title">Payment Details</h1>

        <div class="card-visual">
            <div class="chip"></div>
            <div class="card-type">VISA</div>
        </div>

        <form method="POST" onsubmit="return validateCard();">

            <div class="input-box">
                <label>Card Number</label>
                <input type="text"
                       id="cardno"
                       name="cardno"
                       placeholder="xxxx xxxx xxxx xxxx"
                       maxlength="16"
                       required>
                <small id="cardError" style="color:red;"></small>
            </div>

            <div class="row">
                <div class="input-box">
                    <label>Expiry Date</label>
                    <input type="text"
                           name="exp"
                           placeholder="MM/YY"
                           maxlength="5"
                           required>
                </div>

                <div class="input-box">
                    <label>CVV Code</label>
                    <input type="password"
                           name="cvv"
                           placeholder="xxx"
                           maxlength="3"
                           required>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" name="pay" class="btn btn-pay">
                    PAY ₹<?php echo $price; ?> NOW
                </button>
                <a href="cancelbooking.php" class="btn btn-cancel">
                    CANCEL BOOKING
                </a>
            </div>

        </form>
    </div>

    <!-- RIGHT SUMMARY -->
    <div class="summary">
        <h2>Checkout Summary</h2>

        <div class="summary-box">
            <p>Booking ID</p>
            <strong>#<?php echo $bid; ?></strong>
        </div>

        <div class="summary-box">
            <p>Email</p>
            <strong><?php echo $u_email; ?></strong>
        </div>

        <div class="price-summary">
            <h3>Total Payable</h3>
            <div class="amount">₹<?php echo $price; ?>/-</div>
        </div>
    </div>

</div>

<!-- SUBMIT TIME VALIDATION -->
<script>
function validateCard() {
    let card = document.getElementById("cardno").value;
    let error = document.getElementById("cardError");

    // Allow typing anything, validate on submit
    if (!/^[0-9]{16}$/.test(card)) {
        error.innerText = " Not Valid! Card number must be 16 digits";
        return false;
    }

    error.innerText = "";
    return true;
}
</script>

</body>
</html>
