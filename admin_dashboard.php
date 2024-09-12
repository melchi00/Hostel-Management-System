<?php
include 'functions.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: admin_login.php');
    exit();
}

$conn = new mysqli($servername, $username, $password);
$conn->select_db("hostel_allocation");

// Fetch available rooms
$sql_rooms = "SELECT * FROM rooms";
$result_rooms = $conn->query($sql_rooms);

// Fetch pending applications
$sql_apps = "SELECT allocations.id AS application_id, clients.username AS client_username, rooms.room_number AS room_number, allocations.allocation_date AS allocation_date, allocations.status AS status 
             FROM allocations 
             JOIN clients ON allocations.client_id = clients.id 
             JOIN rooms ON allocations.room_id = rooms.id 
             WHERE allocations.status = 'pending'";
$result_apps = $conn->query($sql_apps);

// Handle application status updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_application'])) {
        $application_id = $_POST['application_id'];
        $status = $_POST['status'];

        // Prepare the SQL statement to update the application status
        $sql_update = "UPDATE allocations SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("si", $status, $application_id);

        if ($stmt->execute()) {
            // If application is accepted, update the room's availability
            if ($status == 'accepted') {
                $update_room_sql = "UPDATE rooms SET is_available = 0 WHERE id = (SELECT room_id FROM allocations WHERE id = ?)";
                $update_stmt = $conn->prepare($update_room_sql);
                $update_stmt->bind_param("i", $application_id);
                $update_stmt->execute();
                $update_stmt->close();
            }

            echo "<p>Application status updated successfully.</p>";
        } else {
            echo "<p>Error updating application status: " . $conn->error . "</p>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Admin Dashboard</h1>
        </header>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <main>
            <section class="room-management">
                <h2>Manage Rooms</h2>
                <form action="admin_manage_rooms.php" method="post">
                    <input type="text" name="room_number" placeholder="Room Number" required>
                    <select name="room_type" required>
                        <option value="Individual">Individual</option>
                        <option value="Shared">Shared</option>
                    </select>
                    <select name="room_category" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    <select name="bathroom_option" required>
                        <option value="In-Room">In-Room</option>
                        <option value="Outside-Room">Outside-Room</option>
                    </select>
                    <textarea name="description" placeholder="Description"></textarea>
                    <button type="submit">Add Room</button>
                </form>

                <h2>Available Rooms</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Room Number</th>
                            <th>Room Type</th>
                            <th>Room Category</th>
                            <th>Bathroom Option</th>
                            <th>Description</th>
                            <th>Availability</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_rooms->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                                <td><?php echo htmlspecialchars($row['room_category']); ?></td>
                                <td><?php echo htmlspecialchars($row['bathroom_option']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo $row['is_available'] ? 'Available' : 'Not Available'; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>

            <section class="applications-management">
                <h2>Pending Applications</h2>
                <?php if ($result_apps->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Client Username</th>
                            <th>Room Number</th>
                            <th>Allocation Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_apps->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['client_username']); ?></td>
                            <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['allocation_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <form action="admin_dashboard.php" method="post">
                                    <input type="hidden" name="application_id" value="<?php echo $row['application_id']; ?>">
                                    <select name="status" required>
                                        <option value="accepted">Accept</option>
                                        <option value="refused">Refuse</option>
                                    </select>
                                    <input type="submit" name="update_application" value="Update">
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p>No pending applications.</p>
                <?php endif; ?>
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
