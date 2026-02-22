<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAR RENTAL</title>
    <script type="text/javascript">
        window.history.forward();
        function noBack() {
            window.history.forward();
        }
    </script>
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript">
        function preventBack() {
            window.history.forward();
        }

        setTimeout("preventBack()", 0);

        window.onunload = function () { null };
    </script>
</head>

<body>



    <?php
    require_once('connection.php');
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $pass = $_POST['pass'];


        if (empty($email) || empty($pass)) {
            echo '<script>alert("please fill the blanks")</script>';
        } else {
            $query = "select *from users where EMAIL='$email'";
            $res = mysqli_query($con, $query);
            if ($row = mysqli_fetch_assoc($res)) {
                $db_password = $row['PASSWORD'];
                if (md5($pass) == $db_password) {
                    header("location: cardetails.php");
                    session_start();
                    $_SESSION['email'] = $email;

                } else {
                    echo '<script>alert("Enter a proper password")</script>';
                }







            } else {
                echo '<script>alert("enter a proper email")</script>';
            }
        }
    }







    ?>
    <div class="hai">
        <header class="navbar">
            <div class="logo-container">
                <h2 class="logo">Cars</h2>
            </div>

            <div class="hamburger" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <nav class="menu" id="navMenu">
                <ul>
                    <li><a href="#">HOME</a></li>
                    <li><a href="aboutus.html">ABOUT</a></li>
                    <li><a href="services.html">SERVICES</a></li>
                    <li><a href="contactus.html">CONTACT</a></li>
                    <li><a href="adminlogin.php" class="admin-link">ADMIN LOGIN</a></li>
                </ul>
            </nav>
        </header>

        <main class="content-wrapper">
            <div class="hero-section">
                <h1>Rent Your <br><span>Dream Car</span></h1>
                <p class="par">Live the life of Luxury.<br>
                    Just rent a car of your wish from our vast collection.<br>
                    Enjoy every moment with your family. Join us today. </p>
                <a href="register.php" class="cn-btn">JOIN US</a>
            </div>

            <div class="login-card">
                <h2>Login Here</h2>
                <form method="POST">
                    <div class="input-group">
                        <input type="email" name="email" placeholder="Enter Email" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="pass" placeholder="Enter Password" required>
                    </div>
                    <button class="btnn" type="submit" name="login">Login</button>
                </form>
                <p class="link">Don't have an account?<br>
                    <a href="register.php">Sign up here</a>
                </p>

                <!-- Social Icons (Optional) -->
                <div class="social-icons">
                    <a href="#"><ion-icon name="logo-facebook"></ion-icon></a>
                    <a href="#"><ion-icon name="logo-instagram"></ion-icon></a>
                    <a href="#"><ion-icon name="logo-google"></ion-icon></a>
                </div>
            </div>
        </main>
    </div>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
    <script>
        function toggleMenu() {
            const menu = document.getElementById('navMenu');
            menu.classList.toggle('active');
        }

        // Close menu when clicking on a link
        document.querySelectorAll('.menu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('navMenu').classList.remove('active');
            });
        });
    </script>
</body>

</html>