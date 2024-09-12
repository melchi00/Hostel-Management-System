<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select the database
$conn->select_db("hostel_allocation");

// Create tables
$adminsTable = "CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";

$clientsTable = "CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    approved BOOLEAN DEFAULT FALSE
)";

$roomsTable = "CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(10) NOT NULL UNIQUE,
    room_type ENUM('Individual', 'Shared') NOT NULL,
    room_category ENUM('Male', 'Female') NOT NULL,
    bathroom_option ENUM('In-Room', 'Outside-Room') NOT NULL,
    description TEXT,
    is_available BOOLEAN DEFAULT TRUE,
    UNIQUE(room_number, room_type, room_category) -- Ensures unique combination of room attributes
)";

$allocationsTable = "CREATE TABLE IF NOT EXISTS allocations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    room_id INT NOT NULL,
    allocation_date DATE NOT NULL,
    status ENUM('pending', 'allocated', 'vacant') DEFAULT 'pending',
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
)";

if ($conn->query($adminsTable) === TRUE) {
    echo "Table admins created successfully\n";
} else {
    echo "Error creating admins table: " . $conn->error . "\n";
}

if ($conn->query($clientsTable) === TRUE) {
    echo "Table clients created successfully\n";
} else {
    echo "Error creating clients table: " . $conn->error . "\n";
}

if ($conn->query($roomsTable) === TRUE) {
    echo "Table rooms created successfully\n";
} else {
    echo "Error creating rooms table: " . $conn->error . "\n";
}

if ($conn->query($allocationsTable) === TRUE) {
    echo "Table allocations created successfully\n";
} else {
    echo "Error creating allocations table: " . $conn->error . "\n";
}

// Add indexes for foreign keys in allocations table
$sql1 = "ALTER TABLE allocations
         ADD INDEX idx_client_id (client_id),
         ADD INDEX idx_room_id (room_id)";
if ($conn->query($sql1) === TRUE) {
    echo "Indexes added successfully\n";
} else {
    echo "Error adding indexes: " . $conn->error . "\n";
}

$conn->close();
?>
