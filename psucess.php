<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Successful | Luxury Rental</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

    :root {
      --primary: #ff7200;
      --success: #2ecc71;
      --bg-dark: #0f0f0f;
      --glass-bg: rgba(255, 255, 255, 0.05);
      --glass-border: rgba(255, 255, 255, 0.1);
      --glass-blur: blur(20px);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: linear-gradient(rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.95)), url("images/ps.png");
      background-size: cover;
      background-position: center;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #fff;
    }

    .success-card {
      background: var(--glass-bg);
      backdrop-filter: var(--glass-blur);
      border: 1px solid var(--glass-border);
      border-radius: 40px;
      padding: 60px;
      text-align: center;
      max-width: 500px;
      width: 90%;
      box-shadow: 0 50px 100px rgba(0, 0, 0, 0.5);
      animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes slideUp {
      from {
        transform: translateY(50px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .icon-circle {
      width: 120px;
      height: 120px;
      background: rgba(46, 204, 113, 0.1);
      border: 2px solid var(--success);
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0 auto 30px;
      color: var(--success);
      font-size: 60px;
      box-shadow: 0 0 30px rgba(46, 204, 113, 0.2);
    }

    h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 15px;
      background: linear-gradient(to right, #fff, var(--success));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    p {
      opacity: 0.7;
      font-size: 1.1rem;
      line-height: 1.6;
      margin-bottom: 40px;
    }

    .btn-home {
      display: inline-block;
      padding: 15px 40px;
      background: var(--primary);
      color: #fff;
      text-decoration: none;
      border-radius: 15px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.3s ease;
      box-shadow: 0 10px 20px rgba(255, 114, 0, 0.3);
    }

    .btn-home:hover {
      transform: translateY(-5px);
      background: #e66700;
      box-shadow: 0 15px 30px rgba(255, 114, 0, 0.4);
    }
  </style>
</head>

<body>
  <div class="success-card">
    <div class="icon-circle">✓</div>
    <h1>PAYMENT SUCCESS!</h1>
    <p>Your luxury ride has been secured. We've received your request and our team will be in touch shortly.</p>
    <?php $bid = isset($_SESSION['bid']) ? $_SESSION['bid'] : ''; ?>
    <a href="invoice.php?id=<?php echo $bid; ?>" class="btn-home" style="background: var(--success); margin-right: 15px;">Download Receipt</a>
    <a href="bookinstatus.php" class="btn-home">View My Bookings</a>
  </div>
</body>

</html>