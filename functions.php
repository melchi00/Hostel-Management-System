<?php
// functions.php
include 'config.php'; // Ensure this includes the code to establish the $conn database connection

// Ensure the connection is open and available throughout the script
global $conn;
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function authenticateAdmin($username, $password) {
    global $conn;
    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        return password_verify($password, $admin['password']);
    }
    return false;
}

function authenticateClient($email, $password) {
    global $conn;
    $sql = "SELECT id, password FROM clients WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log('mysqli prepare() failed: ' . htmlspecialchars($conn->error));
        return false;
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($client_id, $hashed_password);
    if ($stmt->fetch()) {
        if (password_verify($password, $hashed_password)) {
            $stmt->close();
            return true;
        }
    }
    $stmt->close();
    return false;
}


function getClientIdByEmail($email) {
    global $conn;
    $sql = "SELECT id FROM clients WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        error_log('mysqli prepare() failed: ' . htmlspecialchars($conn->error));
        return null;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($client_id);
    
    if ($stmt->fetch()) {
        $stmt->close();
        return $client_id;
    } else {
        $stmt->close();
        return null;
    }
}


function fetchAvailableRooms() {
    global $conn;
    $sql = "SELECT * FROM rooms WHERE is_available = TRUE";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getAvailableRooms() {
    global $conn;
    $sql = "SELECT * FROM rooms WHERE is_available = 1";
    $result = $conn->query($sql);
    return $result;
}

function fetchPendingApplications() {
    global $conn;
    $sql = "SELECT a.id, c.username AS client_username, r.room_number, a.allocation_date, a.status
            FROM allocations a
            JOIN clients c ON a.client_id = c.id
            JOIN rooms r ON a.room_id = r.id
            WHERE a.status = 'pending'";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function applyForRoom($clientId, $roomId) {
    global $conn;
    $sql = "INSERT INTO allocations (client_id, room_id, allocation_date, status) VALUES (?, ?, CURDATE(), 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $clientId, $roomId);
    if ($stmt->execute()) {
        $updateRoomAvailability = "UPDATE rooms SET is_available = 0 WHERE id = ?";
        $stmt = $conn->prepare($updateRoomAvailability);
        $stmt->bind_param("i", $roomId);
        return $stmt->execute();
    }
    return false;
}

function hasClientAppliedForRoom($clientId) {
    global $conn;
    $sql = "SELECT * FROM allocations WHERE client_id = ? AND status IN ('pending', 'approved')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}


function registerClient($username, $email, $password) {
    global $conn;
    
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        return false;
    }

    $sql = "INSERT INTO clients (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        error_log('mysqli prepare() failed: ' . htmlspecialchars($conn->error));
        return false;
    }

    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        error_log('mysqli execute() failed: ' . htmlspecialchars($stmt->error));
        $stmt->close();
        return false;
    }
}


function registerAdmin($username, $password) {
    global $conn;
    $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    return $stmt->execute();
}

function getAllClients() {
    global $conn;
    $sql = "SELECT * FROM clients";
    $result = $conn->query($sql);
    return $result;
}

function chooseRoom($clientId, $roomId) {
    global $conn;
    $sql = "UPDATE rooms SET is_available = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $roomId);
    if ($stmt->execute()) {
        $sql = "INSERT INTO allocations (client_id, room_id, allocation_date, status) VALUES (?, ?, CURDATE(), 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $clientId, $roomId);
        return $stmt->execute();
    }
    return false;
}

function updateRoomChoiceStatus($allocationId, $status) {
    global $conn;
    $sql = "UPDATE allocations SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $allocationId);
    if ($stmt->execute()) {
        if ($status === 'refused') {
            // Make the room available again
            $sql = "SELECT room_id FROM allocations WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $allocationId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $allocation = $result->fetch_assoc();
                $roomId = $allocation['room_id'];
                $sql = "UPDATE rooms SET is_available = 1 WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $roomId);
                $stmt->execute();
            }
        }
        return true;
    }
    return false;
}

function getAllocations() {
    global $conn;
    $sql = "SELECT allocations.id AS allocation_id, clients.username AS client_username, rooms.room_number, rooms.room_type, rooms.room_category, rooms.bathroom_option, allocations.allocation_date, allocations.status
            FROM allocations
            JOIN clients ON allocations.client_id = clients.id
            JOIN rooms ON allocations.room_id = rooms.id";
    $result = $conn->query($sql);
    return $result;
}

function logout() {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}
?>
