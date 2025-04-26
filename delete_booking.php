<!-- filepath: c:\xampp\htdocs\drila-02\BusTimeTable-DBMS-main\delete_booking.php -->
<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit();
}

include('code.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    $query = "DELETE FROM bookings WHERE booking_id = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $booking_id);
        if (mysqli_stmt_execute($stmt)) {
            header("location: user_dashboard.php");
            exit();
        } else {
            echo "Error deleting booking.";
        }
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
?>