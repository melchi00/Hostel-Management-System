<?php
include 'config.php'; // Ensure this includes the code to establish the $conn database connection
include 'functions.php';
session_start();

// Check if client is logged in
if (!isset($_SESSION['client_logged_in']) || !$_SESSION['client_logged_in']) {
    header('Location: client_login.php');
    exit();
}

if (!isset($_SESSION['client_id'])) {
    // Handle the case where client_id is not set
    die('Client ID is not set. Please log in again.');
}

$client_id = $_SESSION['client_id'];

$conn = new mysqli($servername, $username, $password);
$conn->select_db("hostel_allocation");

$message = ''; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['apply_for_room'])) {
        if (hasClientAppliedForRoom($client_id)) {
            $message = "You have already applied for a room.";
        } else {
            $room_id = $_POST['room_id'];
            $allocation_date = date('Y-m-d');
            $status = 'pending';

            // Check if the room is available
            $room_check_sql = "SELECT is_available FROM rooms WHERE id = ?";
            $room_stmt = $conn->prepare($room_check_sql);
            $room_stmt->bind_param("i", $room_id);
            $room_stmt->execute();
            $room_stmt->bind_result($is_available);
            $room_stmt->fetch();
            $room_stmt->close();

            if ($is_available) {
                // Prepare the SQL statement to insert a new application
                $sql = "INSERT INTO allocations (client_id, room_id, allocation_date, status) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iiss", $client_id, $room_id, $allocation_date, $status);

                if ($stmt->execute()) {
                    $message = "Room application pending. The admin will review your application soon.";
                } else {
                    $message = "Error submitting application: " . $conn->error;
                }

                $stmt->close();
            } else {
                $message = "The selected room is no longer available.";
            }
        }
    } elseif (isset($_POST['logout'])) {
        // Handle logout
        session_unset();
        session_destroy();
        header('Location: index.php'); // Redirect to index.php after logout
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Apply for Room</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Apply for a Room</h1>
        </header>
        <main>
            <section>
                <?php if (!empty($message)): ?>
                <p><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>

                <?php 
                // Fetch available rooms for display
                $conn = new mysqli($servername, $username, $password);
                $conn->select_db("hostel_allocation");
                $sql = "SELECT id, room_number, room_type, room_category, bathroom_option FROM rooms WHERE is_available = 1";
                $result = $conn->query($sql);
                ?>

                <?php if ($result->num_rows > 0): ?>
                <form action="apply_for_room.php" method="post">
                    <label for="room_id">Select Room:</label>
                    <select id="room_id" name="room_id" required>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo "Room Number: " . $row['room_number'] . " | Type: " . $row['room_type'] . " | Category: " . $row['room_category'] . " | Bathroom: " . $row['bathroom_option']; ?>
                        </option>
                        <?php endwhile; ?>
                    </select><br>
                    <input type="submit" name="apply_for_room" value="Apply">
                </form>
                <?php else: ?>
                <p>No rooms available at the moment.</p>
                <?php endif; ?>

                <!-- Logout form -->
                <form action="apply_for_room.php" method="post">
                    <input type="submit" name="logout" value="Logout">
                </form>
            </section>
        </main>
        <footer>
            <p>&copy; 2024 Hostel Allocation Management System</p>
        </footer>
    </div>
</body>
</html>
