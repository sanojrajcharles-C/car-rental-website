<?php

require_once('connection.php');
$bookid = $_GET['id'];

$sql = "SELECT * FROM booking WHERE BOOK_ID=$bookid";
$result = mysqli_query($con, $sql);
$res = mysqli_fetch_assoc($result);

if ($res['BOOK_STATUS'] == 'REJECTED') {
    echo '<script>alert("ALREADY REJECTED")</script>';
    echo '<script> window.location.href = "adminbook.php";</script>';
} else {
    $query = "UPDATE booking SET BOOK_STATUS='REJECTED' WHERE BOOK_ID=$bookid";
    $queryy = mysqli_query($con, $query);

    // If it was already approved (unlikely flow but safe), we should free up the car
    if ($res['BOOK_STATUS'] == 'APPROVED') {
        $carid = $res['CAR_ID'];
        mysqli_query($con, "UPDATE cars SET AVAILABLE='Y' WHERE CAR_ID=$carid");
    }

    echo '<script>alert("BOOKING REJECTED SUCCESSFULLY")</script>';
    echo '<script> window.location.href = "adminbook.php";</script>';
}
?>