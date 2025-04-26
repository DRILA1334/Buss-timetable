<!-- filepath: c:\xampp\htdocs\drila-02\BusTimeTable-DBMS-main\index.php -->
<?php
session_start();
include('code.php');

// Check if the user is logged in
$logged_in = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;

// Handle Add Bus Schedule
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_bus"])) {
    $bus_number = $_POST["bus_number"];
    $route = $_POST["route"];
    $departure_time = $_POST["departure_time"];
    $arrival_time = $_POST["arrival_time"];
    $days = $_POST["days"];

    $query = "INSERT INTO bus_schedules (bus_number, route, departure_time, arrival_time, days, status) VALUES (?, ?, ?, ?, ?, 'active')";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "sssss", $bus_number, $route, $departure_time, $arrival_time, $days);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Handle Delete Bus Schedule
if (isset($_GET["delete_id"])) {
    $delete_id = $_GET["delete_id"];
    $query = "DELETE FROM bus_schedules WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $delete_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Fetch bus schedules
$query = "SELECT * FROM bus_schedules WHERE status = 'active'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching bus schedules: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Schedules</title>
    <link rel="stylesheet" href="style3.css"> <!-- Include the CSS file -->
</head>
<body>
    <header>
        <h1>Welcome to Bus Schedule Management</h1>
        <nav>
            <ul>
                <?php if ($logged_in): ?>
                    <li>Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>!</li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">User Login</a></li>
                    <li><a href="register.php">User Registration</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Active Bus Schedules</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Bus Number</th>
                    <th>Route</th>
                    <th>Departure Time</th>
                    <th>Arrival Time</th>
                    <th>Days</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['bus_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['route']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['departure_time']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['arrival_time']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['days']) . "</td>";
                        echo "<td>
                                <a href='update_bus.php?id=" . $row['id'] . "'>Edit</a> |
                                <a href='index.php?delete_id=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No active buses found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Add New Bus Schedule</h2>
        <form action="index.php" method="post" style="max-width: 500px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background-color: #f9f9f9;">
            <div style="margin-bottom: 15px;">
            <label for="bus_number" style="display: block; font-weight: bold; margin-bottom: 5px;">Bus Number:</label>
            <input type="text" name="bus_number" id="bus_number" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
            <label for="route" style="display: block; font-weight: bold; margin-bottom: 5px;">Route:</label>
            <input type="text" name="route" id="route" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
            <label for="departure_time" style="display: block; font-weight: bold; margin-bottom: 5px;">Departure Time:</label>
            <input type="time" name="departure_time" id="departure_time" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
            <label for="arrival_time" style="display: block; font-weight: bold; margin-bottom: 5px;">Arrival Time:</label>
            <input type="time" name="arrival_time" id="arrival_time" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
            <label for="days" style="display: block; font-weight: bold; margin-bottom: 5px;">Days:</label>
            <input type="text" name="days" id="days" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
            </div>

            <div style="text-align: center;">
            <button type="submit" name="add_bus" style="padding: 10px 20px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;">Add Bus</button>
            </div>
        </form>
    </main>
</body>
</html>