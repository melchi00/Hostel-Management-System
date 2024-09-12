<?php
include 'functions.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: admin_login.php');
    exit();
}

// Handle logout
if (isset($_POST['logout'])) {
    session_unset(); // Clear all session variables
    session_destroy(); // Destroy the session
    header('Location: index.php'); // Redirect to index.php after logout
    exit();
}

$conn = new mysqli($servername, $username, $password);
$conn->select_db("hostel_allocation");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_room'])) {
        $room_number = $_POST['room_number'];
        $room_type = $_POST['room_type'];
        $room_category = $_POST['room_category'];
        $bathroom_option = $_POST['bathroom_option'];
        $description = $_POST['description'];
        $is_available = $_POST['is_available'];

        // Prepare the SQL statement with the correct column names
        $sql = "INSERT INTO rooms (room_number, room_type, room_category, bathroom_option, description, is_available) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $room_number, $room_type, $room_category, $bathroom_option, $description, $is_available);

        if ($stmt->execute()) {
            echo "Room added successfully.";
        } else {
            echo "Error adding room: " . $conn->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Manage Rooms</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Admin Manage Rooms</h1>
        </header>
        <main>
            <section>
                <h2>Add New Room</h2>
                <form action="admin_manage_rooms.php" method="post">
                    <label for="room_number">Room Number:</label>
                    <input type="text" id="room_number" name="room_number" required><br>
                    <label for="room_type">Room Type:</label>
                    <select id="room_type" name="room_type" required>
                        <option value="Individual">Individual</option>
                        <option value="Shared">Shared</option>
                    </select><br>
                    <label for="room_category">Room Category:</label>
                    <select id="room_category" name="room_category" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select><br>
                    <label for="bathroom_option">Bathroom Option:</label>
                    <select id="bathroom_option" name="bathroom_option" required>
                        <option value="Shared">Shared</option>
                        <option value="Ensuite">Ensuite</option>
                    </select><br>
                    <label for="description">Description:</label>
                    <textarea id="description" name="description"></textarea><br>
                    <label for="is_available">Available:</label>
                    <select id="is_available" name="is_available" required>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select><br>
                    <input type="submit" name="add_room" value="Add Room">
                </form>
            </section>
            <section>
                <form action="admin_manage_rooms.php" method="post">
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
