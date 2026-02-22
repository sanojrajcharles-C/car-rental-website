<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Luxury Rental</title>
    <link rel="stylesheet" href="css/adlog.css">
    <script type="text/javascript">
        function preventBack() { window.history.forward(); }
        setTimeout("preventBack()", 0);
        window.onunload = function () { null };
    </script>
</head>

<body>
    <!-- Ambient Background Decorations -->
    <div class="bg-circle circle-1"></div>
    <div class="bg-circle circle-2"></div>

    <a href="index.php" class="back-home">GO TO HOME</a>

    <?php
    require_once('connection.php');
    if (isset($_POST['adlog'])) {
        $id = mysqli_real_escape_string($con, $_POST['adid']);
        $pass = mysqli_real_escape_string($con, $_POST['adpass']);

        if (empty($id) || empty($pass)) {
            echo '<script>alert("Please fill all the blanks")</script>';
        } else {
            $query = "select * from admin where ADMIN_ID='$id'";
            $res = mysqli_query($con, $query);
            if ($row = mysqli_fetch_assoc($res)) {
                $db_password = $row['ADMIN_PASSWORD'];
                if ($pass == $db_password) {
                    echo '<script>alert("Welcome ADMINISTRATOR!");</script>';
                    header("location: admindash.php");
                } else {
                    echo '<script>alert("Enter a proper password")</script>';
                }
            } else {
                echo '<script>alert("Enter a proper admin ID")</script>';
            }
        }
    }
    ?>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>HELLO ADMIN</h1>
                <p>Enter your credentials to access the console</p>
            </div>

            <form method="POST">
                <div class="form-group">
                    <label>Admin User ID</label>
                    <input type="text" name="adid" placeholder="e.g. admin01" required autofocus>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="adpass" placeholder="••••••••" required>
                </div>

                <input type="submit" class="login-btn" value="LOGIN" name="adlog">
            </form>
        </div>
    </div>

</body>

</html>