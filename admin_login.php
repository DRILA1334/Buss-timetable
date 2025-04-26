<?php
// Include the database connection
include('code.php');

// Start the session to store admin information after successful login
session_start();

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";

// Process the login form when it is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check if there are no errors
    if (empty($email_err) && empty($password_err)) {
        // Prepare a SELECT statement to fetch admin details by email
        $query = "SELECT id, name, email, password, role FROM users WHERE email = ? AND role = 'admin'";

        if ($stmt = mysqli_prepare($conn, $query)) {
            // Bind the email parameter to the statement
            mysqli_stmt_bind_param($stmt, "s", $email);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Store the result
                mysqli_stmt_store_result($stmt);

                // Check if the email exists and if the user is an admin
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $name, $email_db, $hashed_password, $role);
                    if (mysqli_stmt_fetch($stmt)) {
                        // Check if the password is correct
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session and store admin details
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["name"] = $name;
                            $_SESSION["email"] = $email_db;
                            $_SESSION["role"] = $role;

                            // Redirect to the admin dashboard
                            header("location: admin_dashboard.php");
                            exit();
                        } else {
                            // Password is not valid
                            $password_err = "The password you entered is incorrect.";
                        }
                    }
                } else {
                    // Email doesn't exist or not an admin
                    $email_err = "No admin account found with that email.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="syle3.css"> <!-- Include your custom CSS -->
</head>
<body>
    <header>
        <h1>Admin Login</h1>
        <nav>
            <ul>
                <li><a href="index.php">Back to Bus Schedules</a></li>
                <li><a href="user_login.php">User Login</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Login to Your Admin Account</h2>
        <form action="admin_login.php" method="post">
            <!-- Email Field -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo $email; ?>" required>
                <span class="error"><?php echo $email_err; ?></span>
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                <span class="error"><?php echo $password_err; ?></span>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>

        <p>Don't have an admin account? <a href="register.php">Register here</a></p>
    </main>
</body>
</html>