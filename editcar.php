<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vehicle - Admin Dashboard</title>
    <link rel="stylesheet" href="css/addcar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/admindash.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php
    require_once('connection.php');
    if (isset($_GET['id'])) {
        $carid = $_GET['id'];
        $query = "SELECT * FROM cars WHERE CAR_ID = '$carid'";
        $result = mysqli_query($con, $query);
        $car = mysqli_fetch_assoc($result);
    }
    ?>

    <header class="navbar">
        <h2 class="logo">CaRs Admin</h2>
        <a href="adminvehicle.php" class="back-btn">BACK TO FLEET</a>
    </header>

    <main class="form-container">
        <div class="form-card">
            <div class="form-header">
                <h2>Edit Vehicle</h2>
                <p>Update details for <?php echo $car['CAR_NAME']; ?></p>
            </div>

            <form id="editcar-form" action="upload.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="carid" value="<?php echo $car['CAR_ID']; ?>">
                
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Car Name</label>
                        <input type="text" name="carname" value="<?php echo $car['CAR_NAME']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Fuel Type</label>
                        <select name="ftype" required>
                            <option value="Petrol" <?php echo ($car['FUEL_TYPE'] == 'Petrol') ? 'selected' : ''; ?>>Petrol</option>
                            <option value="Diesel" <?php echo ($car['FUEL_TYPE'] == 'Diesel') ? 'selected' : ''; ?>>Diesel</option>
                            <option value="Electric" <?php echo ($car['FUEL_TYPE'] == 'Electric') ? 'selected' : ''; ?>>Electric</option>
                            <option value="Gas" <?php echo ($car['FUEL_TYPE'] == 'Gas') ? 'selected' : ''; ?>>Gas</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Capacity</label>
                        <input type="number" name="capacity" min="1" value="<?php echo $car['CAPACITY']; ?>" required>
                    </div>

                    <div class="form-group full-width">
                        <label>Daily Rental Price (₹)</label>
                        <input type="number" name="price" min="1" value="<?php echo $car['PRICE']; ?>" required>
                    </div>

                    <div class="form-group full-width file-upload">
                        <label>Update Image (Optional)</label>
                        <input type="file" name="image" accept="image/*">
                        <small style="color: rgba(255,255,255,0.5); display: block; margin-top: 5px;">Leave blank to keep current image</small>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label>Availability</label>
                    <div style="display: flex; gap: 20px; color: white;">
                         <label style="color:white; display:flex; align-items:center; gap:5px; text-transform:none;">
                            <input type="radio" name="available" value="Y" <?php echo ($car['AVAILABLE']=='Y')?'checked':''; ?> style="width: auto;"> Available
                         </label>
                         <label style="color:white; display:flex; align-items:center; gap:5px; text-transform:none;">
                            <input type="radio" name="available" value="N" <?php echo ($car['AVAILABLE']=='N')?'checked':''; ?> style="width: auto;"> Busy/Maintenance
                         </label>
                    </div>
                </div>

                <button type="submit" class="submit-btn" name="updatecar">UPDATE VEHICLE</button>
            </form>
        </div>
    </main>
</body>
</html>
