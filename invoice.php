<?php
require_once('connection.php');
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid Booking ID";
    exit();
}

$bid = mysqli_real_escape_string($con, $_GET['id']);
$u_email = mysqli_real_escape_string($con, $_SESSION['email']);

// Fetch booking, user, car and payment details in one go
$query = "SELECT 
            b.BOOK_ID, b.CAR_ID, b.EMAIL, b.BOOK_PLACE, b.BOOK_DATE, 
            b.DURATION, b.PHONE_NUMBER, b.DESTINATION, b.RETURN_DATE, 
            b.PRICE, b.BOOK_STATUS,
            u.FNAME, u.LNAME, u.LIC_NUM, u.GENDER,
            c.CAR_NAME, c.FUEL_TYPE, c.CAPACITY, c.CAR_IMG,
            p.CARD_NO, p.PAY_ID
          FROM booking b 
          JOIN users u ON b.EMAIL = u.EMAIL 
          JOIN cars c ON b.CAR_ID = c.CAR_ID 
          LEFT JOIN payment p ON b.BOOK_ID = p.BOOK_ID 
          WHERE b.BOOK_ID = '$bid' AND b.EMAIL = '$u_email'";

$result = mysqli_query($con, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Booking not found or access denied.";
    exit();
}

$is_paid = !empty($data['PAY_ID']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - #<?php echo $bid; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: #f0f2f5; padding: 40px 20px; color: #1a1a1a; }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: relative;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 30px;
            margin-bottom: 40px;
        }

        .logo-area h1 { color: #ff7200; font-size: 2rem; font-weight: 700; letter-spacing: -1px; }
        .logo-area p { color: #666; font-size: 0.9rem; margin-top: 5px; }

        .invoice-meta { text-align: right; }
        .invoice-meta h2 { font-size: 1.5rem; margin-bottom: 5px; color: #1a1a1a; }
        .invoice-meta p { color: #666; font-size: 0.9rem; }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 50px;
        }

        .info-box h3 {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #999;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .info-box p { font-weight: 600; line-height: 1.5; color: #333; }

        .table-area { margin-bottom: 40px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px 0; border-bottom: 2px solid #1a1a1a; color: #999; font-size: 0.75rem; text-transform: uppercase; }
        td { padding: 20px 0; border-bottom: 1px solid #f0f0f0; }

        .table-car-name { font-weight: 700; font-size: 1.1rem; color: #1a1a1a; }
        .table-car-sub { font-size: 0.85rem; color: #666; margin-top: 4px; }

        .total-area {
            background: #fafafa;
            padding: 30px;
            border-radius: 8px;
            display: flex;
            justify-content: flex-end;
        }

        .total-box { text-align: right; }
        .total-box p { color: #666; font-size: 0.9rem; margin-bottom: 8px; }
        .total-box h2 { color: #ff7200; font-size: 2.5rem; font-weight: 700; }

        .footer {
            margin-top: 50px;
            text-align: center;
            color: #999;
            font-size: 0.85rem;
            border-top: 1px solid #f0f0f0;
            padding-top: 30px;
        }

        .payment-status {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            margin-top: 10px;
        }
        .status-paid { background: #e6fcf5; color: #0ca678; }
        .status-unpaid { background: #fff5f5; color: #fa5252; }

        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #ff7200;
            color: #fff;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            box-shadow: 0 10px 30px rgba(255, 114, 0, 0.3);
            display: flex;
            align-items: center;
            gap: 10px;
            border: none;
            cursor: pointer;
        }

        @media print {
            body { background: #fff; padding: 0; }
            .invoice-container { box-shadow: none; max-width: 100%; border-radius: 0; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>

    <div class="invoice-container">
        <div class="header">
            <div class="logo-area">
                <h1>CaRs LUXURY</h1>
                <p>Premium Vehicle Rental Services</p>
                <div class="payment-status <?php echo $is_paid ? 'status-paid' : 'status-unpaid'; ?>">
                    <?php echo $is_paid ? 'PAYMENT RECEIVED' : 'PAYMENT PENDING'; ?>
                </div>
            </div>
            <div class="invoice-meta">
                <h2>INVOICE</h2>
                <p>Invoice #: INV-<?php echo str_pad($bid, 6, "0", STR_PAD_LEFT); ?></p>
                <p>Date: <?php echo date('d M, Y'); ?></p>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-box">
                <h3>Billed To</h3>
                <p><?php echo strtoupper($data['FNAME'] . ' ' . $data['LNAME']); ?></p>
                <p><?php echo $data['EMAIL']; ?></p>
                <p><?php echo $data['PHONE_NUMBER']; ?></p>
            </div>
            <div class="info-box" style="text-align: right;">
                <h3>Booking Details</h3>
                <p>Pickup: <?php echo $data['BOOK_PLACE']; ?></p>
                <p>Destination: <?php echo $data['DESTINATION']; ?></p>
                <p>Period: <?php echo date('d M', strtotime($data['BOOK_DATE'])); ?> - <?php echo date('d M, Y', strtotime($data['RETURN_DATE'])); ?></p>
            </div>
        </div>

        <div class="table-area">
            <table>
                <thead>
                    <tr>
                        <th width="60%">Description</th>
                        <th width="20%">Duration</th>
                        <th width="20%" style="text-align: right;">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="table-car-name"><?php echo strtoupper($data['CAR_NAME']); ?></div>
                            <div class="table-car-sub"><?php echo $data['FUEL_TYPE']; ?> | <?php echo $data['CAPACITY']; ?>-Seater Luxury Performance</div>
                        </td>
                        <td><?php echo $data['DURATION']; ?> Days</td>
                        <td style="text-align: right; font-weight: 600;">₹<?php echo $data['PRICE']; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="total-area">
            <div class="total-box">
                <p>TOTAL PAYABLE AMOUNT</p>
                <h2>₹<?php echo $data['PRICE']; ?></h2>
                <?php if ($is_paid): ?>
                    <p style="margin-top: 10px; font-size: 0.8rem;">Paid via Card: **** **** **** <?php echo substr($data['CARD_NO'], -4); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for choosing CaRs Luxury. Have a safe and thrilling drive!</p>
            <p style="margin-top: 10px; opacity: 0.5;">This is a computer-generated invoice and doesn't require a physical signature.</p>
        </div>
    </div>

    <button onclick="window.print()" class="print-btn">
        <i class="fas fa-print"></i> DOWNLOAD / PRINT INVOICE
    </button>

    <script src="https://kit.fontawesome.com/dbed6b6114.js" crossorigin="anonymous"></script>
</body>
</html>
