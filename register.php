<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Account | CaRs Luxury Rentals</title>
  <link rel="stylesheet" href="css/regs.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

  <?php
  require_once('connection.php');
  if (isset($_POST['regs'])) {
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $lic = mysqli_real_escape_string($con, $_POST['lic']);
    $ph = mysqli_real_escape_string($con, $_POST['ph']);
    $pass = mysqli_real_escape_string($con, $_POST['pass']);
    $cpass = mysqli_real_escape_string($con, $_POST['cpass']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $dob = mysqli_real_escape_string($con, $_POST['dob']);
    $Pass = md5($pass);

    if (empty($fname) || empty($lname) || empty($email) || empty($lic) || empty($ph) || empty($pass) || empty($gender) || empty($dob)) {
      echo '<script>alert("Please fill all the fields")</script>';
    } else {
      if ($pass == $cpass) {
        $sql2 = "SELECT * FROM users WHERE EMAIL='$email'";
        $res = mysqli_query($con, $sql2);
        if (mysqli_num_rows($res) > 0) {
          echo '<script>alert("Email already exists! Please login instead.")</script>';
          echo '<script> window.location.href = "index.php";</script>';
        } else {
          $sql = "INSERT INTO users (FNAME,LNAME,EMAIL,LIC_NUM,PHONE_NUMBER,PASSWORD,GENDER,DOB) VALUES ('$fname','$lname','$email','$lic',$ph,'$Pass','$gender','$dob')";
          $result = mysqli_query($con, $sql);
          if ($result) {
            echo '<script>alert("Registration Successful! Please login to continue.")</script>';
            echo '<script> window.location.href = "index.php";</script>';
          } else {
            echo '<script>alert("Service error, please try again later.")</script>';
          }
        }
      } else {
        echo '<script>alert("Passwords do not match!")</script>';
      }
    }
  }
  ?>

  <a href="index.php" class="back-btn">BACK TO HOME</a>

  <div class="main-container">
    <div class="register-card">
      <h2>JOIN THE CLUB</h2>
      <p class="subtitle">Experience the thrill of elite performance</p>

      <form id="register-form" action="register.php" method="POST">
        <div class="form-grid">
          <div class="form-group">
            <label>First Name</label>
            <input type="text" name="fname" placeholder="Enter First Name" required>
          </div>

          <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="lname" placeholder="Enter Last Name" required>
          </div>

          <div class="form-group full-width">
            <label>Email Address</label>
            <input type="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
              placeholder="name@example.com" required>
          </div>

          <div class="form-group">
            <label>License Number</label>
            <input type="text" name="lic" placeholder="DL-XXXXXXXXXXXX" required>
          </div>

          <div class="form-group">
            <label>Phone Number</label>
            <input type="tel" name="ph" maxlength="10" onkeypress="return onlyNumberKey(event)"
              placeholder="10-digit number" required>
          </div>

          <div class="form-group">
            <label>Date of Birth</label>
            <input type="text" name="dob" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Select Date of Birth" required>
          </div>

          <div class="form-group">
            <label>Create Password</label>
            <div class="password-container">
              <input type="password" name="pass" id="psw" maxlength="12" placeholder="••••••••"
                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
              <i class="fas fa-eye toggle-password" onclick="togglePassword('psw', this)"></i>
            </div>
          </div>

          <div class="form-group">
            <label>Confirm Password</label>
            <div class="password-container">
              <input type="password" name="cpass" id="cpsw" placeholder="••••••••" required>
              <i class="fas fa-eye toggle-password" onclick="togglePassword('cpsw', this)"></i>
            </div>
          </div>

          <div class="gender-group">
            <label class="gender-label">Gender</label>
            <div class="gender-options">
              <label class="gender-tile">
                <input type="radio" name="gender" value="male" required>
                <span>Male</span>
              </label>
              <label class="gender-tile">
                <input type="radio" name="gender" value="female">
                <span>Female</span>
              </label>
            </div>
          </div>
        </div>

        <button type="submit" class="submit-btn" name="regs">SIGN UP NOW</button>
      </form>
    </div>
  </div>

  <div id="message">
    <h3>Password Requirements:</h3>
    <p id="letter" class="invalid">● A <b>lowercase</b> letter</p>
    <p id="capital" class="invalid">● A <b>capital (uppercase)</b> letter</p>
    <p id="number" class="invalid">● A <b>number</b></p>
    <p id="length" class="invalid">● Minimum <b>8 characters</b></p>
  </div>

  <script>
    var myInput = document.getElementById("psw");
    var letter = document.getElementById("letter");
    var capital = document.getElementById("capital");
    var number = document.getElementById("number");
    var length = document.getElementById("length");

    myInput.onfocus = function () {
      document.getElementById("message").style.display = "block";
    }

    myInput.onblur = function () {
      document.getElementById("message").style.display = "none";
    }

    myInput.onkeyup = function () {
      var lowerCaseLetters = /[a-z]/g;
      if (myInput.value.match(lowerCaseLetters)) {
        letter.classList.remove("invalid");
        letter.classList.add("valid");
      } else {
        letter.classList.remove("valid");
        letter.classList.add("invalid");
      }

      var upperCaseLetters = /[A-Z]/g;
      if (myInput.value.match(upperCaseLetters)) {
        capital.classList.remove("invalid");
        capital.classList.add("valid");
      } else {
        capital.classList.remove("valid");
        capital.classList.add("invalid");
      }

      var numbers = /[0-9]/g;
      if (myInput.value.match(numbers)) {
        number.classList.remove("invalid");
        number.classList.add("valid");
      } else {
        number.classList.remove("valid");
        number.classList.add("invalid");
      }

      if (myInput.value.length >= 8) {
        length.classList.remove("invalid");
        length.classList.add("valid");
      } else {
        length.classList.remove("valid");
        length.classList.add("invalid");
      }
    }

    function onlyNumberKey(evt) {
      var ASCIICode = (evt.which) ? evt.which : evt.keyCode
      if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
        return false;
      return true;
    }

    function togglePassword(inputId, icon) {
      const input = document.getElementById(inputId);
      if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      }
    }
  </script>
</body>

</html>