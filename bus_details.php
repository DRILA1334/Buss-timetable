<?php
// Include the database connection
include('code.php');

// Fetch bus details based on bus number (or ID) passed through the URL
if (isset($_GET['bus_number'])) {
    $bus_number = mysqli_real_escape_string($conn, $_GET['bus_number']);
    
    // Query to get the details of the specific bus
    $query = "SELECT * FROM bus_schedules WHERE bus_number = '$bus_number' AND status = 'active' LIMIT 1";
    $result = mysqli_query($conn, $query);

    // Check if the bus is found
    if (mysqli_num_rows($result) == 0) {
        echo "Bus not found or it is not active.";
        exit;
    }

    $bus = mysqli_fetch_assoc($result);
} else {
    echo "Bus number is required.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Details - <?php echo htmlspecialchars($bus['bus_number']); ?></title>
    <link rel="stylesheet" href="style2.css"> <!-- Include your custom CSS -->
</head>
<body>
    <header>
        <h1>Bus Details</h1>
        <nav>
            <ul>
                <li><a href="index.php">Back to Bus Schedules</a></li>
                <li><a href="login.php">User Login</a></li>
                <li><a href="register.php">User Registration</a></li>
                <li><a href="admin_login.php">Admin Login</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- Display Bus Details -->
        <h2>Bus Information</h2>
        <table border="1">
            <tr>
                <th>Bus Number</th>
                <td><?php echo htmlspecialchars($bus['bus_number']); ?></td>
            </tr>
            <tr>
                <th>Route</th>
                <td><?php echo htmlspecialchars($bus['route']); ?></td>
            </tr>
            <tr>
                <th>Departure Time</th>
                <td><?php echo htmlspecialchars($bus['departure_time']); ?></td>
            </tr>
            <tr>
                <th>Arrival Time</th>
                <td><?php echo htmlspecialchars($bus['arrival_time']); ?></td>
            </tr>
            <tr>
                <th>Days</th>
                <td><?php echo htmlspecialchars($bus['days']); ?></td>
            </tr>
            <tr>
                <th>Capacity</th>
                <td><?php echo htmlspecialchars($bus['capacity']); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo htmlspecialchars($bus['status']); ?></td>
            </tr>
        </table>
    </main>
</body>
</html>
