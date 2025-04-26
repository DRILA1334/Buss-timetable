<!-- filepath: c:\xampp\htdocs\drila-02\BusTimeTable-DBMS-main\update_bus.php -->
<?php
include('code.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the bus details
    $query = "SELECT * FROM bus_schedules WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $bus = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
}

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bus_number = $_POST["bus_number"];
    $route = $_POST["route"];
    $departure_time = $_POST["departure_time"];
    $arrival_time = $_POST["arrival_time"];
    $days = $_POST["days"];

    $query = "UPDATE bus_schedules SET bus_number = ?, route = ?, departure_time = ?, arrival_time = ?, days = ? WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "sssssi", $bus_number, $route, $departure_time, $arrival_time, $days, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("location: index.php");
        exit();
    }
}
?>

<!-- filepath: c:\xampp\htdocs\drila-02\BusTimeTable-DBMS-main\update_bus.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Bus Schedule</title>
    <link rel="stylesheet" href="syle.css"> <!-- Include the CSS file -->
</head>
<body>
    <header>
        <h1>Update Bus Schedule</h1>
    </header>
    <main>
        <form action="update_bus.php?id=<?php echo $id; ?>" method="post">
            <label for="bus_number">Bus Number:</label>
            <input type="text" name="bus_number" id="bus_number" value="<?php echo htmlspecialchars($bus['bus_number']); ?>" required>

            <label for="route">Route:</label>
            <input type="text" name="route" id="route" value="<?php echo htmlspecialchars($bus['route']); ?>" required>

            <label for="departure_time">Departure Time:</label>
            <input type="time" name="departure_time" id="departure_time" value="<?php echo htmlspecialchars($bus['departure_time']); ?>" required>

            <label for="arrival_time">Arrival Time:</label>
            <input type="time" name="arrival_time" id="arrival_time" value="<?php echo htmlspecialchars($bus['arrival_time']); ?>" required>

            <label for="days">Days:</label>
            <input type="text" name="days" id="days" value="<?php echo htmlspecialchars($bus['days']); ?>" required>

            <button type="submit">Update Bus</button>
        </form>
    </main>
</body>
</html>