<?php
header('Content-Type: application/json');

// Include the database connection file
require_once 'connect.php';

// Check if event_id is provided
if (!isset($_POST['event_id'])) {
    echo json_encode(['error' => 'Event ID not provided']);
    exit;
}

// Get event_id from POST data
$event_id = $_POST['event_id'];

// Use prepared statement to avoid SQL injection
$stmt = $conn->prepare("DELETE FROM events WHERE event_id = ?");
$stmt->bind_param("i", $event_id);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(['message' => 'Event deleted successfully']);
} else {
    echo json_encode(['error' => 'Error deleting event']);
}

// Close the statement and database connection
$stmt->close();
$conn->close();
?>