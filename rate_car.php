<?php
require_once('connection.php');
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id']) || !isset($_GET['car'])) {
    header("Location: bookinstatus.php");
    exit();
}

$bid = $_GET['id'];
$cid = $_GET['car'];
$email = $_SESSION['email'];

// Check if booking exists and belongs to user
$check_sql = "SELECT b.*, c.CAR_NAME, c.CAR_IMG FROM booking b JOIN cars c ON b.CAR_ID = c.CAR_ID WHERE b.BOOK_ID = $bid AND b.EMAIL = '$email' AND b.BOOK_STATUS = 'RETURNED'";
$check_res = mysqli_query($con, $check_sql);
$booking = mysqli_fetch_assoc($check_res);

if (!$booking) {
    echo "Access denied or booking not eligible for review.";
    exit();
}

// Check if already reviewed
$rev_check = mysqli_query($con, "SELECT * FROM reviews WHERE BOOK_ID = $bid");
if (mysqli_num_rows($rev_check) > 0) {
    header("Location: bookinstatus.php");
    exit();
}

if (isset($_POST['submit_review'])) {
    $stars = $_POST['stars'];
    $comment = mysqli_real_escape_string($con, $_POST['comment']);
    
    $insert_sql = "INSERT INTO reviews (CAR_ID, BOOK_ID, EMAIL, STARS, COMMENT) VALUES ($cid, $bid, '$email', $stars, '$comment')";
    if (mysqli_query($con, $insert_sql)) {
        echo '<script>alert("Thank you for your review!"); window.location.href="bookinstatus.php";</script>';
    } else {
        echo '<script>alert("Error submitting review.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Your Ride - CaRs</title>
    <link rel="stylesheet" href="css/addcar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }
        .star-rating input { display: none; }
        .star-rating label {
            font-size: 2.5rem;
            color: rgba(255,255,255,0.2);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #ff7200;
            text-shadow: 0 0 15px rgba(255,114,0,0.4);
        }
        .car-preview {
            text-align: center;
            margin-bottom: 30px;
        }
        .car-preview img {
            width: 200px;
            height: 120px;
            object-fit: cover;
            border-radius: 15px;
            border: 2px solid var(--primary);
            margin-bottom: 15px;
        }
        textarea {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            padding: 15px;
            border-radius: 12px;
            color: #fff;
            resize: none;
            height: 120px;
            margin-bottom: 20px;
        }
        textarea:focus {
            outline: none;
            border-color: var(--primary);
        }
    </style>
</head>
<body>
    <header class="navbar">
        <h2 class="logo">CaRs</h2>
        <a href="bookinstatus.php" class="back-btn">CANCEL</a>
    </header>

    <main class="form-container">
        <div class="form-card">
            <div class="form-header">
                <h2>Rate Your Experience</h2>
                <p>How was your ride with the <?php echo $booking['CAR_NAME']; ?>?</p>
            </div>

            <div class="car-preview">
                <img src="images/<?php echo $booking['CAR_IMG']; ?>" alt="Car">
            </div>

            <form method="POST">
                <div class="star-rating">
                    <input type="radio" name="stars" id="star5" value="5" required><label for="star5"><i class="fas fa-star"></i></label>
                    <input type="radio" name="stars" id="star4" value="4"><label for="star4"><i class="fas fa-star"></i></label>
                    <input type="radio" name="stars" id="star3" value="3"><label for="star3"><i class="fas fa-star"></i></label>
                    <input type="radio" name="stars" id="star2" value="2"><label for="star2"><i class="fas fa-star"></i></label>
                    <input type="radio" name="stars" id="star1" value="1"><label for="star1"><i class="fas fa-star"></i></label>
                </div>

                <div class="form-group">
                    <label>Your Feedback</label>
                    <textarea name="comment" placeholder="Share your experience (Optional)..."></textarea>
                </div>

                <button type="submit" name="submit_review" class="submit-btn" style="margin-top: 0;">SUBMIT REVIEW</button>
            </form>
        </div>
    </main>
</body>
</html>
