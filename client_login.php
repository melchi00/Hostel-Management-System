<?php
session_start();
include 'config.php';
include 'functions.php';

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logout();
    exit(); // Ensure that the script terminates after logout
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (authenticateClient($email, $password)) {
        $_SESSION['client_logged_in'] = true;
        $_SESSION['client_id'] = getClientIdByEmail($email);
        header("Location: client_dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Client Login</h1>
        </header>
        <main>
            <?php if (!isset($_SESSION['client_logged_in']) || !$_SESSION['client_logged_in']): ?>
            <form action="client_login.php" method="post">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Login</button>
            </form>
            <?php else: ?>
            <form action="client_login.php" method="post">
                <p>You are already logged in. <button type="submit" name="logout">Logout</button></p>
            </form>
            <?php endif; ?>
            
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        </main>
    </div>
</body>
</html>
