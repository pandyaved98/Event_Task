<?php
require_once('connect.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get other event details from POST data
    $eventName = isset($_POST['eventName']) ? trim($_POST['eventName']) : '';
    $startTime = isset($_POST['startTime']) ? trim($_POST['startTime']) : '';
    $endTime = isset($_POST['endTime']) ? trim($_POST['endTime']) : '';
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';

    // Validate input data
    if (empty($eventName) || empty($startTime) || empty($endTime) || empty($location) || empty($category)) {
        // Return validation error response
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }

    // Handle file upload
    $uploadDir = 'N:/xampp/htdocs/allevent_task/backend/images/';

    // Check if the directory exists, create it if necessary
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Set appropriate permissions
    chmod($uploadDir, 0777);

    $bannerImage = '';

    if (isset($_FILES['bannerImage']) && $_FILES['bannerImage']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['bannerImage']['tmp_name'];
        $bannerImage = basename($_FILES['bannerImage']['name']);

        // Debugging statements
        error_log("Upload Directory: " . $uploadDir);
        error_log("Temporary Name: " . $tmpName);
        error_log("Banner Image: " . $bannerImage);

        // Attempt to move the file
        if (move_uploaded_file($tmpName, $uploadDir . $bannerImage)) {
            // File moved successfully
            error_log("File moved successfully");
        } else {
            // Error moving file
            error_log("Error moving file");
        }
    } else {
        // Debugging statement for file upload error
        error_log("File upload error: " . $_FILES['bannerImage']['error']);
    }

    // Insert event into the database
    $sql = "INSERT INTO events (event_name, start_time, end_time, location, description, category, banner_image)
            VALUES ('$eventName', '$startTime', '$endTime', '$location', '$description', '$category', '$bannerImage')";

    if ($conn->query($sql) === TRUE) {
        // Return success response
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Event created successfully']);
    } else {
        // Return error response
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Error creating event']);
    }
} else {
    // Return method not allowed response for non-POST requests
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
}

// Close the database connection
$conn->close();
?>