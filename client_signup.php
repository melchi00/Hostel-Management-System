<?php
include 'config.php'; // Ensure this includes the code to establish the $conn database connection
include 'functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (registerClient($username, $email, $password)) {
        $_SESSION['client_logged_in'] = true;
        $_SESSION['client_id'] = getClientIdByEmail($email);
        header("Location: client_dashboard.php");
        exit();
    } else {
        $error = "Error registering account. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Sign Up - Hostel Allocation Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Client Sign Up</h1>
        </header>
        <main>
            <form action="client_signup.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Sign Up</button>
                <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            </form>
        </main>
    </div>
</body>
</html>
