<?php
// Start the session
session_start();

// Check if the user is logged in, else redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit();
}

// Include the database connection
include('code.php');

// Fetch the user's bookings from the database (optional)
$user_id = $_SESSION["id"];
$bookings = [];

$query = "SELECT * FROM bookings WHERE user_id = ? ORDER BY booking_date DESC";
if ($stmt = mysqli_prepare($conn, $query)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $bookings[] = $row;
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($conn);

// Handle logout action
if (isset($_POST['logout'])) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("location: login.php"); // Redirect to the login page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style2.css"> <!-- Include your custom CSS -->
</head>
<body>
    <header>
        <h1>Welcome to Your Dashboard, <?php echo $_SESSION["name"]; ?>!</h1>
        <nav>
            <ul>
                <li><a href="index.php">Back to Bus Schedules</a></li>
                <li><a href="user_dashboard.php">Dashboard</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Your Bookings</h2>

        <?php if (count($bookings) > 0): ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Bus Number</th>
                        <th>Route</th>
                        <th>Departure Time</th>
                        <th>Arrival Time</th>
                        <th>Booking Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                            <td><?php echo htmlspecialchars($booking['bus_number']); ?></td>
                            <td><?php echo htmlspecialchars($booking['route']); ?></td>
                            <td><?php echo htmlspecialchars($booking['departure_time']); ?></td>
                            <td><?php echo htmlspecialchars($booking['arrival_time']); ?></td>
                            <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have no bookings yet.</p>
        <?php endif; ?>

        <!-- Logout form -->
        <form action="user_dashboard.php" method="post">
            <div class="form-group">
                <button type="submit" name="logout">Logout</button>
            </div>
        </form>
    </main>
</body>
</html>
