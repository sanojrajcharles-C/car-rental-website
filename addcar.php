<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Vehicle - Admin Dashboard</title>
    <link rel="stylesheet" href="css/addcar.css?v=<?php echo time(); ?>">
    <script type="text/javascript">
        function preventBack() { window.history.forward(); }
        setTimeout("preventBack()", 0);
        window.onunload = function () { null };
    </script>
</head>

<body>
    <header class="navbar">
        <h2 class="logo">CaRs Admin</h2>
        <a href="adminvehicle.php" class="back-btn">BACK TO FLEET</a>
    </header>

    <main class="form-container">
        <div class="form-card">
            <div class="form-header">
                <h2>New Vehicle Entry</h2>
                <p>Register a new luxury car into the rental fleet.</p>
            </div>

            <form id="addcar-form" action="upload.php" method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Car Name</label>
                        <input type="text" name="carname" placeholder="e.g. Lamborghini Huracán" required autofocus>
                    </div>

                    <div class="form-group">
                        <label>Fuel Type</label>
                        <select name="ftype" required>
                            <option value="" disabled selected>Select Fuel Type</option>
                            <option value="Petrol">Petrol</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Electric">Electric</option>
                            <option value="Gas">Gas</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Capacity</label>
                        <input type="number" name="capacity" min="1" placeholder="Number of seats" required>
                    </div>

                    <div class="form-group full-width">
                        <label>Daily Rental Price (₹)</label>
                        <input type="number" name="price" min="1" placeholder="Enter amount per day" required>
                    </div>

                    <div class="form-group full-width file-upload">
                        <label>Vehicle Showcase Image</label>
                        <input type="file" name="image" accept="image/*" required>
                    </div>
                </div>

                <button type="submit" class="submit-btn" name="addcar">REGISTER VEHICLE</button>
            </form>
        </div>
    </main>

    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>

</html>