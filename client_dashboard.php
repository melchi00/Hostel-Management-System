<?php
include 'functions.php';
session_start();

if (!isset($_SESSION['client_logged_in']) || !$_SESSION['client_logged_in']) {
    header('Location: client_login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logout();
}
$conn = new mysqli($servername, $username, $password);
$conn->select_db("hostel_allocation");

// Fetch available rooms
$sql = "SELECT * FROM rooms WHERE is_available = TRUE";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Client Dashboard</h1>
        </header>
        <nav>
            <ul>
                <li><a href="client_dashboard.php">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <main>
            <section class="room-application">
                <h2>Available Rooms</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Room Number</th>
                            <th>Room Type</th>
                            <th>Room Category</th>
                            <th>Bathroom Option</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                                <td><?php echo htmlspecialchars($row['room_category']); ?></td>
                                <td><?php echo htmlspecialchars($row['bathroom_option']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td>
                                    <form action="apply_for_room.php" method="post">
                                        <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                        <button type="submit">Apply</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </main>
        <footer>
            <p>&copy; 2024 Hostel Allocation Management System</p>
        </footer>
    </div>
</body>
</html>

<?php
$conn->close();
?>
