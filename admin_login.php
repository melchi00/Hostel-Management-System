<?php
session_start();
include 'functions.php';

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logout();
    exit(); // Ensure that the script terminates after logout
}

// Redirect to admin dashboard if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_dashboard.php");
    exit();
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (authenticateAdmin($username, $password)) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['username'] = $username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Hostel Allocation Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Admin Login</h1>
        </header>
        <main>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

            <?php if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']): ?>
            <form action="admin_login.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>
                <button type="submit" name="login">Login</button>
                <button type="submit" name="logout">Logout</button>
            </form>
            <?php else: ?>
            <p>You are already logged in. <button type="submit" name="logout" form="logoutForm">Logout</button></p>
            <form id="logoutForm" action="admin_login.php" method="post">
                <input type="hidden" name="logout" value="true">
            </form>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
